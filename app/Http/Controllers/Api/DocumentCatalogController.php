<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use App\Models\DocumentSubcategory;
use Illuminate\Http\JsonResponse;

class DocumentCatalogController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = DocumentCategory::query()
            ->where('is_active', true)
            ->with(['subcategories' => function ($query) {
                $query->where('is_active', true)
                    ->withCount(['documents' => fn ($query) => $query->published()])
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function category(DocumentCategory $documentCategory): JsonResponse
    {
        abort_unless($documentCategory->is_active, 404);

        $documentCategory->load(['subcategories' => function ($query) {
            $query->where('is_active', true)
                ->withCount(['documents' => fn ($query) => $query->published()])
                ->orderBy('sort_order')
                ->orderBy('name');
        }]);

        return response()->json(['data' => $documentCategory]);
    }

    public function show(DocumentCategory $documentCategory, string $subcategorySlug): JsonResponse
    {
        abort_unless($documentCategory->is_active, 404);

        $subcategory = DocumentSubcategory::query()
            ->where('document_category_id', $documentCategory->id)
            ->where('slug', $subcategorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $documents = $subcategory->documents()
            ->published()
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return response()->json([
            'category' => $documentCategory,
            'subcategory' => $subcategory,
            'documents' => $documents,
        ]);
    }
}
