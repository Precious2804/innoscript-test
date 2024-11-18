<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByID(string $userId): ?User
    {
        return User::findOrFail($userId);
    }

    public function update($userId, $data)
    {
        $user = User::findOrFail($userId);
        return $user->update($data);
    }
}
