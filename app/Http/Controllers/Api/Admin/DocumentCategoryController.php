<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $categories = DocumentCategory::query()
            ->with(['subcategories' => function ($query) {
                $query->withCount('documents')
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->withCount('subcategories')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        $category = DocumentCategory::create($data);

        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, DocumentCategory $documentCategory): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $documentCategory->id);

        $documentCategory->update($data);

        return response()->json(['data' => $documentCategory->fresh('subcategories')]);
    }

    public function destroy(Request $request, DocumentCategory $documentCategory): JsonResponse
    {
        $this->authorizeManage($request);

        $documentCategory->delete();

        return response()->json(['message' => 'Document category deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'document-category';
        $slug = $baseSlug;
        $counter = 2;

        while (
            DocumentCategory::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function authorizeManage(Request $request): void
    {
        abort_unless($request->user()?->canManageDocuments(), 403, 'Your role cannot manage documents.');
    }
}
