<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::get(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'enterprice_id' => ['nullable', 'integer', 'exists:enterprice,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
        ]);

        $user = User::create($data);

        return response()->json($user->fresh(), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user, 200);
    }

    public function update(Request $request, int $user): JsonResponse
    {
        $request->validate([
            'enterprice_id' => ['required', 'integer', 'exists:enterprice,id'],
        ]);

        // Primero se valida que el usuario exista dentro de la empresa indicada
        $model = User::where('enterprice_id', $request->integer('enterprice_id'))->find($user);

        if (! $model) {
            return response()->json(['message' => 'El usuario no existe en la empresa'], 404);
        }

        // La empresa solo identifica al usuario: no se puede usar para moverlo a otra
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,'.$model->id],
            'password' => ['sometimes', 'required', 'string', Password::min(8)],
        ]);

        $model->update($data);

        return response()->json($model, 200);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }
}
