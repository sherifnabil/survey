<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->when($this->description, fn() => $this->description),
            'created_at' => $this->when(!is_null($this->created_at), fn() => $this->created_at->format('Y-m-d H:i:s')),
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
            'options' => OptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
