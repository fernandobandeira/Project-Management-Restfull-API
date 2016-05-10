<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ClientRepositoryEloquent;
use CodeProject\Entities\Client;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ClientController extends Controller
{
    public function index(ClientRepositoryEloquent $repository) {
        return $repository->all();
    }

    public function store(Request $request) {
        return Client::create($request->all());
    }

    public function show($id) {
        return Client::find($id);
    }

    public function update(Request $request, $id) {
        $client = Client::find($id);
        if ($client->update($request->all()))
            return $client;
        abort(500);
    }

    public function destroy($id) {
        if (Client::find($id)->delete())
            return ['message' => 'Cliente deletado com sucesso'];
        abort(500);
    }
}