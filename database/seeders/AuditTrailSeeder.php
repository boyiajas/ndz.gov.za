<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AuditTrailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        if ($users->isEmpty()) {
            return;
        }

        $departments = Department::all()->keyBy('code');

        $sampleLogs = [
            [
                'email' => 'spenganem@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Mfuneko Spengane (spenganem@ndz.gov.za) [Role: admin]',
                'dept_code' => 'CSS',
                'ip' => '102.134.45.12',
                'hours_ago' => 1,
            ],
            [
                'email' => 'spenganem@ndz.gov.za',
                'action' => 'update',
                'module' => 'Settings',
                'description' => 'Updated municipal portal site settings and system configurations',
                'dept_code' => 'CSS',
                'ip' => '102.134.45.12',
                'hours_ago' => 2,
                'old_values' => ['portal_title' => 'NDZ Municipality'],
                'new_values' => ['portal_title' => 'Dr Nkosazana Dlamini Zuma Local Municipality'],
            ],
            [
                'email' => 'nxelem@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Mthokozisi Nxele (nxelem@ndz.gov.za) [Role: admin]',
                'dept_code' => 'CSS',
                'ip' => '102.134.45.14',
                'hours_ago' => 4,
            ],
            [
                'email' => 'nxelem@ndz.gov.za',
                'action' => 'create',
                'module' => 'Procurement',
                'description' => "Published tender notice: 'NDZ-TDR-2026-001' - Appointment of a service provider for Creighton Infrastructure Upgrade [Category: Open Tenders, Year: 2026-2027]",
                'dept_code' => 'CSS',
                'ip' => '102.134.45.14',
                'hours_ago' => 5,
                'new_values' => [
                    'reference_no' => 'NDZ-TDR-2026-001',
                    'title' => 'Appointment of a service provider for Creighton Infrastructure Upgrade',
                    'category' => 'Open Tenders',
                    'type' => 'tender',
                    'financial_year' => '2026-2027',
                ],
            ],
            [
                'email' => 'mshengus@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Siyabonga Mshengu (mshengus@ndz.gov.za) [Role: manager]',
                'dept_code' => 'CSS',
                'ip' => '196.25.1.20',
                'hours_ago' => 8,
            ],
            [
                'email' => 'mshengus@ndz.gov.za',
                'action' => 'upload',
                'module' => 'Documents',
                'description' => "Uploaded document: 'Service Delivery and Budget Implementation Plan 2026/27' [File: SDBIP_2026_2027_Approved.pdf]",
                'dept_code' => 'CSS',
                'ip' => '196.25.1.20',
                'hours_ago' => 9,
                'new_values' => [
                    'title' => 'Service Delivery and Budget Implementation Plan 2026/27',
                    'file_name' => 'SDBIP_2026_2027_Approved.pdf',
                    'category' => 'SDBIP',
                ],
            ],
            [
                'email' => 'sokhelab@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Buhlebakhe Sokhela (sokhelab@ndz.gov.za) [Role: manager]',
                'dept_code' => 'CSS',
                'ip' => '196.25.1.22',
                'hours_ago' => 14,
            ],
            [
                'email' => 'sokhelab@ndz.gov.za',
                'action' => 'create',
                'module' => 'Procurement',
                'description' => "Published quote notice: 'NDZ-RFQ-2026-042' - Supply and Delivery of Disaster Relief Blankets and Mattresses [Category: Quotations, Year: 2025-2026]",
                'dept_code' => 'CSS',
                'ip' => '196.25.1.22',
                'hours_ago' => 16,
                'new_values' => [
                    'reference_no' => 'NDZ-RFQ-2026-042',
                    'title' => 'Supply and Delivery of Disaster Relief Blankets and Mattresses',
                    'category' => 'Quotations',
                    'type' => 'quote',
                ],
            ],
            [
                'email' => 'communications@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Skhumbuzo Sethuntsa (communications@ndz.gov.za) [Role: manager]',
                'dept_code' => 'MM',
                'ip' => '102.134.45.18',
                'hours_ago' => 20,
            ],
            [
                'email' => 'communications@ndz.gov.za',
                'action' => 'create',
                'module' => 'News',
                'description' => "Published municipal news release: 'Mayor Unveils New Community Water Project in Ward 4'",
                'dept_code' => 'MM',
                'ip' => '102.134.45.18',
                'hours_ago' => 22,
                'new_values' => [
                    'title' => 'Mayor Unveils New Community Water Project in Ward 4',
                    'category' => 'Community Projects',
                    'status' => 'published',
                ],
            ],
            [
                'email' => 'vakalisan@ndz.gov.za',
                'action' => 'login',
                'module' => 'Auth',
                'description' => 'User logged into portal: Nqobile Vakalisa (vakalisan@ndz.gov.za) [Role: manager]',
                'dept_code' => 'MM',
                'ip' => '102.134.45.25',
                'hours_ago' => 26,
            ],
            [
                'email' => 'vakalisan@ndz.gov.za',
                'action' => 'create',
                'module' => 'Vacancies',
                'description' => "Created vacancy advertisement: 'NDZ-HR-2026-004' - Chief Financial Officer (5-Year Fixed Contract) [Dept: Budget & Treasury]",
                'dept_code' => 'MM',
                'ip' => '102.134.45.25',
                'hours_ago' => 28,
                'new_values' => [
                    'reference_no' => 'NDZ-HR-2026-004',
                    'title' => 'Chief Financial Officer (5-Year Fixed Contract)',
                    'department' => 'Budget & Treasury',
                    'status' => 'open',
                ],
            ],
            [
                'email' => 'spenganem@ndz.gov.za',
                'action' => 'update',
                'module' => 'Users',
                'description' => "Updated user account 'Nqobile Vakalisa' (vakalisan@ndz.gov.za) [Role: manager]",
                'dept_code' => 'CSS',
                'ip' => '102.134.45.12',
                'hours_ago' => 36,
                'old_values' => ['role' => 'editor'],
                'new_values' => ['role' => 'manager'],
            ],
            [
                'email' => 'nxelem@ndz.gov.za',
                'action' => 'create',
                'module' => 'Procurement',
                'description' => "Published tender notice: 'NDZ-AWARD-2020-011' - INTENTION TO AWARD: SUPPLY AND DELIVERY OF DISASTER RELIEF FOOD PARCELS [Category: Intention To Award]",
                'dept_code' => 'CSS',
                'ip' => '102.134.45.14',
                'hours_ago' => 48,
                'new_values' => [
                    'title' => 'INTENTION TO AWARD: SUPPLY AND DELIVERY OF DISASTER RELIEF FOOD PARCELS',
                    'category' => 'Intention To Award',
                ],
            ],
            [
                'email' => 'mshengus@ndz.gov.za',
                'action' => 'update',
                'module' => 'Documents',
                'description' => "Updated document: 'Quarterly Financial Report Q3' [File: Q3_Financial_Statement_Final.pdf]",
                'dept_code' => 'CSS',
                'ip' => '196.25.1.20',
                'hours_ago' => 60,
                'old_values' => ['file_name' => 'Q3_Financial_Draft.pdf'],
                'new_values' => ['file_name' => 'Q3_Financial_Statement_Final.pdf'],
            ],
            [
                'email' => 'spenganem@ndz.gov.za',
                'action' => 'update',
                'module' => 'Departments',
                'description' => "Updated department 'Community Services' [Code: CS]",
                'dept_code' => 'CSS',
                'ip' => '102.134.45.12',
                'hours_ago' => 72,
                'old_values' => ['is_active' => true],
                'new_values' => ['is_active' => true, 'sort_order' => 3],
            ],
        ];

        foreach ($sampleLogs as $log) {
            $user = $users->get(strtolower($log['email']));
            $dept = isset($log['dept_code']) ? $departments->get($log['dept_code']) : null;
            $createdAt = Carbon::now()->subHours($log['hours_ago']);

            AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'Municipal Staff',
                'user_email' => $user?->email ?? $log['email'],
                'user_role' => $user?->role ?? 'manager',
                'department_id' => $dept?->id ?? $user?->department_id,
                'department_name' => $dept?->name ?? $user?->department?->name ?? 'Corporate Support Services',
                'action' => $log['action'],
                'module' => $log['module'],
                'description' => $log['description'],
                'old_values' => $log['old_values'] ?? null,
                'new_values' => $log['new_values'] ?? null,
                'ip_address' => $log['ip'],
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
