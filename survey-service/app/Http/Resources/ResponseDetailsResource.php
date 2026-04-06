<?php

namespace App\Http\Resources;

use App\Http\Resources\AnswerGroupedResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'survey' => new SurveyResource($this->whenLoaded('survey')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'answers' => AnswerGroupedResource::collection($this->whenLoaded('answers'))
        ];
    }
}
