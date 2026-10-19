<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Database\Seeders\DepartmentSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $query = Department::query()
            ->withCount(['categories', 'documents', 'users'])
            ->with(['users:id,name,email,role']);

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('head_title', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->has('is_active') && $request->input('is_active') !== 'all') {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $departments = $query->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $departments,
            'summary' => [
                'total_departments' => Department::count(),
                'active_departments' => Department::where('is_active', true)->count(),
                'total_categories_mapped' => \App\Models\DocumentCategory::whereNotNull('department_id')->count(),
                'total_documents_mapped' => \App\Models\Document::whereNotNull('department_id')->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $this->validated($request);

        if (empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['name']);
        }

        $data['code'] = strtoupper(trim($data['code']));

        $department = Department::create($data);

        return response()->json([
            'message' => 'Department created successfully.',
            'data' => $department->fresh()->loadCount(['categories', 'documents', 'users']),
        ], 201);
    }

    public function show(Request $request, Department $department): JsonResponse
    {
        $this->authorizeAdmin($request);

        $department->load(['users:id,name,email,role', 'categories:id,name,slug'])
            ->loadCount(['categories', 'documents', 'users']);

        return response()->json(['data' => $department]);
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $this->authorizeAdmin($request);

        $data = $this->validated($request, $department->id);

        if (empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['name'], $department->id);
        }

        $data['code'] = strtoupper(trim($data['code']));

        $department->update($data);

        return response()->json([
            'message' => 'Department updated successfully.',
            'data' => $department->fresh()->loadCount(['categories', 'documents', 'users']),
        ]);
    }

    public function destroy(Request $request, Department $department): JsonResponse
    {
        $this->authorizeAdmin($request);

        $categoryCount = $department->categories()->count();
        $docCount = $department->documents()->count();

        if ($categoryCount > 0 || $docCount > 0) {
            return response()->json([
                'message' => "Cannot delete department '{$department->name}' because it currently has {$categoryCount} category(s) and {$docCount} document file(s) assigned to it. Please reassign or remove them first.",
            ], 422);
        }

        $department->users()->detach();
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully.']);
    }

    public function seedDefaults(Request $request): JsonResponse
    {
        $this->authorizeAdmin($request);

        (new DepartmentSeeder())->run();

        $departments = Department::query()
            ->withCount(['categories', 'documents', 'users'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Default municipal directorates verified and mapped successfully.',
            'data' => $departments,
        ]);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($ignoreId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('departments', 'slug')->ignore($ignoreId)],
            'email' => ['nullable', 'email', 'max:255'],
            'head_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'department';
        $slug = $baseSlug;
        $counter = 2;

        while (
            Department::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();
        abort_unless(
            $user && ($user->isAdmin() || $user->role === \App\Models\User::ROLE_MANAGER),
            403,
            'Only administrators and municipal leadership can access department management.'
        );
    }
}
