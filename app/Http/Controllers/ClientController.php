<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:clients',
                'phone' => 'required|string',
                'birth_date' => 'required|date',
                'address' => 'required|string',
                'complement' => 'nullable|string',
                'neighborhood' => 'required|string',
                'zip_code' => 'required|string',
            ]);

            return response()->json(Client::create($validated), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show($id)
    {
        return response()->json(Client::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
    
            $validated = $request->validate([
                'name' => 'sometimes|string',
                'email' => 'sometimes|email|unique:clients,email,' . $id,
                'phone' => 'sometimes|string',
                'birth_date' => 'sometimes|date',
                'address' => 'sometimes|string',
                'complement' => 'nullable|string',
                'neighborhood' => 'sometimes|string',
                'zip_code' => 'sometimes|string'
            ]);
    
            $client->update($validated);
            return response()->json($client);
    
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
    
            return response()->json(['message' => 'Client deleted'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting client'], 500);
        }
    }
}
