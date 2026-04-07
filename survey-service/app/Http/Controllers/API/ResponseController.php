<?php

namespace App\Http\Controllers\API;

use App\DTOs\Response\ResponseDTO;
use App\DTOs\Response\ResponseFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ResponseRequest;
use App\Http\Resources\ResponseResource;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResponseController extends Controller
{
    public function __construct(protected ResponseService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $action = $this->service->list(ResponseFilterDTO::fromRequest($request->all()));
        return ResponseFormatter::cursorPaginationResponse(
            ResponseResource::collection($action['data']),
            $action['data'],
            $action['total'],
        );
    }

    /**
     * Create a blank response for a specific survey
     * @param int $surveyId
     * @return JsonResponse
     */
    public function blankResponseBySurvey(int $surveyId): JsonResponse
    {
        $resource = $this->service->createResponse($surveyId);
        return ResponseFormatter::dataResponse($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResponseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $dto = ResponseDTO::fromArray($data);
        $resource = $this->service->create($dto);
        return ResponseFormatter::dataResponse(new ResponseResource($resource));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $resource = $this->service->getDetails($id);
        return ResponseFormatter::dataResponse($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResponseRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        $dto = ResponseDTO::fromArray($data);
        $resource = $this->service->update($dto);
        return ResponseFormatter::dataResponse(new ResponseResource($resource));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->deleteById($id);
        return ResponseFormatter::dataResponse(data: null);
    }
}
