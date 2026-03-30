<?php

namespace App\Http\Controllers\API;

use App\DTOs\Option\OptionDTO;
use App\DTOs\Option\OptionFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OptionRequest;
use App\Services\OptionService;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct(protected OptionService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $surveyId)
    {
        $request->merge(['survey_id' => $surveyId]); // Ensure the survey_id is included in the request data
        return $this->service->list((OptionFilterDTO::fromRequest($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OptionRequest $request, int $surveyId)
    {
        $data = $request->validated();
        $data['survey_id'] = $surveyId; // Ensure the survey_id is included in the DTO
        return $this->service->create(OptionDTO::fromRequest($data));
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
    public function update(OptionRequest $request, int $id)
    {
        $data = $request->validated();
        $data['id'] = $id; // Ensure the ID is included in the DTO
        unset($data['survey_id']); // Prevent changing the survey_id through update
        return $this->service->update(OptionDTO::fromRequest($data), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->service->delete($id);
    }

    public function types()
    {
        return $this->service->getTypes();
    }
}
