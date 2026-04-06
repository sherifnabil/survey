<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerGroupedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $sections =
        return [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'option_id' => $this->option_id,
            'response_id' => $this->response_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
