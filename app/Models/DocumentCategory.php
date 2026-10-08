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

        // If the URL was stored with http://localhost/storage (missing port), fix it
        if (preg_match('#^http://localhost(/storage/.*)$#i', $value, $matches)) {
            return config('app.url') . $matches[1];
        }

        return $value;
    }
}
