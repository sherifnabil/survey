<?php

namespace App\Services;

use App\Actions\Response\CreateAction;
use App\Actions\Response\ListAction;
use App\Actions\Response\UpdateAction;
use App\DTOs\Response\ResponseDTO;
use App\DTOs\Response\ResponseFilterDTO;
use App\Models\Response;
use App\Models\Survey;
use Illuminate\Support\Collection;

class ResponseService
{
  public function __construct(private SurveyService $surveyService) {}

  /**
   * Get a paginated list of responses with optional filters and selected columns
   * @param ResponseFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return array An array containing the paginated list of responses and total count
   */
  public function list(ResponseFilterDTO $dto): array
  {
    return (new ListAction())->execute($dto);
  }

  /**
   * Get detailed information about a specific response, including its sections and questions
   * @param int $id The ID of the response to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the response with the specified ID is not found
   * @return Collection A Collection containing the detailed information about the response
   */
  public function getDetails(int $id): Collection
  {
    $responseData = Response::with(['survey.sections.questions', 'survey.options', 'answers.question:id,section_id', 'user:id,name,email'])->findOrFail($id);
    $answersGroupedBySection = $responseData->answers->groupBy('question.section_id') ?? collect([]);

    return $this->structureResponseDetails($responseData, $responseData->survey, $answersGroupedBySection);
  }

  public function createResponse(int $id): Collection
  {
    $survey = $this->surveyService->getDetails($id);
    return $this->structureResponseDetails(response: null, survey: $survey, answers: []);
  }

  /**
   * Create a new response along with its sections and options
   * @param ResponseDTO $dto The data transfer object
   * @return Response
   */
  public function create(ResponseDTO $dto): Response
  {
    return (new CreateAction())->execute($dto);
  }

  /**
   * Update an existing response along with its sections and options
   * @param ResponseDTO $dto The data transfer object containing the updated response information
   * @return Response
   */
  public function update(ResponseDTO $dto): Response
  {
    return (new UpdateAction())->execute($dto);
  }

  /**
   * Delete a response by its ID
   * @param int $id The ID of the response to delete
   * @return bool
   */
  public function deleteById(int $id): bool
  {
    $response = Response::findOrFail($id);
    return $response->delete();
  }

  /**
   * Structure the response details into a collection.
   *
   * @param Response|null $response The response to structure
   * @param Survey $survey The survey to structure
   * @param array $answers The answers to structure
   * @return Collection The structured response details
   */
  private function structureResponseDetails(Response|null $response, Survey $survey, $answers): Collection
  {
    $survey['sections'] = $this->formatSections($survey->sections, $answers);
    return collect([
      ...($response?->only(['id', 'user_id', 'survey_id', 'created_at']) ?? []),
      'user' => $response->user ?? null,
      'survey' => $survey->only(['id', 'name', 'description', 'active']),
      'sections' => $survey->sections,
    ]);
  }

  /**
   * Format the sections and questions for the response details.
   * @param $sections
   * @param $answers
   * @return Collection
   */
  private function formatSections($sections, $answers): Collection
  {
    return $sections->map(function ($section) use ($answers) {
      $sectionAnswers = !empty($answers[$section->id]) ? $answers[$section->id]->keyBy('question_id') : collect([]);
      return [
        'id'          => $section->id,
        'name'        => $section->name,
        'section_id'  => $section->id,
        'order'       => $section->order,
        'survey_id'   => $section->survey_id,
        'questions'   => $this->formatQuestions($section->questions, $sectionAnswers)
      ];
    });
  }

  /**
   * Format the questions for the response details.
   * @param $questions
   * @param $sectionAnswers
   * @return Collection
   */
  private function formatQuestions($questions, $sectionAnswers): Collection
  {
    return $questions->map(function ($question) use ($sectionAnswers) {
      $sectionAnsweredQuestions = $sectionAnswers->keyBy('question_id');
      $answeredQuestion = !empty($sectionAnsweredQuestions[$question->id]) ? $sectionAnsweredQuestions[$question->id] : null;
      return [
        'id'          => $answeredQuestion?->id,
        'title'       => $question->title,
        'order'       => $question->order,
        'option_id'   => $answeredQuestion?->option_id,   // null if no answer
        'response_id' => $answeredQuestion?->response_id, // null if no answer
        'question_id' => $question->id
      ];
    });
  }
}
