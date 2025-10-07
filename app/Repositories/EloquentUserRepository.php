<?php

namespace App\Repositories;

use App\Models\User;
use App\Schemas\UserSchema;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function listPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::orderByDesc('id')->paginate($perPage);
    }

    public function listAll(): Collection
    {
        return User::orderByDesc('id')->get();
    }

    public function create(UserSchema $data): User
    {
        $attrs = [
            'name' => $data->name,
            'email' => $data->email,
        ];
        if ($data->password !== null) {
            $attrs['password'] = Hash::make($data->password);
        }
        return User::create($attrs);
    }

    public function update(User $user, UserSchema $data): User
    {
        $user->name = $data->name;
        $user->email = $data->email;
        if ($data->password !== null && $data->password !== '') {
            $user->password = Hash::make($data->password);
        }
        $user->save();
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}


