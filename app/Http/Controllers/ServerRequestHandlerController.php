<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerRequestHandlerController extends Controller
{
    public function index(Request $request)
    {
        // Logic for handling the index action
        $hostname = $request->header('host');
        dd($hostname);
        return response()->json(['message' => 'Hello, World!']);
    }

    public function store(Request $request)
    {
        // Logic for handling the store action
        $data = $request->all();

        // Process the data and save it to the database or perform other operations

        return response()->json(['message' => 'Data stored successfully']);
    }

    public function show($id)
    {
        // Logic for handling the show action
        // Retrieve the data with the given $id from the database

        return response()->json(['message' => 'Data retrieved successfully']);
    }

    public function update(Request $request, $id)
    {
        // Logic for handling the update action
        $data = $request->all();

        // Update the data with the given $id in the database

        return response()->json(['message' => 'Data updated successfully']);
    }

    public function destroy($id)
    {
        // Logic for handling the destroy action
        // Delete the data with the given $id from the database

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
