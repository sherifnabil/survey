<?php

namespace App\Http\Controllers\API;

use App\DTOs\Section\SectionDTO;
use App\DTOs\Section\SectionFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SectionRequest;
use App\Http\Resources\SectionResource;
use App\Services\SectionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SectionController extends Controller
{
    public function __construct(protected SectionService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $surveyId): JsonResponse
    {
        $request->merge(['survey_id' => $surveyId]); // Ensure the survey_id is included in the request data
        $action = $this->service->list((SectionFilterDTO::fromRequest($request->all())));
        return ResponseFormatter::paginationResponse(SectionResource::collection($action['data']), $action['paginator']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request, int $surveyId): JsonResponse
    {
        $data = $request->validated();
        $data['survey_id'] = $surveyId; // Ensure the survey_id is included in the DTO
        $data['order'] = $this->service->getNextOrder($surveyId); // Get the next order value for the new section
        $action = $this->service->create(SectionDTO::fromArray($data));
        return ResponseFormatter::dataResponse(new SectionResource($action));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $section = $this->service->getDetails($id);
        return ResponseFormatter::dataResponse(new SectionResource($section));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        unset($data['survey_id']); // Prevent changing the survey_id through update
        unset($data['order']);
        $section = $this->service->update(SectionDTO::fromArray($data), $id);
        return ResponseFormatter::dataResponse(new SectionResource($section));
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
