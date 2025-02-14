<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['client', 'products'])->get());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'products' => 'required|array',
                'products.*' => 'exists:products,id'
            ]);

            $order = Order::create(['client_id' => $validated['client_id']]);
            $order->products()->attach($validated['products']);

            $client = Client::findOrFail($validated['client_id']);

            // Enviar e-mail para o cliente
            Mail::to($client->email)->send(new OrderCreatedMail($order));

            return response()->json($order->load('products'), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar pedido',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(Order::with(['client', 'products'])->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order deleted'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar pedido'], 500);
        }
    }
}
