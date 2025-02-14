<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $path = $request->file('photo')->store('products');

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'photo' => $path
            ]);

            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar produto',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(Product::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string',
                'price' => 'sometimes|numeric',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('photo')) {
                Storage::delete($product->photo);
                $path = $request->file('photo')->store('products');
                $validated['photo'] = $path;
            }

            $product->update($validated);
            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar produto',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            Storage::delete($product->photo);
            $product->delete();

            return response()->json(['message' => 'Produto deletado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao deletar produto',
            ], 500);
        }
    }
}
