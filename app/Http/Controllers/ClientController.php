<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

use App\Models\Client;
use App\Models\Phone;
use Auth;

class ClientController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:client-list');
         $this->middleware('permission:client-create', ['only' => ['create','store']]);
         $this->middleware('permission:client-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:client-delete', ['only' => ['destroy']]);
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
            $list = Client::whereHas('phones', function ($query) use ($search) {
                $query->where('number', 'like', '%'.$search.'%');
            })
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orderBy('name')->paginate(10);
        } else {
            $list = Client::orderBy('name')->paginate(10);
        }

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de clientes');

        return view('clients.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de cadastro de clientes');

        return view('clients.edit');
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
            'email' => 'required|unique:clients|email',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $client = new Client;
        $client->fill($request->all());
        $client->save();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($client)
        ->log('cadastrou o cliente ('.$client->name.')');

        return redirect()
                ->route('clients.index')
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
        $client = Client::findOrFail($id);

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de edição de clientes');

        return view('clients.edit', compact('client'));
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
            'email' => 'required|email|unique:clients,email,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $client = Client::findOrFail($id);
        $client->fill($request->all());
        $client->save();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($client)
        ->log('alterou o cliente ('.$client->name.')');

        return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente Alterado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->phones()->delete();
        $client->delete();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($client)
        ->log('removeu o cliente ('.$client->name.')');

        return redirect()
                ->route('clients.index')
                ->with('success', 'Cliente Removido.');
    }
}
