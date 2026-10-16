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

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'enterprice_id' => ['sometimes', 'nullable', 'integer', 'exists:enterprice,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'required', 'string', Password::min(8)],
        ]);

        $user->update($data);

        return response()->json($user, 200);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }
}
