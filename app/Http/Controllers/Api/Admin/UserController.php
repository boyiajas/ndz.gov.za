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

        $query = User::query()->orderByDesc('created_at');

        if ($request->filled('role')) {
            $query->where('role', $request->query('role'));
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
            'password' => ['required', 'string', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return response()->json(['data' => $user], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorizeUsers($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(User::ROLES)],
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

        $user->update($data);

        return response()->json(['data' => $user->fresh()]);
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

        return response()->json(['data' => $roles]);
    }

    private function authorizeUsers(Request $request): void
    {
        abort_unless($request->user()?->canManageUsers(), 403, 'Only administrators can manage users and security roles.');
    }
}
