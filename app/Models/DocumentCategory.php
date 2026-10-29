<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subtitle',
        'slug',
        'department_id',
        'description',
        'image_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(DocumentSubcategory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function directDocuments(): HasMany
    {
        return $this->hasMany(Document::class)->whereNull('document_subcategory_id');
    }

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        // Normalize any absolute localhost/127.0.0.1 URLs to root-relative /storage/... path
        if (preg_match('#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?(/storage/.*)$#i', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }

    public function setImageUrlAttribute(?string $value): void
    {
        if ($value && preg_match('#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?(/storage/.*)$#i', $value, $matches)) {
            $this->attributes['image_url'] = $matches[1];
        } else {
            $this->attributes['image_url'] = $value;
        }
    }
}
