<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']); // Hash the password before saving
        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid Login Credentials'],
            ]);
        }

        // Generate a Sanctum token for the user
        $token = $user->createToken('API Token')->plainTextToken;
        return ['token' => $token, 'user' => $user];
    }

    public function updatePassword($userId, $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->update($userId, $data);
    }

    public function findByID($userId)
    {
        return $this->userRepository->findByID($userId);
    }
}
