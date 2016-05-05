<?php

namespace CodeProject\Http\Controllers;

use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ClientController extends Controller
{
    public function index() {
        return \CodeProject\Client::all();
    }

    public function store(Request $request) {
        return \CodeProject\Client::create($request->all());
    }
}
