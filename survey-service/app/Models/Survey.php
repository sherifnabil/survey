<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'active', 'description'])]
class Survey extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function scopeFilters($query, array $filters): void
    {
        $query->when(
            !empty($filters['name']),
            fn($q) => $q->where('name', 'like', '%' . $filters['name'] . '%')
        )
            ->when(
                !empty($filters['description']),
                fn($q) => $q->where('description', 'like', '%' . $filters['description'] . '%')
            )
            ->when(
                !is_null($filters['active']),
                fn($q) => $q->where('active', $filters['active'])
            );
    }
}
