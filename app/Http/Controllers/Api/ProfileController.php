<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\ProcurementNotice;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile details.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'data' => $this->formatProfileData($user),
        ]);
    }

    /**
     * Update the authenticated user's personal details.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'job_title' => ['nullable', 'string', 'max:150'],
        ]);

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'job_title' => $user->job_title,
        ];

        $user->update($validated);

        AuditService::log(
            action: 'update',
            module: 'Users',
            description: "User '{$user->name}' updated their personal profile details",
            user: $user,
            auditable: $user,
            oldValues: $oldValues,
            newValues: $validated
        );

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => $this->formatProfileData($user->fresh()),
        ]);
    }

    /**
     * Change the authenticated user's password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditService::log(
            action: 'update',
            module: 'Auth',
            description: "User '{$user->name}' changed their account password",
            user: $user,
            auditable: $user
        );

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Get another user's profile details (for admins/managers).
     */
    public function viewUser(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();
        abort_unless(
            $currentUser->canManageUsers() || $currentUser->isGlobalAdmin() || $currentUser->id === $user->id,
            403,
            'Unauthorized to inspect other user profiles.'
        );

        return response()->json([
            'data' => $this->formatProfileData($user),
        ]);
    }

    /**
     * Helper to format user profile data with relations, permissions and stats.
     */
    private function formatProfileData(User $user): array
    {
        $user->load([
            'department:id,name,code,slug,head_title,email',
            'departments:id,name,code,slug',
        ]);

        $permissions = [
            'Manage Documents & Categories' => $user->canManageDocuments(),
            'Publish News & Blogs' => $user->canManageContent(),
            'Manage Event Gallery' => $user->canManageContent(),
            'Manage Tenders & Quotations' => $user->canManageProcurement(),
            'Manage Vacancies & Careers' => $user->canManageVacancies(),
            'Manage Users & Roles' => $user->canManageUsers(),
            'Manage Directorates & Departments' => in_array($user->role, [User::ROLE_ADMIN, User::ROLE_MANAGER], true),
            'View Audit Trail & Activity' => $user->canViewAuditLogs(),
            'Manage System Settings' => $user->canManageSettings(),
        ];

        $recentActivity = AuditLog::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(15)
            ->get();

        $stats = [
            'documents_count' => Document::where('created_by', $user->id)->count(),
            'procurement_count' => ProcurementNotice::where('created_by', $user->id)->count(),
            'total_actions_logged' => AuditLog::where('user_id', $user->id)->count(),
            'last_login_at' => AuditLog::where('user_id', $user->id)->where('action', 'login')->latest()->value('created_at'),
        ];

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'job_title' => $user->job_title,
            'department_id' => $user->department_id,
            'department' => $user->department,
            'departments' => $user->departments,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'email_verified_at' => $user->email_verified_at,
            'permissions' => $permissions,
            'recent_activity' => $recentActivity,
            'stats' => $stats,
        ];
    }
}
