<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserPreferences;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class UserRepository
{
    use ApiResponse;

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
        abort_if(!$user, ApiResponse::errorResponse("User Not found", Response::HTTP_BAD_REQUEST));
        return $user->update($data);
    }

    public function setPreference($userId, $preference)
    {
        $user = User::findOrFail($userId);
        abort_if(!$user, ApiResponse::errorResponse("User Not found", Response::HTTP_BAD_REQUEST));
        return $user->preferences()->updateOrCreate(
            ['type' => $preference['type'], 'value' => $preference['value']],
            $preference
        );
    }

    public function getPreferences($userId)
    {
        return UserPreferences::where('user_id', $userId)->orderBy('type', 'ASC')->get();
    }
}
