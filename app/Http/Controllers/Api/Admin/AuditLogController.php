<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display a paginated listing of audit logs.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorizeAudit($request);

        $query = AuditLog::query()
            ->with(['user:id,name,email,role', 'department:id,name,code'])
            ->orderByDesc('created_at');

        // Department scoping for non-global managers
        $this->applyDepartmentScoping($request, $query);

        // Apply request filters
        $query->filter($request->only([
            'user_id',
            'department_id',
            'action',
            'module',
            'date_from',
            'date_to',
            'search',
        ]));

        $perPage = min(max((int) $request->query('per_page', 25), 10), 100);
        $logs = $query->paginate($perPage);

        return response()->json($logs);
    }

    /**
     * Retrieve filter options and summary statistics.
     */
    public function filters(Request $request): JsonResponse
    {
        $this->authorizeAudit($request);

        $users = User::select('id', 'name', 'email', 'role')->orderBy('name')->get();
        $departments = Department::active()->select('id', 'name', 'code', 'slug')->orderBy('name')->get();

        $modules = [
            'Users',
            'Departments',
            'Documents',
            'Procurement',
            'Vacancies',
            'News',
            'Gallery',
            'Settings',
            'Auth',
        ];

        $actions = [
            'create',
            'update',
            'delete',
            'login',
            'logout',
            'status_change',
            'upload',
            'download',
        ];

        $today = now()->startOfDay();

        $statsQuery = AuditLog::query();
        $this->applyDepartmentScoping($request, $statsQuery);

        $totalLogs = (clone $statsQuery)->count();
        $todayLogs = (clone $statsQuery)->where('created_at', '>=', $today)->count();
        $uniqueUsers = (clone $statsQuery)->whereNotNull('user_id')->distinct('user_id')->count('user_id');

        return response()->json([
            'users' => $users,
            'departments' => $departments,
            'modules' => $modules,
            'actions' => $actions,
            'stats' => [
                'total_logs' => $totalLogs,
                'today_logs' => $todayLogs,
                'unique_users' => $uniqueUsers,
            ],
        ]);
    }

    /**
     * Display the specified audit log.
     */
    public function show(Request $request, AuditLog $auditLog): JsonResponse
    {
        $this->authorizeAudit($request);

        $auditLog->load(['user:id,name,email,role', 'department:id,name,code']);

        return response()->json([
            'data' => $auditLog,
        ]);
    }

    /**
     * Export filtered audit logs to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $this->authorizeAudit($request);

        $query = AuditLog::query()
            ->with(['user:id,name,email,role', 'department:id,name,code'])
            ->orderByDesc('created_at');

        $this->applyDepartmentScoping($request, $query);

        $query->filter($request->only([
            'user_id',
            'department_id',
            'action',
            'module',
            'date_from',
            'date_to',
            'search',
        ]));

        $filename = 'ndz-audit-trail-' . now()->format('Y-m-d-His') . '.csv';

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'ID',
                'Date & Time',
                'User Name',
                'User Email',
                'Role',
                'Department',
                'Module',
                'Action',
                'Description',
                'IP Address',
            ]);

            // Stream rows in chunks
            $query->chunk(250, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user_name,
                        $log->user_email,
                        $log->user_role,
                        $log->department_name ?? 'N/A',
                        $log->module,
                        strtoupper($log->action),
                        $log->description,
                        $log->ip_address,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Authorize user access to audit logs.
     */
    private function authorizeAudit(Request $request): void
    {
        abort_unless($request->user()?->canViewAuditLogs(), 403, 'Unauthorized to view audit trail logs.');
    }

    /**
     * Scope query if user is restricted to specific departments.
     */
    private function applyDepartmentScoping(Request $request, $query): void
    {
        $user = $request->user();
        if (!$user) {
            return;
        }

        // Global admin sees all
        if ($user->isGlobalAdmin()) {
            return;
        }

        $deptIds = $user->departmentIds();
        if (!empty($deptIds)) {
            $query->where(function ($q) use ($deptIds, $user) {
                $q->whereIn('department_id', $deptIds)
                  ->orWhere('user_id', $user->id);
            });
        }
    }
}
