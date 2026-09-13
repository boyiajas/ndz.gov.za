<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\GalleryItem;
use App\Models\ProcurementNotice;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DashboardContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $adminId = $admin?->id;

        // 1. Seed News / Blog Articles
        $articles = [
            [
                'title' => 'Mayor tables R674.8 million annual budget for 2025/2026',
                'category' => 'Budget',
                'excerpt' => 'A people-first budget focused on infrastructure delivery, youth jobs, and safer communities.',
                'content' => '<p>Her Worship the Mayor Cllr. Sindisiwe P. Msomi has officially tabled the municipal budget of R674.8 million for the 2025/2026 financial year. The budget emphasizes key strategic priorities including rural road rehabilitation, electrification of outlying wards, water security initiatives, and youth entrepreneurship funds.</p><p>Community consultation sessions will be rolled out across all 13 wards in the coming weeks to allow residents to participate actively in shaping their municipal priorities.</p>',
                'image_url' => 'https://picsum.photos/seed/ndz-news-1/800/500',
                'read_time' => '2 min',
                'published_at' => '2025-05-28',
                'is_featured' => true,
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Her Worship the Mayor to table the 2025/2026 Annual Budget',
                'category' => 'News',
                'excerpt' => 'Council prepares for the annual budget address and public engagement sessions across Dr Nkosazana Dlamini-Zuma Municipality.',
                'content' => '<p>The Office of the Mayor announces the schedule for the 2025/2026 Annual Budget address. Citizens, stakeholders, and community leaders are invited to observe the council proceedings and submit inputs during the public participation window.</p>',
                'image_url' => 'https://picsum.photos/seed/ndz-news-2/800/500',
                'read_time' => '3 min',
                'published_at' => '2025-05-27',
                'is_featured' => true,
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'PUBLIC NOTICE – Extension Of Operating Hours: Traffic Department',
                'category' => 'Notice',
                'excerpt' => 'Extended operating hours introduced to improve access to driver licensing and motor vehicle testing services.',
                'content' => '<p>The Dr Nkosazana Dlamini-Zuma Local Municipality wishes to notify all motorists and citizens that the Traffic and Licensing Centre will extend its operating hours every Thursday and Saturday to alleviate congestion and ensure speedier service delivery.</p>',
                'image_url' => 'https://picsum.photos/seed/ndz-news-3/800/500',
                'read_time' => '1 min',
                'published_at' => '2025-05-26',
                'is_featured' => true,
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Dr Nkosazana Dlamini Zuma Municipality Acquired A Fire Engine',
                'category' => 'News',
                'excerpt' => 'The procurement of the fire engine is earmarked at ensuring adequate and sufficient equipment to deal with structural and veld fires.',
                'content' => '<p>The procurement of the fire engine is earmarked at ensuring adequate and sufficient equipment to deal with fires within the area of jurisdiction of the municipality. Mostly structural fires that are experienced on regular basis will now receive rapid response times, protecting lives and municipal assets.</p>',
                'image_url' => '/img/news/news-fire-engine.jpg',
                'read_time' => '3 min',
                'published_at' => '2024-10-14',
                'is_featured' => false,
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Welcoming Address By Mayor On The SALGA KZN Provincial Members Assembly',
                'category' => 'Blog',
                'excerpt' => 'A landmark address stressing intergovernmental relations, local economic development, and sustainable rural service delivery.',
                'content' => '<p>The Mayor extended a warm welcome to all provincial leadership, Mayors, and Municipal Managers attending the SALGA KZN Provincial Members Assembly, reiterating commitment to good governance, transparent procurement, and cooperative governance.</p>',
                'image_url' => '/img/news/news-salga.jpg',
                'read_time' => '4 min',
                'published_at' => '2024-10-03',
                'is_featured' => false,
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Appreciation To Stakeholders Who Contributed To Duzi 2 Sani Tourism Event',
                'category' => 'Events',
                'excerpt' => 'Celebrating the success of the annual tourism month event, driving visitor numbers and promoting Southern Drakensberg destinations.',
                'content' => '<p>Dr Nkosazana Dlamini-Zuma Municipality has long grasped the potential of Travel & Tourism to drive economic growth, create jobs and promote social cohesion. The municipality extends its gratitude to participants, sponsors, and community members who made Duzi 2 Sani a triumph.</p>',
                'image_url' => '/img/news/news-duzi2sani.jpg',
                'read_time' => '2 min',
                'published_at' => '2024-09-21',
                'is_featured' => false,
                'is_published' => true,
                'created_by' => $adminId,
            ],
        ];

        foreach ($articles as $article) {
            $article['slug'] = \Illuminate\Support\Str::slug($article['title']);
            Article::updateOrCreate(['title' => $article['title']], $article);
        }

        // 2. Seed Event Gallery Items
        $galleryItems = [
            [
                'title' => 'Southern Drakensberg Scenic Vistas',
                'category' => 'Tourism',
                'image_url' => '/tourism-1.jpg',
                'description' => 'Spectacular mountain waterfalls and serene landscapes across Dr Nkosazana Dlamini-Zuma.',
                'sort_order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Community Service Handover',
                'category' => 'Community',
                'image_url' => '/tourism-2.jpg',
                'description' => 'Municipal leadership handing over community projects to local ward residents.',
                'sort_order' => 2,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Municipal Council in Session',
                'category' => 'Council',
                'image_url' => '/tourism-3.jpg',
                'description' => 'Elected councillors debating strategic municipal resolutions in council chambers.',
                'sort_order' => 3,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Youth Heritage Cultural Festival',
                'category' => 'Events',
                'image_url' => '/tourism-4.jpg',
                'description' => 'Vibrant traditional dance, music, and craft exhibitions by local youth.',
                'sort_order' => 4,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Agricultural and Rural Development Showcase',
                'category' => 'Tourism',
                'image_url' => '/tourism-5.jpg',
                'description' => 'Showcasing agricultural yields, local farmers, and green economy initiatives.',
                'sort_order' => 5,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Annual Mayoral Marathon Event',
                'category' => 'Events',
                'image_url' => '/img/gallery/gallery_1.jpg',
                'description' => 'Community road runners participating in the annual wellness and sport challenge.',
                'sort_order' => 6,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Infrastructure Road Rehabilitation',
                'category' => 'Infrastructure',
                'image_url' => '/img/gallery/gallery_2.jpg',
                'description' => 'Public Works team paving access roads in rural wards.',
                'sort_order' => 7,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Disaster Management Equipment Handover',
                'category' => 'Community',
                'image_url' => '/img/gallery/gallery_3.jpg',
                'description' => 'Handover of firefighting and emergency rescue equipment.',
                'sort_order' => 8,
                'is_active' => true,
                'created_by' => $adminId,
            ],
        ];

        foreach ($galleryItems as $item) {
            GalleryItem::updateOrCreate(['title' => $item['title']], $item);
        }

        // 3. Seed Procurement Notices (Tenders & Quotes)
        $notices = [
            [
                'type' => ProcurementNotice::TYPE_TENDER,
                'status' => ProcurementNotice::STATUS_OPEN,
                'financial_year' => '2025/2026',
                'reference_no' => 'NDZ-PW-001-2025',
                'title' => 'Construction of Ward 4 Community Access Road & Stormwater Drainage',
                'description' => 'Proposals are invited from experienced civil engineering contractors CIDB 4CE or higher for construction of 3.2km gravel to tar upgrade.',
                'closing_date' => now()->addDays(21)->setTime(12, 0),
                'briefing_date' => 'Compulsory briefing: 25 March 2025 at 10:00 AM, Creighton Municipal Hall',
                'contact_person' => 'Mr. S. Mkhize (Technical) / Ms. N. Cele (SCM) - scm@ndz.gov.za',
                'document_url' => '/documents/tenders/NDZ-PW-001-2025-Tender-Document.pdf',
                'created_by' => $adminId,
            ],
            [
                'type' => ProcurementNotice::TYPE_TENDER,
                'status' => ProcurementNotice::STATUS_OPEN,
                'financial_year' => '2025/2026',
                'reference_no' => 'NDZ-BTO-002-2025',
                'title' => 'Provision of Municipal Financial Management System & ICT Support Services for 36 Months',
                'description' => 'Appointment of an accredited service provider for MSCOA compliant financial management system and managed support services.',
                'closing_date' => now()->addDays(28)->setTime(12, 0),
                'briefing_date' => 'Non-compulsory virtual briefing via Microsoft Teams',
                'contact_person' => 'Chief Financial Officer - bto@ndz.gov.za',
                'document_url' => '/documents/tenders/NDZ-BTO-002-2025-Spec.pdf',
                'created_by' => $adminId,
            ],
            [
                'type' => ProcurementNotice::TYPE_QUOTE,
                'status' => ProcurementNotice::STATUS_OPEN,
                'financial_year' => '2025/2026',
                'reference_no' => 'NDZ-COMM-Q014-2025',
                'title' => 'Supply and Delivery of Disaster Relief Blankets, Mattresses and Food Parcels',
                'description' => 'Quotations are requested from locally registered suppliers for immediate delivery of disaster relief supplies.',
                'closing_date' => now()->addDays(7)->setTime(11, 0),
                'briefing_date' => 'No briefing required',
                'contact_person' => 'Disaster Management Unit - 039 833 1038',
                'document_url' => '/documents/quotes/NDZ-COMM-Q014-RFQ.pdf',
                'created_by' => $adminId,
            ],
            [
                'type' => ProcurementNotice::TYPE_TENDER,
                'status' => ProcurementNotice::STATUS_CLOSED,
                'financial_year' => '2024/2025',
                'reference_no' => 'NDZ-CS-009-2024',
                'title' => 'Supply and Delivery of 2x Heavy Duty Waste Refuse Compactor Trucks',
                'description' => 'Tender closed and awarded to evaluate bidders.',
                'closing_date' => '2024-11-15 12:00:00',
                'briefing_date' => 'Completed',
                'contact_person' => 'SCM Unit - scm@ndz.gov.za',
                'document_url' => '/documents/tenders/NDZ-CS-009-Award-Notice.pdf',
                'created_by' => $adminId,
            ],
        ];

        foreach ($notices as $notice) {
            ProcurementNotice::updateOrCreate(['reference_no' => $notice['reference_no']], $notice);
        }

        // 4. Seed Site Settings
        $settings = [
            'municipality_name' => ['value' => 'Dr Nkosazana Dlamini-Zuma Local Municipality', 'group' => 'general'],
            'tagline' => ['value' => 'Serving Our Communities with Dedication', 'group' => 'general'],
            'contact_email' => ['value' => 'helpdesk@ndz.gov.za', 'group' => 'contact'],
            'contact_phone' => ['value' => '+27 39 833 1038', 'group' => 'contact'],
            'physical_address' => ['value' => 'Main Street, Creighton, 3263, KwaZulu-Natal, South Africa', 'group' => 'contact'],
            'office_hours' => ['value' => 'Monday - Friday: 07:30 AM to 04:00 PM', 'group' => 'contact'],
            'emergency_numbers' => [
                'value' => [
                    ['label' => 'Police Services', 'display' => '10111', 'tel' => '10111'],
                    ['label' => 'Rural Metro Fire & Rescue', 'display' => '+27 33 345 0080', 'tel' => '+27333450080'],
                    ['label' => 'Disaster Management Hotline', 'display' => '+27 83 708 2314', 'tel' => '+27837082314'],
                    ['label' => 'Medical Rescue Services', 'display' => '10177', 'tel' => '10177'],
                    ['label' => 'Municipal Fire Unit', 'display' => '+27 66 469 8893', 'tel' => '+27664698893'],
                    ['label' => 'Anti-Fraud & Corruption Hotline', 'display' => '0800 701 701', 'tel' => '0800701701'],
                ],
                'group' => 'emergency',
            ],
            'social_links' => [
                'value' => [
                    'facebook' => 'https://facebook.com/ndzmunicipality',
                    'twitter' => 'https://x.com/ndzmunicipality',
                    'youtube' => 'https://youtube.com/@ndzmunicipality',
                ],
                'group' => 'social',
            ],
            'announcement_banner' => [
                'value' => [
                    'is_active' => false,
                    'text' => 'Council Budget Consultation meetings underway in all 13 wards. View schedule under Notices.',
                    'link' => '/news',
                ],
                'group' => 'announcement',
            ],
        ];

        foreach ($settings as $key => $data) {
            Setting::set($key, $data['value'], $data['group']);
        }
    }
}
