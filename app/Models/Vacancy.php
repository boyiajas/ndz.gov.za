<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_CLOSED,
    ];

    protected $fillable = [
        'title',
        'reference_no',
        'department',
        'status',
        'closing_date',
        'remuneration',
        'location',
        'description',
        'requirements',
        'document_url',
        'document_name',
        'application_url',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeDepartment(Builder $query, ?string $department): Builder
    {
        if ($department && $department !== 'all') {
            return $query->where('department', $department);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        $term = '%' . trim($term) . '%';

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', $term)
              ->orWhere('reference_no', 'like', $term)
              ->orWhere('department', 'like', $term)
              ->orWhere('description', 'like', $term);
        });
    }
}
