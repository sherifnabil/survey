<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['user_id', 'survey_id'])]
class Response extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /** 
     * Apply filters to the query.
     * 
     * @param Builder $query
     * @param array $filters
     */
    public function scopeFilters(Builder $query, array $filters): void
    {
        $query->when($filters['user_id'] ?? null, function ($query) use ($filters) {
            $query->where('user_id', $filters['user_id']);
        })
            ->when($filters['survey_id'] ?? null, function ($query) use ($filters) {
                $query->where('survey_id', $filters['survey_id']);
            })
            ->when($filters['from'] ?? null, function ($query) use ($filters) {
                $query->where('created_at', '>=', $filters['from']);
            })
            ->when($filters['to'] ?? null, function ($query) use ($filters) {
                $query->where('created_at', '<=', $filters['to']);
            });
    }
}
