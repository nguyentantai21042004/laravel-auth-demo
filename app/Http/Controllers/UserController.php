<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use App\Schemas\UserSchema;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->users->listPaginated());
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->users->findById($id);
        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
        ]);
        $user = $this->users->create(new UserSchema(
            $validated['name'],
            $validated['email'],
            $validated['password'],
        ));
        return response()->json($user, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->users->findById($id);
        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:8'],
        ]);
        $updated = $this->users->update($user, new UserSchema(
            $validated['name'],
            $validated['email'],
            $validated['password'] ?? null,
        ));
        return response()->json($updated);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->users->findById($id);
        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $this->users->delete($user);
        return response()->json(['deleted' => true]);
    }
}


