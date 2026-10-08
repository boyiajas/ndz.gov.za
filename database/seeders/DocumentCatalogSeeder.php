<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentSubcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentCatalogSeeder extends Seeder
{
    /**
     * Seed the official municipal document categories and subcategories.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Office of the municipal manager',
                'slug' => 'office-of-the-municipal-manager',
                'subtitle' => 'Office of the Municipal Manager records, charters, and governance frameworks',
                'description' => 'Official administrative records, audit charters, delegation frameworks, and governance publications from the Office of the Municipal Manager.',
                'items' => [],
            ],
            [
                'name' => 'Corporate Services',
                'slug' => 'corporate-services',
                'subtitle' => 'Corporate administration, human resources, organizational design, and ICT',
                'description' => 'Public records and operational guidelines for corporate administration, organizational structures, HR management, and ICT services.',
                'items' => [
                    'Organisational Structure',
                    'Administration',
                    'Information Technology',
                    'HR Policies',
                ],
            ],
            [
                'name' => 'Budget & Treasury',
                'slug' => 'budget-treasury',
                'subtitle' => 'Financial management, budget reporting, and municipal property rates',
                'description' => 'Municipal budget schedules, monthly and quarterly section 71/52 reports, annual financial documentation, MPRA valuation records, and finance structures.',
                'items' => [
                    'Budget Reports',
                    'Monthly Reports',
                    'Quarterly Reports',
                    'Annual Reports',
                    'Budget Documents',
                    'MPRA',
                    'Organogram Finance',
                ],
            ],
            [
                'name' => 'Public works & Basic Services',
                'slug' => 'public-works-basic-services',
                'subtitle' => 'Infrastructure development, basic service delivery, and technical services',
                'description' => 'Infrastructure plans, capital project reports, service delivery charters, and public works department structures.',
                'items' => [],
            ],
            [
                'name' => 'Community Services',
                'slug' => 'community-services',
                'subtitle' => 'Public safety, disaster management, local economic development, and social amenities',
                'description' => 'Community development records, disaster management frameworks, sports and recreation, youth fund initiatives, and LED documentation.',
                'items' => [
                    'Organogram Community Services',
                    'Sports & Recreation',
                    'Youth Fund',
                    'LED',
                ],
            ],
            [
                'name' => 'Development & Town Planning Services',
                'slug' => 'development-town-planning-services',
                'subtitle' => 'Spatial planning, land use management, and building control documentation',
                'description' => 'Municipal Spatial Development Frameworks (MSDF), land use planning records, zoning guidelines, and building control frameworks.',
                'items' => [
                    'MSDF',
                    'Planning and Land Use Documents',
                ],
            ],
            [
                'name' => 'Mpac Reports',
                'slug' => 'mpac-reports',
                'subtitle' => 'Municipal Public Accounts Committee oversight and governance reports',
                'description' => 'Statutory MPAC oversight reports, public accountability assessments, and council committee review documents.',
                'items' => [],
            ],
            [
                'name' => 'Annual Reports',
                'slug' => 'annual-reports',
                'subtitle' => 'Official municipal annual reports, performance reviews, and statutory submissions',
                'description' => 'Audited annual reports, service delivery assessments, performance overviews, and statutory reporting across financial years.',
                'items' => [],
            ],
            [
                'name' => 'Pms',
                'slug' => 'pms',
                'subtitle' => 'Performance Management System, signed agreements, and SDBIP reports',
                'description' => 'Service Delivery and Budget Implementation Plans (SDBIP), Section 56/57 performance agreements, performance contracts, and evaluation reports.',
                'items' => [
                    'Performance Agreements 2026/2027',
                    'Performance Agreements 2025/2026',
                    'Performance Agreements 2024/2025',
                    'Performance Agreements 2023/2024',
                    'Performance Agreements 2021/2022',
                    'Performance Agreements 2020/2021',
                    'Final 2020/2021 SDBIP',
                    'Special Revised 2019/2020 SDBIP',
                    'Revised SDBIP For 2018-2019',
                    '2017-2018 SDBIP',
                    '201819 SDBIP',
                    'Mid Year Performance Report',
                ],
            ],
            [
                'name' => 'idp',
                'slug' => 'idp',
                'subtitle' => 'Five-year Integrated Development Plans, annual reviews, and process frameworks',
                'description' => 'Integrated Development Plans (IDP), process plans, framework documentation, and public participation submissions.',
                'items' => [],
            ],
            [
                'name' => 'Gazetted By Laws',
                'slug' => 'gazetted-by-laws',
                'subtitle' => 'Official gazetted municipal by-laws and promulgated local regulations',
                'description' => 'Enacted municipal by-laws, tariffs, rates, law enforcement directives, and credit control regulations published in the provincial gazette.',
                'items' => [],
            ],
            [
                'name' => 'Ward Based Plans',
                'slug' => 'ward-based-plans',
                'subtitle' => 'Ward operational plans, priority projects, and local ward profiling',
                'description' => 'Development and operational plans for municipal wards 1 through 15, identifying community needs and ward committee priorities.',
                'items' => [],
            ],
            [
                'name' => 'Policies',
                'slug' => 'policies',
                'subtitle' => 'Council-approved operational, financial, HR, and governance policies',
                'description' => 'Official municipal policies adopted by Council across finance, administration, infrastructure, human resources, and community governance.',
                'items' => [],
            ],
        ];

        $newSlugs = [];

        foreach ($categories as $categoryIndex => $categoryData) {
            $slug = $categoryData['slug'] ?? Str::slug($categoryData['name']);
            $newSlugs[] = $slug;

            $category = DocumentCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $categoryData['name'],
                    'subtitle' => $categoryData['subtitle'] ?? "{$categoryData['name']} records and municipal publications",
                    'description' => $categoryData['description'] ?? 'Public document category managed through the NDZ portal.',
                    'sort_order' => $categoryIndex + 1,
                    'is_active' => true,
                ],
            );

            $newSubSlugs = [];

            foreach ($categoryData['items'] as $itemIndex => $itemName) {
                $subSlug = Str::slug($itemName);
                $newSubSlugs[] = $subSlug;

                DocumentSubcategory::updateOrCreate(
                    [
                        'document_category_id' => $category->id,
                        'slug' => $subSlug,
                    ],
                    [
                        'name' => $itemName,
                        'description' => "Document listing for {$itemName}.",
                        'sort_order' => $itemIndex + 1,
                        'is_active' => true,
                    ],
                );
            }

            // Clean up any old subcategories for this category that are not in the new list
            DocumentSubcategory::where('document_category_id', $category->id)
                ->whereNotIn('slug', $newSubSlugs)
                ->each(function ($oldSub) {
                    $oldSub->documents()->whereNull('file_url')->delete();
                    if ($oldSub->documents()->count() === 0) {
                        $oldSub->delete();
                    }
                });
        }

        // Clean up any legacy categories that are no longer in the official 13
        DocumentCategory::whereNotIn('slug', $newSlugs)
            ->each(function ($oldCat) {
                foreach ($oldCat->subcategories as $oldSub) {
                    $oldSub->documents()->whereNull('file_url')->delete();
                    if ($oldSub->documents()->count() === 0) {
                        $oldSub->delete();
                    }
                }
                if ($oldCat->subcategories()->count() === 0) {
                    $oldCat->delete();
                }
            });
    }
}
