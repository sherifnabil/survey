<?php

namespace App\Http\Controllers\API;

use App\DTOs\Survey\SurveyDTO;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Services\SurveyService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SurveyController extends Controller
{
    public function __construct(protected SurveyService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $action = $this->service->list(SurveyFilterDTO::fromArray($request->all()));
        return ResponseFormatter::cursorPaginationResponse(
            SurveyResource::collection($action['data']),
            $action['data'],
            $action['total'],
        );
    }

    /**
     * Get a minimal list of surveys (id and name only) for select options
     */
    public function minimalList(Request $request): JsonResponse
    {
        $request->merge(['active' => true]); // Only return active surveys);
        $action = $this->service->minimalList(SurveyFilterDTO::fromArray($request->all(), columns: ['id', 'name']));
        return ResponseFormatter::cursorPaginationResponse(
            $action['data']->items(),
            $action['data'],
            $action['total']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurveyRequest $request): JsonResponse
    {
        $dto = SurveyDTO::fromArray($request->validated());
        $survey = $this->service->create($dto);
        return ResponseFormatter::dataResponse(new SurveyResource($survey));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $survey = $this->service->getDetails($id);
        return ResponseFormatter::dataResponse(new SurveyResource($survey));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        $dto = SurveyDTO::fromArray($data);
        $survey = $this->service->update($dto, $id);
        return ResponseFormatter::dataResponse(new SurveyResource($survey));
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
