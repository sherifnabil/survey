<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'order', 'survey_id'])]
class Section extends Model
{
    use HasFactory;

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        $surveyId = $filters['survey_id'] ?? null;
        $name = $filters['name'] ?? null;
        $order = $filters['order'] ?? null;

        $query->when($surveyId, function ($query, $surveyId) {
            $query->where('survey_id', $surveyId);
        })
            ->when($name ?? null, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($order ?? null, function ($query, $order) {
                $query->where('order', $order);
            });
    }
}
