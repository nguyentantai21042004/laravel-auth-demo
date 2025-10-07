<?php

namespace App\Repositories;

use App\Models\User;
use App\Schemas\UserSchema;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function listPaginated(int $perPage = 15): LengthAwarePaginator;

    public function listAll(): Collection;

    public function create(UserSchema $data): User;

    public function update(User $user, UserSchema $data): User;

    public function delete(User $user): void;
}


