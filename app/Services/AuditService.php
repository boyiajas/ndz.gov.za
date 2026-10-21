<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Create an audit log entry.
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?User $user = null,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $departmentId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        $currentUser = $user ?? Auth::user();

        // Resolve department
        $resolvedDeptId = $departmentId;
        $resolvedDeptName = null;

        if (!$resolvedDeptId && $currentUser) {
            $resolvedDeptId = $currentUser->department_id ?? $currentUser->departmentIds()[0] ?? null;
        }

        if ($resolvedDeptId) {
            $dept = Department::find($resolvedDeptId);
            $resolvedDeptName = $dept?->name;
        } elseif ($currentUser && $currentUser->department) {
            $resolvedDeptName = $currentUser->department->name;
        }

        return AuditLog::create([
            'user_id' => $currentUser?->id,
            'user_name' => $currentUser?->name ?? 'System / Guest',
            'user_email' => $currentUser?->email,
            'user_role' => $currentUser?->role ?? 'system',
            'department_id' => $resolvedDeptId,
            'department_name' => $resolvedDeptName,
            'action' => strtolower($action),
            'module' => $module,
            'description' => $description,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable?->getKey(),
            'old_values' => self::sanitizeValues($oldValues),
            'new_values' => self::sanitizeValues($newValues),
            'ip_address' => $ipAddress ?? Request::ip(),
            'user_agent' => $userAgent ?? Request::userAgent(),
        ]);
    }

    /**
     * Sanitize sensitive attributes such as passwords and secrets.
     */
    protected static function sanitizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $redactedKeys = [
            'password',
            'password_confirmation',
            'token',
            'remember_token',
            'secret',
            'api_key',
        ];

        foreach ($values as $key => $val) {
            if (in_array(strtolower($key), $redactedKeys, true)) {
                $values[$key] = '********';
            } elseif (is_array($val)) {
                $values[$key] = self::sanitizeValues($val);
            }
        }

        return $values;
    }
}
