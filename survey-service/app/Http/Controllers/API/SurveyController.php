<?php

namespace App\Http\Controllers\API;

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
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        return $this->service->getDetails($id);
    }
}
