<?php

namespace App\Http\Controllers\API;

use App\DTOs\Survey\SurveyDTO;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SurveyRequest;
use App\Services\SurveyService;
use Illuminate\Http\JsonResponse;
use Ramsey\Collection\Collection;
use Symfony\Component\HttpFoundation\Request;

class SurveyController extends Controller
{
    public function __construct(protected SurveyService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Collection|array
    {
        return $this->service->list(SurveyFilterDTO::fromRequest($request->all()));
    }

    /**
     * Get a minimal list of surveys (id and name only) for select options
     */
    public function minimalList(Request $request): Collection|array
    {
        $request->merge(['active' => true]); // Only return active surveys);
        return $this->service->minimalList(SurveyFilterDTO::fromRequest($request->all(), columns: ['id', 'name']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurveyRequest $request): JsonResponse
    {
        $dto = SurveyDTO::fromRequest($request->validated());
        return $this->service->create($dto);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        return $this->service->getDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        $dto = SurveyDTO::fromRequest($data);
        return $this->service->update($dto, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->service->delete($id);
    }
}
