<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $documents = Document::query()
            ->with(['category', 'subcategory.category', 'creator:id,name,email', 'updater:id,name,email'])
            ->when($request->integer('document_category_id'), function ($query, int $categoryId) {
                $query->where('document_category_id', $categoryId);
            })
            ->when($request->filled('document_subcategory_id'), function ($query) use ($request) {
                $val = $request->input('document_subcategory_id');
                if ($val === 'direct' || $val === 'null' || $val === '0') {
                    $query->whereNull('document_subcategory_id');
                } else {
                    $query->where('document_subcategory_id', (int) $val);
                }
            })
            ->when($request->boolean('direct_only'), function ($query) {
                $query->whereNull('document_subcategory_id');
            })
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return response()->json(['data' => $documents]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $document = Document::create($data);

        return response()->json(['data' => $document->fresh(['category', 'subcategory.category', 'creator:id,name,email'])], 201);
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['updated_by'] = $request->user()->id;

        $document->update($data);

        return response()->json(['data' => $document->fresh(['category', 'subcategory.category', 'creator:id,name,email', 'updater:id,name,email'])]);
    }

    public function destroy(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $document->delete();

        return response()->json(['message' => 'Document deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'document_category_id' => ['nullable', 'integer', Rule::exists('document_categories', 'id')],
            'document_subcategory_id' => ['nullable', 'integer', Rule::exists('document_subcategories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_url' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', Rule::in(Document::STATUSES)],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (empty($data['document_category_id']) && empty($data['document_subcategory_id'])) {
            abort(422, 'Please select a parent document name or sub name.');
        }

        if (!empty($data['document_subcategory_id'])) {
            $subcategory = \App\Models\DocumentSubcategory::find($data['document_subcategory_id']);
            if ($subcategory) {
                $data['document_category_id'] = $subcategory->document_category_id;
            }
        }

        return $data;
    }

    private function authorizeManage(Request $request): void
    {
        abort_unless($request->user()?->canManageDocuments(), 403, 'Your role cannot manage documents.');
    }
}
