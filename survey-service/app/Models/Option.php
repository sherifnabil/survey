<?php

namespace App\Models;

use App\Enums\OptionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'type', 'value', 'survey_id'])]
class Option extends Model
{
    use HasFactory;

    protected function casts()
    {
        return [
            'type' => OptionType::class,
            'value' => 'integer',
        ];
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopeFilters($query, array $filters): void
    {
        $surveyId = $filters['survey_id'] ?? null;
        $name = $filters['name'] ?? null;
        $type = $filters['type'] ?? null;

        $query->when($surveyId, function ($query, $surveyId) {
            $query->where('survey_id', $surveyId);
        })
            ->when($name ?? null, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($type ?? null, function ($query, $type) {
                $query->where('type', $type);
            });
    }
}
