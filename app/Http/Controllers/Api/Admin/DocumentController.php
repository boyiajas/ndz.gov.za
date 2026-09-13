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
            ->with(['subcategory.category', 'creator:id,name,email', 'updater:id,name,email'])
            ->when($request->integer('document_subcategory_id'), function ($query, int $subcategoryId) {
                $query->where('document_subcategory_id', $subcategoryId);
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

        return response()->json(['data' => $document->fresh(['subcategory.category', 'creator:id,name,email'])], 201);
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $data = $this->validated($request);
        $data['updated_by'] = $request->user()->id;

        $document->update($data);

        return response()->json(['data' => $document->fresh(['subcategory.category', 'creator:id,name,email', 'updater:id,name,email'])]);
    }

    public function destroy(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $document->delete();

        return response()->json(['message' => 'Document deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'document_subcategory_id' => ['required', 'integer', Rule::exists('document_subcategories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_url' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', Rule::in(Document::STATUSES)],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function authorizeManage(Request $request): void
    {
        abort_unless($request->user()?->canManageDocuments(), 403, 'Your role cannot manage documents.');
    }
}
