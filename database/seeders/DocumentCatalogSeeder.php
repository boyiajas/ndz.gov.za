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
     * Seed the document categories currently shown on the public Documents page.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Municipal Documents',
                'items' => [
                    'Executive Office',
                    'Financial Services',
                    'Economic Development',
                    'Infrastructure',
                    'Community Services',
                    'Council Reports',
                ],
            ],
            [
                'name' => 'Policies',
                'items' => [
                    'Financial Policies',
                    'Administrative Policies',
                    'Community Policies',
                    'Infrastructure Policies',
                    'Municipal Policies',
                    'Economic Development and Planning Policies',
                    'Incentives Policies',
                    'By-Laws',
                ],
            ],
            [
                'name' => 'LED',
                'items' => [
                    'LED Strategy',
                    'Incentives Policy',
                ],
            ],
            [
                'name' => 'Economic Development Planning & Human Settlement',
                'items' => [
                    'Town Planning',
                ],
            ],
            [
                'name' => 'Performance Management',
                'items' => [
                    'Performance Report',
                ],
            ],
            [
                'name' => 'Public Notices',
                'items' => [
                    'Notices',
                ],
            ],
            [
                'name' => 'Tariffs',
                'items' => [
                    '2024-2025',
                ],
            ],
            [
                'name' => 'IDP',
                'items' => [
                    '2026-2027',
                    '2025-2026',
                    '2024-2025',
                    '2023-2024',
                    '2022-2023',
                    '2021-2022',
                ],
            ],
            [
                'name' => 'Budget',
                'items' => [
                    '2026-2027',
                    '2025-2026',
                    '2024-2025',
                    '2023-2024',
                    '2022-2023',
                    '2021-2022',
                ],
            ],
            [
                'name' => 'Annual Report',
                'items' => [
                    '2025-2026',
                    '2024-2025',
                    '2023-2024',
                    '2022-2023',
                    '2021-2022',
                ],
            ],
            [
                'name' => 'Newsletter',
                'items' => [
                    'Latest editions',
                ],
            ],
            [
                'name' => 'Building Control',
                'items' => [
                    'Building Control Forms',
                ],
            ],
            [
                'name' => 'All Service Agreements',
                'items' => [
                    'Service Level Agreements',
                ],
            ],
            [
                'name' => 'Long-Term Borrowings Contracts',
                'items' => [
                    'Borrowings Contracts',
                ],
            ],
            [
                'name' => 'EDP & HS',
                'items' => [
                    'Environmental Management',
                ],
            ],
            [
                'name' => 'Economic Development and Planning',
                'items' => [
                    'Spatial Development Framework',
                ],
            ],
        ];

        foreach ($categories as $categoryIndex => $categoryData) {
            $category = DocumentCategory::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'subtitle' => "{$categoryData['name']} records and municipal publications",
                    'description' => 'Public document category managed through the NDZ portal.',
                    'image_url' => null,
                    'sort_order' => $categoryIndex + 1,
                    'is_active' => true,
                ],
            );

            foreach ($categoryData['items'] as $itemIndex => $itemName) {
                $subcategory = DocumentSubcategory::updateOrCreate(
                    [
                        'document_category_id' => $category->id,
                        'slug' => Str::slug($itemName),
                    ],
                    [
                        'name' => $itemName,
                        'description' => "Document listing for {$itemName}.",
                        'sort_order' => $itemIndex + 1,
                        'is_active' => true,
                    ],
                );

                Document::updateOrCreate(
                    [
                        'document_subcategory_id' => $subcategory->id,
                        'title' => "{$itemName} sample listing",
                    ],
                    [
                        'description' => 'Replace this seeded sample with the official uploaded document record.',
                        'file_url' => null,
                        'status' => Document::STATUS_PUBLISHED,
                        'published_at' => now(),
                        'sort_order' => 1,
                    ],
                );
            }
        }
    }
}
