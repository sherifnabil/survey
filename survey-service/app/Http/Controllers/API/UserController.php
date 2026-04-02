<?php

namespace App\Http\Controllers\API;

use App\DTOs\User\UserDTO;
use App\DTOs\User\UserFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $action = $this->service->list(UserFilterDTO::fromRequest($request->all()));
        return ResponseFormatter::paginationResponse(
            data: UserResource::collection($action['data']),
            paginator: $action['paginator']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        $dto = UserDTO::fromArray($request->validated());
        $user = $this->service->create($dto);
        return ResponseFormatter::dataResponse(new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->service->getDetails($id);
        return ResponseFormatter::dataResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        $dto = UserDTO::fromArray($data);
        $user = $this->service->update($dto, $id);
        return ResponseFormatter::dataResponse(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return ResponseFormatter::dataResponse(data: null);
    }
}
