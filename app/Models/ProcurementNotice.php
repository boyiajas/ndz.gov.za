<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementNotice extends Model
{
    use HasFactory;

    public const TYPE_TENDER = 'tender';
    public const TYPE_QUOTE = 'quote';

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'type',
        'status',
        'financial_year',
        'reference_no',
        'title',
        'description',
        'closing_date',
        'briefing_date',
        'contact_person',
        'document_url',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'datetime',
        ];
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeTenders(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_TENDER);
    }

    public function scopeQuotes(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_QUOTE);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
