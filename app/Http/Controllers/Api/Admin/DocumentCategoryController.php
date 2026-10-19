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

        $user = $request->user();
        $deptIds = $user->departmentIds();

        $query = DocumentCategory::query()
            ->with(['department:id,name,code', 'subcategories' => function ($query) {
                $query->withCount('documents')
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->withCount(['subcategories', 'documents', 'directDocuments']);

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            $query->whereIn('department_id', $deptIds);
            if ($request->filled('department_id') && $request->input('department_id') !== 'all') {
                $targetDept = (int) $request->input('department_id');
                if (in_array($targetDept, $deptIds, true)) {
                    $query->where('department_id', $targetDept);
                }
            }
        } elseif ($request->filled('department_id') && $request->input('department_id') !== 'all') {
            $query->where('department_id', (int) $request->input('department_id'));
        }

        $categories = $query->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeManage($request);

        $user = $request->user();
        $deptIds = $user->departmentIds();
        $data = $this->validated($request);

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            if (empty($data['department_id']) || ! in_array((int) $data['department_id'], $deptIds, true)) {
                $data['department_id'] = $deptIds[0];
            }
        }

        $data['slug'] = $this->uniqueSlug($data['name']);

        $category = DocumentCategory::create($data);

        return response()->json(['data' => $category->fresh(['department:id,name,code'])], 201);
    }

    public function update(Request $request, DocumentCategory $documentCategory): JsonResponse
    {
        $this->authorizeManage($request);

        $user = $request->user();
        $deptIds = $user->departmentIds();

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            if ($documentCategory->department_id && ! in_array((int) $documentCategory->department_id, $deptIds, true)) {
                abort(403, 'You are not authorized to edit categories belonging to another department.');
            }
        }

        $data = $this->validated($request);

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            if (empty($data['department_id']) || ! in_array((int) $data['department_id'], $deptIds, true)) {
                $data['department_id'] = $documentCategory->department_id ?: $deptIds[0];
            }
        }

        $data['slug'] = $this->uniqueSlug($data['name'], $documentCategory->id);

        $documentCategory->update($data);

        return response()->json(['data' => $documentCategory->fresh(['subcategories', 'department:id,name,code'])]);
    }

    public function destroy(Request $request, DocumentCategory $documentCategory): JsonResponse
    {
        $this->authorizeManage($request);

        $user = $request->user();
        $deptIds = $user->departmentIds();

        if (! $user->isGlobalAdmin() && ! empty($deptIds)) {
            if ($documentCategory->department_id && ! in_array((int) $documentCategory->department_id, $deptIds, true)) {
                abort(403, 'You are not authorized to delete categories belonging to another department.');
            }
        }

        $documentCategory->delete();

        return response()->json(['message' => 'Document category deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
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
