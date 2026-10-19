<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed official municipal departments and map document categories.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Office of the Municipal Manager',
                'code' => 'MM',
                'slug' => 'office-of-the-municipal-manager',
                'email' => 'mm@ndz.gov.za',
                'head_title' => 'Municipal Manager',
                'description' => 'Office of the Accounting Officer overseeing strategic administration, internal audit, performance management, and municipal governance.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Budget & Treasury',
                'code' => 'BTO',
                'slug' => 'budget-treasury',
                'email' => 'cfo@ndz.gov.za',
                'head_title' => 'Chief Financial Officer',
                'description' => 'Financial management, annual budget preparation, expenditure control, revenue enhancement, and supply chain management.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Corporate Support Services',
                'code' => 'CORP',
                'slug' => 'corporate-services',
                'email' => 'corporate@ndz.gov.za',
                'head_title' => 'General Manager: Corporate Services',
                'description' => 'Administration, human capital management, legal services, information & communication technology (ICT), and council support.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Community and Social Services',
                'code' => 'COMM',
                'slug' => 'community-services',
                'email' => 'community@ndz.gov.za',
                'head_title' => 'General Manager: Community Services',
                'description' => 'Community development, disaster management, local economic development (LED), sports, arts, culture, and social welfare.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Public Works & Basic Services',
                'code' => 'PW',
                'slug' => 'public-works-basic-services',
                'email' => 'publicworks@ndz.gov.za',
                'head_title' => 'General Manager: Public Works',
                'description' => 'Infrastructure delivery, rural access roads, electricity networks, fleet management, and municipal capital projects.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Development and Town Planning Services',
                'code' => 'DTPS',
                'slug' => 'development-town-planning-services',
                'email' => 'townplanning@ndz.gov.za',
                'head_title' => 'General Manager: Development Planning',
                'description' => 'Spatial planning, municipal spatial development frameworks (MSDF), building control, land use management, and environmental management.',
                'sort_order' => 6,
            ],
        ];

        $deptModels = [];
        foreach ($departments as $data) {
            $deptModels[$data['code']] = Department::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // Map categories to departments
        $categoryDepartmentMap = [
            // Office of the Municipal Manager
            'office-of-the-municipal-manager' => 'MM',
            'pms' => 'MM',
            'mpac-reports' => 'MM',

            // Budget & Treasury
            'budget-treasury' => 'BTO',
            'annual-reports' => 'BTO',

            // Corporate Support Services
            'corporate-services' => 'CORP',
            'policies' => 'CORP',
            'gazetted-by-laws' => 'CORP',

            // Community and Social Services
            'community-services' => 'COMM',
            'ward-based-plans' => 'COMM',

            // Public Works & Basic Services
            'public-works-basic-services' => 'PW',

            // Development and Town Planning Services
            'development-town-planning-services' => 'DTPS',
            'idp' => 'DTPS',
        ];

        foreach ($categoryDepartmentMap as $categorySlug => $deptCode) {
            $dept = $deptModels[$deptCode] ?? null;
            if ($dept) {
                DocumentCategory::where('slug', $categorySlug)->update([
                    'department_id' => $dept->id,
                ]);
            }
        }

        // Propagate department_id from categories to all documents
        foreach (DocumentCategory::whereNotNull('department_id')->get() as $category) {
            Document::where('document_category_id', $category->id)->update([
                'department_id' => $category->department_id,
            ]);
        }
    }
}
