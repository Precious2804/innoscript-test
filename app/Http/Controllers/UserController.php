<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use ApiResponse;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(CreateUserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->createUser($data);
        return ApiResponse::successResponseWithData(new UserResource($user), 'New account created successfully', Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        try {
            $processLogin = $this->userService->login($data);
            return ApiResponse::successResponseWithData(new UserResource($processLogin['user']), 'Login Succesful', Response::HTTP_OK, $processLogin['token']);
        } catch (ValidationException $e) {
            return ApiResponse::errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function info()
    {
        $user = $this->userService->findById(Auth::user()->id);
        return ApiResponse::successResponseWithData(new UserResource($user), 'User Details', Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::successResponse('Logout Successful', Response::HTTP_OK);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $this->userService->updatePassword(Auth::user()->id, $data);
        return ApiResponse::successResponse('Password Updated', Response::HTTP_OK);
    }
}
