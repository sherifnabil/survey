<?php

namespace App\Http\Controllers\API;

use App\DTOs\User\UserDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\UserRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    /**
     * Handle user registration and return the created user along with an authentication token.
     *
     * @param UserRegisterRequest $request The incoming request containing user registration data
     * @return JsonResponse The formatted response containing the registered user and authentication token
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $dto = UserDTO::fromArray($request->validated());
        [$user, $token] = $this->service->register($dto);

        return ResponseFormatter::dataResponse(
            data: collect([
                'user' => new AuthResource($user),
                'token' => $token,
            ])
        );
    }

    /**
     * Handle user login and return user data along with an authentication token
     * @param LoginRequest $request The incoming request containing user login data
     * @return JsonResponse The formatted response containing the logged user and authentication token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = UserDTO::fromArray($request->validated());
        [$user, $token] = $this->service->login($dto);

        return ResponseFormatter::dataResponse(
            data: collect([
                'user' => new AuthResource($user),
                'token' => $token,
            ])
        );
    }

    /**
     * Logout the current auth user and revoke all their tokens
     * @param Request $request The incoming request containing auth user
     * @return JsonResponse The formatted response 
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->service->logout($user);
        return ResponseFormatter::dataResponse(data: null);
    }

    /**
     * Getting the request's logged user data and return them transformed
     * @param Request $request The incoming request containing auth user
     * @return JsonResponse The formatted response 
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->service->loggedUser($user);
        return ResponseFormatter::dataResponse(data: new AuthResource($user));
    }
}
