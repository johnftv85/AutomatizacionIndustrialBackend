<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnterpriceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Enterprice::get(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nit' => ['nullable', 'string', 'max:30', 'unique:enterprice,nit'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $enterprice = Enterprice::create($data);

        return response()->json($enterprice->fresh(), 201);
    }

    public function show(Enterprice $enterprice): JsonResponse
    {
        return response()->json($enterprice, 200);
    }

    public function update(Request $request, Enterprice $enterprice): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'nit' => ['sometimes', 'nullable', 'string', 'max:30', 'unique:enterprice,nit,'.$enterprice->id],
            'status' => ['sometimes', 'boolean'],
        ]);

        $enterprice->update($data);

        return response()->json($enterprice, 200);
    }

    public function destroy(Enterprice $enterprice): JsonResponse
    {
        $enterprice->delete();

        return response()->json(['message' => 'Empresa eliminada'], 200);
    }
}
