<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentSubcategoryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['document_category_id'], $data['name']);

        $subcategory = DocumentSubcategory::create($data);

        return response()->json(['data' => $subcategory], 201);
    }

    public function update(Request $request, DocumentSubcategory $documentSubcategory): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug(
            (int) $data['document_category_id'],
            $data['name'],
            $documentSubcategory->id,
        );

        $documentSubcategory->update($data);

        return response()->json(['data' => $documentSubcategory->fresh('category')]);
    }

    public function destroy(Request $request, DocumentSubcategory $documentSubcategory): JsonResponse
    {
        $this->authorizeManage($request);

        $documentSubcategory->delete();

        return response()->json(['message' => 'Document subcategory deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'document_category_id' => ['required', 'integer', Rule::exists('document_categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }

    private function uniqueSlug(int $categoryId, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'document-section';
        $slug = $baseSlug;
        $counter = 2;

        while (
            DocumentSubcategory::query()
                ->where('document_category_id', $categoryId)
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
