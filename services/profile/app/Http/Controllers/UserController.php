<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        [$user, $token] = $this->userService->register($request->validated());

        return response()->json([
            'data' => [
                'user' => UserResource::make($user)->resolve(),
                'token' => $token
            ],
        ], 201);
    }

    public function auth(AuthUserRequest $request): JsonResponse
    {
        [$user, $token] = $this->userService->auth($request->validated());

        return response()->json([
            'data' => [
                'user' => UserResource::make($user)->resolve(),
                'token' => $token
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->userService->me();

        return response()->json([
            'data' => [
                'user' => UserResource::make($user)->resolve()
            ]
        ]);
    }

    public function logout(): Response
    {
        $this->userService->logout();

        return response()->noContent();
    }
}
