<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Client;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ClientController extends Controller
{
    public function index() {
        return Client::all();
    }

    public function store(Request $request) {
        return Client::create($request->all());
    }

    public function show($id) {
        return Client::find($id);
    }

    public function update(Request $request, $id) {
        $client = Client::find($id)->fill($request->all());
        $client->save();
        return $client;
    }

    public function destroy($id) {
        Client::find($id)->delete();
        return ['message' => 'Cliente deletado com sucesso'];
    }
}
