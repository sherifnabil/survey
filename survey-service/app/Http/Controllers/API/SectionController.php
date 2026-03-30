<?php

namespace App\Http\Controllers\API;

use App\DTOs\Section\SectionDTO;
use App\DTOs\Section\SectionFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\SectionRequest;
use App\Services\SectionService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct(protected SectionService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $surveyId)
    {
        $request->merge(['survey_id' => $surveyId]); // Ensure the survey_id is included in the request data
        return $this->service->list((SectionFilterDTO::fromRequest($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request, int $surveyId)
    {
        $data = $request->validated();
        $data['survey_id'] = $surveyId; // Ensure the survey_id is included in the DTO
        $data['order'] = $this->service->getNextOrder($surveyId); // Get the next order value for the new section
        return $this->service->create(SectionDTO::fromRequest($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return $this->service->getDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, int $id)
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        unset($data['survey_id']); // Prevent changing the survey_id through update
        return $this->service->update(SectionDTO::fromRequest($data), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->service->delete($id);
    }
}
