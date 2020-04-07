<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list');
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Role::orderBy('id')->get();

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página regras');

        return view('roles.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('id')->get();

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de cadastro regras');

        return view('roles.edit', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->input('permission'));

        activity()
        ->causedBy(Auth::user())
        ->performedOn($role)
        ->log('cadastrou a regra ('.$role->name.')');

        return redirect()->route('roles.index')
                        ->with('success','Regra cadastrada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::orderBy('id')->get();
        $role = Role::findOrFail($id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

            activity()
            ->causedBy(Auth::user())
            ->log('acessou a página de edição de regras');

        return view('roles.edit', compact('permissions','role','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $role = Role::findOrFail($id);

        $roleName = $role->name;

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->input('permission'));

        activity()
        ->causedBy(Auth::user())
        ->performedOn($role)
        ->log('alterou a regra ('.$roleName.') para ('.$role->name.')');

        return redirect()->route('roles.index')
                        ->with('success','Regra alterada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($role)
        ->log('removeu a regra ('.$role->name.')');

        return redirect()->route('roles.index')
                        ->with('success','Regra removida.');
    }
}
