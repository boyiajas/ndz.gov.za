<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('role', User::ROLE_ADMIN)->value('id') ?? User::first()?->id;

        $vacancies = [
            // Open Vacancies
            [
                'title' => 'Internship Programme: Financial Management (3 Positions)',
                'reference_no' => 'NDZ-BTO-01/2026',
                'department' => 'Budget & Treasury',
                'status' => Vacancy::STATUS_OPEN,
                'closing_date' => now()->addDays(21)->setTime(16, 0),
                'remuneration' => 'Stipend as per National Treasury Guidelines',
                'location' => 'Creighton / Himeville Offices',
                'description' => 'A 24-month municipal financial management internship offering practical work experience in budgeting, reporting, expenditure management, revenue, and supply chain management.',
                'requirements' => "• Three-year Bachelor's Degree or National Diploma in Accounting, Financial Management, or Economics\n• Must be a South African citizen between 21 and 35 years old\n• Sound computer literacy (MS Excel, Word)",
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Senior Internal Auditor',
                'reference_no' => 'NDZ-MM-02/2026',
                'department' => 'Office of the Municipal Manager',
                'status' => Vacancy::STATUS_OPEN,
                'closing_date' => now()->addDays(36)->setTime(16, 0),
                'remuneration' => 'Task Grade 14 (R420,000 – R545,000 p.a.)',
                'location' => 'Creighton Main Office',
                'description' => 'Responsible for conducting risk-based audit reviews, preparing reports for the Audit and Performance Audit Committee (APAC), and ensuring statutory compliance across municipal operations.',
                'requirements' => "• B.Com Internal Auditing or equivalent qualification\n• Minimum 3 years relevant experience in local government auditing\n• Certified Internal Auditor (CIA) will be an added advantage\n• Valid Driver's license",
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Civil Engineering Technician: Roads & Stormwater',
                'reference_no' => 'NDZ-PW-03/2026',
                'department' => 'Public Works & Basic Services',
                'status' => Vacancy::STATUS_OPEN,
                'closing_date' => now()->addDays(30)->setTime(16, 0),
                'remuneration' => 'Task Grade 12',
                'location' => 'Creighton Infrastructure Depot',
                'description' => 'Oversee rural access road maintenance, culvert installation, gravel grading schedules, and contract monitoring for civil infrastructure capital projects.',
                'requirements' => "• National Diploma in Civil Engineering\n• Minimum 2 years civil construction and maintenance experience\n• Registration with ECSA as Candidate Engineering Technician\n• Valid Code B/EB Driver's license",
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],

            // Closed Vacancies
            [
                'title' => 'Manager: Local Economic Development & Tourism',
                'reference_no' => 'NDZ-COMM-01/2025',
                'department' => 'Community and Social Services',
                'status' => Vacancy::STATUS_CLOSED,
                'closing_date' => now()->subMonths(6),
                'remuneration' => 'Task Grade 16',
                'location' => 'Creighton Main Office',
                'description' => 'Spearheaded agricultural initiatives, Drakensberg tourism promotion, and SMME mentorship programmes across Dr Nkosazana Dlamini-Zuma local wards.',
                'requirements' => null,
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Town Planning Officer',
                'reference_no' => 'NDZ-DTPS-04/2025',
                'department' => 'Development and Town Planning Services',
                'status' => Vacancy::STATUS_CLOSED,
                'closing_date' => now()->subMonths(4),
                'remuneration' => 'Task Grade 12',
                'location' => 'Creighton Main Office',
                'description' => 'Processed land use management applications, SPLUMA compliance, and township development layout schemes.',
                'requirements' => null,
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Human Resources Practitioner: Labour Relations',
                'reference_no' => 'NDZ-CORP-03/2025',
                'department' => 'Corporate Support Services',
                'status' => Vacancy::STATUS_CLOSED,
                'closing_date' => now()->subMonths(3),
                'remuneration' => 'Task Grade 11',
                'location' => 'Creighton Main Office',
                'description' => 'Administered employee wellness, grievance handling, SALGBC bargaining council compliance, and disciplinary processes.',
                'requirements' => null,
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Disaster Management & Fire Officer',
                'reference_no' => 'NDZ-COMM-02/2025',
                'department' => 'Community and Social Services',
                'status' => Vacancy::STATUS_CLOSED,
                'closing_date' => now()->subMonths(2),
                'remuneration' => 'Task Grade 10',
                'location' => 'Underberg Satellite Station',
                'description' => 'Emergency response coordination, fire safety inspections, and community risk awareness campaigns.',
                'requirements' => null,
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Supply Chain Management Officer: Acquisitions',
                'reference_no' => 'NDZ-BTO-05/2025',
                'department' => 'Budget & Treasury',
                'status' => Vacancy::STATUS_CLOSED,
                'closing_date' => now()->subMonth(),
                'remuneration' => 'Task Grade 11',
                'location' => 'Creighton Main Office',
                'description' => 'Coordinated tender evaluation committees, Central Supplier Database (CSD) verification, and quotation requisitions.',
                'requirements' => null,
                'document_url' => null,
                'document_name' => null,
                'application_url' => 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
                'is_active' => true,
                'created_by' => $adminId,
            ],
        ];

        foreach ($vacancies as $data) {
            Vacancy::updateOrCreate(
                ['reference_no' => $data['reference_no']],
                $data
            );
        }
    }
}
