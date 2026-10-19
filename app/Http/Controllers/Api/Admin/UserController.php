<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeUsers($request);

        $query = User::query()
            ->with(['department:id,name,code,slug', 'departments:id,name,code,slug'])
            ->orderByDesc('created_at');

        if ($request->filled('role')) {
            $query->where('role', $request->query('role'));
        }

        if ($request->filled('department_id') && $request->query('department_id') !== 'all') {
            $deptId = (int) $request->query('department_id');
            $query->where(function ($q) use ($deptId) {
                $q->where('department_id', $deptId)
                  ->orWhereHas('departments', fn ($d) => $d->where('departments.id', $deptId));
            });
        }

        if ($request->filled('search')) {
            $term = '%' . $request->query('search') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeUsers($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(User::ROLES)],
            'department_ids' => ['nullable', 'array'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'password' => ['required', 'string', Password::defaults()],
        ]);

        $departmentIds = [];
        if (isset($validated['department_ids'])) {
            $departmentIds = array_values(array_unique(array_filter(array_map('intval', $validated['department_ids']))));
        } elseif (! empty($validated['department_id'])) {
            $departmentIds = [(int) $validated['department_id']];
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'department_id' => $departmentIds[0] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->departments()->sync($departmentIds);

        return response()->json([
            'data' => $user->fresh(['department:id,name,code,slug', 'departments:id,name,code,slug']),
        ], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorizeUsers($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(User::ROLES)],
            'department_ids' => ['nullable', 'array'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'password' => ['nullable', 'string', Password::defaults()],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if (array_key_exists('department_ids', $validated)) {
            $departmentIds = array_values(array_unique(array_filter(array_map('intval', $validated['department_ids'] ?? []))));
            $data['department_id'] = $departmentIds[0] ?? null;
            $user->update($data);
            $user->departments()->sync($departmentIds);
        } elseif (array_key_exists('department_id', $validated)) {
            $data['department_id'] = $validated['department_id'];
            $user->update($data);
            if ($validated['department_id']) {
                $user->departments()->sync([(int) $validated['department_id']]);
            } else {
                $user->departments()->detach();
            }
        } else {
            $user->update($data);
        }

        return response()->json([
            'data' => $user->fresh(['department:id,name,code,slug', 'departments:id,name,code,slug']),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorizeUsers($request);

        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    public function roles(Request $request): JsonResponse
    {
        $this->authorizeUsers($request);

        $roles = [
            [
                'id' => User::ROLE_ADMIN,
                'name' => 'Administrator',
                'description' => 'Unrestricted access to all municipal management modules, users, security roles, system configuration, and audit logs.',
                'permissions' => [
                    'Manage Documents & Categories' => true,
                    'Publish News & Blog Articles' => true,
                    'Manage Event Gallery' => true,
                    'Manage Tenders & Procurement' => true,
                    'Manage Users & Assign Roles' => true,
                    'Edit Portal Site Settings' => true,
                    'Direct File Uploads' => true,
                ],
            ],
            [
                'id' => User::ROLE_MANAGER,
                'name' => 'Municipal Manager',
                'description' => 'Senior municipal leadership access to oversee and update official documents, news announcements, gallery media, and tenders.',
                'permissions' => [
                    'Manage Documents & Categories' => true,
                    'Publish News & Blog Articles' => true,
                    'Manage Event Gallery' => true,
                    'Manage Tenders & Procurement' => true,
                    'Manage Users & Assign Roles' => false,
                    'Edit Portal Site Settings' => false,
                    'Direct File Uploads' => true,
                ],
            ],
            [
                'id' => User::ROLE_EDITOR,
                'name' => 'Content Editor',
                'description' => 'Communications and content staff authorized to draft, upload, and publish news, public documents, and event gallery pictures.',
                'permissions' => [
                    'Manage Documents & Categories' => true,
                    'Publish News & Blog Articles' => true,
                    'Manage Event Gallery' => true,
                    'Manage Tenders & Procurement' => false,
                    'Manage Users & Assign Roles' => false,
                    'Edit Portal Site Settings' => false,
                    'Direct File Uploads' => true,
                ],
            ],
            [
                'id' => User::ROLE_CITIZEN,
                'name' => 'Citizen User',
                'description' => 'Standard verified public user. Read-only access to portal resources, citizen forms, and tender notices.',
                'permissions' => [
                    'Manage Documents & Categories' => false,
                    'Publish News & Blog Articles' => false,
                    'Manage Event Gallery' => false,
                    'Manage Tenders & Procurement' => false,
                    'Manage Users & Assign Roles' => false,
                    'Edit Portal Site Settings' => false,
                    'Direct File Uploads' => false,
                ],
            ],
        ];

        return response()->json([
            'data' => $roles,
            'departments' => \App\Models\Department::active()->get(['id', 'name', 'code', 'slug']),
        ]);
    }

    private function authorizeUsers(Request $request): void
    {
        abort_unless($request->user()?->canManageUsers(), 403, 'Only administrators can manage users and security roles.');
    }
}
