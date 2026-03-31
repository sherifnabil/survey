<?php

namespace App\Http\Controllers\API;

use App\DTOs\Option\OptionDTO;
use App\DTOs\Option\OptionFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OptionRequest;
use App\Http\Resources\OptionResource;
use App\Services\OptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct(protected OptionService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $surveyId): JsonResponse
    {
        $request->merge(['survey_id' => $surveyId]); // Ensure the survey_id is included in the request data
        $action = $this->service->list((OptionFilterDTO::fromRequest($request->all())));
        return ResponseFormatter::paginationResponse(OptionResource::collection($action['data']), $action['paginator']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OptionRequest $request, int $surveyId): JsonResponse
    {
        $data = $request->validated();
        $data['survey_id'] = $surveyId; // Ensure the survey_id is included in the DTO
        $action = $this->service->create(OptionDTO::fromArray($data));
        return ResponseFormatter::dataResponse(new OptionResource($action));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $option = $this->service->getDetails($id);
        return ResponseFormatter::dataResponse(new OptionResource($option));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OptionRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        unset($data['survey_id']); // Prevent changing the survey_id through update
        $action = $this->service->update(OptionDTO::fromArray($data), $id);
        return ResponseFormatter::dataResponse(new OptionResource($action));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return ResponseFormatter::dataResponse(data: null);
    }

    public function types(): JsonResponse
    {
        $types = $this->service->getTypes();
        return ResponseFormatter::dataResponse($types);
    }
}
