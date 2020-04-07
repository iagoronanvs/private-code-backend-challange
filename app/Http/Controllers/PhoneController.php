<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Phone;
use App\Models\Client;

use Auth;

class PhoneController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:phone-list');
         $this->middleware('permission:phone-create', ['only' => ['create','store']]);
         $this->middleware('permission:phone-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:phone-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($client_id)
    {
        $client = Client::findOrFail($client_id);
        $list = Phone::where('client_id', $client_id)->get();

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de telefones do cliente ('.$client->name.')');

        return view('phones.index', compact('list','client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        $client = Client::findOrFail($client_id);

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de cadastro de telefone do cliente ('.$client->name.')');

        return view('phones.edit', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $client_id)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $phone = new Phone;
        $phone->number = $request->number;
        $phone->client_id = $client_id;
        $phone->save();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($phone)
        ->log('cadastrou o telefone ('.$phone->number.')');

        return redirect()
                ->route('clients.phones.index', ['client_id' => $client_id])
                ->with('success', 'Telefone Cadastrado.');
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
    public function edit($client_id, $id)
    {
        $client = Client::findOrFail($client_id);
        $phone = Phone::findOrFail($id);

        activity()
        ->causedBy(Auth::user())
        ->log('acessou a página de edição de telefone do cliente ('.$client->name.')');

        return view('phones.edit', compact('client','phone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $client_id, $id)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $phone = Phone::findOrFail($id);
        $phone->number = $request->number;
        $phone->client_id = $client_id;
        $phone->save();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($phone)
        ->log('alterou o telefone ('.$phone->number.')');

        return redirect()
                ->route('clients.phones.index', ['client_id' => $client_id])
                ->with('success', 'Telefone Cadastrado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($client_id, $id)
    {
        $phone = Phone::findOrFail($id);
        $phone->delete();

        activity()
        ->causedBy(Auth::user())
        ->performedOn($phone)
        ->log('removeu o telefone ('.$phone->number.')');

        return redirect()
                ->route('clients.phones.index', ['client_id' => $client_id])
                ->with('success', 'Cliente Removido.');
    }
}
