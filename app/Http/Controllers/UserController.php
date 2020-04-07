<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Auth;

class UserController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:user-list');
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('query');

        if (isset($search)) {
            $list = User::where('name','like','%'.$search.'%')
                        ->orWhere('email','like','%'.$search.'%')
                        ->orderBy('name')->paginate(10);
        } else {
            $list = User::orderBy('name')->paginate(10);
        }

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de usuários');

        return view('users.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de cadastro de usuário');

        return view('users.edit', compact('roles'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->assignRole($request->roles);

        activity()
        ->causedBy(Auth::user())
        ->performedOn($user)
        ->log('cadastrou o usuário ('.$user->name.')');

        return redirect()
                ->route('users.index')
                ->with('success', 'Cliente Cadastrado.');
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
        $user = User::findOrFail($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de edição de usuário');

        return view('users.edit', compact('user','roles','userRole'));
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
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if (isset($request->password) && $request->password == $request->password_confirmation) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->roles);

        activity()
        ->causedBy(Auth::user())
        ->performedOn($user)
        ->log('alterou o usuário ('.$user->name.')');

        return redirect()
                ->route('users.index')
                ->with('success', 'Cliente Cadastrado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($user)
        ->log('removeu o usuário ('.$user->name.')');

        return redirect()
                ->route('users.index')
                ->with('success', 'Usuário Removido.');
    }
}
