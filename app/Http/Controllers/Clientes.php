<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Clientes extends Controller
{
    public function view_list(Request $request): View
    {
        return view('Client\List', json_decode($this->get_list($request)->getContent(), true));
    }

    public function get_list(Request $request): JsonResponse
    {
        $validate = $this->validate($request, ['per_page' => 'integer', 'page' => 'integer']);
        $clients = Clients::paginate(page: $validate['page'] ?? 1, perPage: $validate['per_page'] ?? 10);
        return response()->json(compact("clients"));
    }

    public function create(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            'name' => 'string|required',
            'email' => 'string|unique:clients|required',
            'type_document' => 'in:cpf, cnpj, rg',
            'document' => 'string|max:18|unique:clients|required',
        ]);

        Clients::create($validate);
        return response()->json(['message' => 'client created'], 201);
    }

    public function client(int $id): JsonResponse
    {
        $client = Clients::where('id', '=', $id)->first();

        if ($client !== null) {
            return response()->json(compact("client"));
        } else {
            return response()->json(['erro' => 'client', 'message' => 'client not found'], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = $this->validate($request, [
            'name' => 'string',
            'email' => 'string|unique:clients',
            'type_document' => 'in:cpf, cnpj, rg',
            'document' => 'string|unique:clients',
        ]);

        $client = Clients::where('id', '=', $id)->first();

        if ($client !== null) {
            if (count($validate) >= 1) {
                $client->update($validate);
                return response()->json(['message' => 'client changed'], 200);
            } else {
                return response()->json(['message' => 'client not changed'], 304);
            }
        } else {
            return response()->json(['erro' => 'client', 'message' => 'client not found'], 404);
        }
    }

    // Request front For Back
    public function delete(int $id): JsonResponse
    {
        $client = Clients::where('id', '=', $id)->first();

        if ($client !== null) {
            $client->delete();
            return response()->json(['message' => 'client deleted'], 200);
        } else {
            return response()->json(['erro' => 'client', 'message' => 'client not found'], 404);
        }
    }
}
