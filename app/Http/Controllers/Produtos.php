<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Produtos extends Controller
{
    public function view_list(Request $request): View
    {
        return view('Product\List', json_decode($this->get_list($request)->getContent(), true));
    }

    public function get_list(Request $request): JsonResponse
    {
        $validate = $this->validate($request, ['per_page' => 'integer', 'page' => 'integer']);
        $products = Products::orderBy('id')->paginate(page: $validate['page'] ?? 1, perPage: $validate['per_page'] ?? 10)->toArray();
        return response()->json(compact("products"));
    }

    public function create(Request $request): JsonResponse
    {
        $validate = $this->validate($request, ['name' => 'string|required', 'stock_quantity' => 'integer|required', 'unity_price' => 'decimal:2|required']);

        Products::create($validate);

        return response()->json(['message' => 'product created'], 201);
    }

    public function product(int $id): JsonResponse
    {
        $product = Products::where('id', '=', $id)->first();

        if ($product !== null) {
            return response()->json(compact("product"));
        } else {
            return response()->json(['erro' => 'product', 'message' => 'product not found'], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = $this->validate($request, ['name' => 'string', 'stock_quantity' => 'integer', 'unity_price' => 'decimal:2']);
        $product = Products::where('id', '=', $id)->first();

        if ($product !== null) {
            if (count($validate) >= 1) {
                $product->update($validate);
                return response()->json(['message' => 'product changed'], 200);
            } else {
                return response()->json(['message' => 'product not changed'], 304);
            }
        } else {
            return response()->json(['erro' => 'product', 'message' => 'product not found'], 404);
        }
    }

    // Request front For Back
    public function delete(int $id): JsonResponse
    {
        $product = Products::where('id', '=', $id)->first();

        if ($product !== null) {
            $product->delete();
            return response()->json(['message' => 'product deleted'], 200);
        } else {
            return response()->json(['erro' => 'product', 'message' => 'product not found'], 404);
        }
    }
}
