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

        $user = $request->user();
        $query = Document::query()
            ->with(['category.department:id,name,code', 'subcategory.category', 'department:id,name,code', 'creator:id,name,email', 'updater:id,name,email']);

        $deptIds = $user->departmentIds();

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            $query->forUserDepartment($user);
            if ($request->filled('department_id') && $request->input('department_id') !== 'all') {
                $targetDept = (int) $request->input('department_id');
                if (in_array($targetDept, $deptIds, true)) {
                    $query->where(function ($q) use ($targetDept) {
                        $q->where('documents.department_id', $targetDept)
                          ->orWhereHas('category', fn ($c) => $c->where('department_id', $targetDept));
                    });
                }
            }
        } elseif ($request->filled('department_id') && $request->input('department_id') !== 'all') {
            $deptId = (int) $request->input('department_id');
            $query->where(function ($q) use ($deptId) {
                $q->where('documents.department_id', $deptId)
                  ->orWhereHas('category', fn ($c) => $c->where('department_id', $deptId));
            });
        }

        $documents = $query
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

        $user = $request->user();
        $deptIds = $user->departmentIds();
        $data = $this->validated($request);

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            $category = \App\Models\DocumentCategory::find($data['document_category_id']);
            if ($category && $category->department_id && ! in_array((int) $category->department_id, $deptIds, true)) {
                abort(403, 'You can only upload documents to categories belonging to your assigned department(s).');
            }
            if (empty($data['department_id']) || ! in_array((int) $data['department_id'], $deptIds, true)) {
                $data['department_id'] = $category?->department_id ?: $deptIds[0];
            }
        } elseif (empty($data['department_id']) && ! empty($data['document_category_id'])) {
            $category = \App\Models\DocumentCategory::find($data['document_category_id']);
            $data['department_id'] = $category?->department_id;
        }

        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        $document = Document::create($data);

        return response()->json(['data' => $document->fresh(['category', 'subcategory.category', 'department', 'creator:id,name,email'])], 201);
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $user = $request->user();
        $deptIds = $user->departmentIds();

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            $docDept = $document->department_id ?? $document->category?->department_id;
            if ($docDept && ! in_array((int) $docDept, $deptIds, true)) {
                abort(403, 'You are not authorized to update documents belonging to another department.');
            }
        }

        $data = $this->validated($request);

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            if (empty($data['department_id']) || ! in_array((int) $data['department_id'], $deptIds, true)) {
                $data['department_id'] = $document->department_id ?: $deptIds[0];
            }
        }

        $data['updated_by'] = $user->id;

        $document->update($data);

        return response()->json(['data' => $document->fresh(['category', 'subcategory.category', 'department', 'creator:id,name,email', 'updater:id,name,email'])]);
    }

    public function destroy(Request $request, Document $document): JsonResponse
    {
        $this->authorizeManage($request);

        $user = $request->user();
        $deptIds = $user->departmentIds();

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            $docDept = $document->department_id ?? $document->category?->department_id;
            if ($docDept && ! in_array((int) $docDept, $deptIds, true)) {
                abort(403, 'You are not authorized to delete documents belonging to another department.');
            }
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'document_category_id' => ['nullable', 'integer', Rule::exists('document_categories', 'id')],
            'document_subcategory_id' => ['nullable', 'integer', Rule::exists('document_subcategories', 'id')],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
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
