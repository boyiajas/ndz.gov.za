<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Document;
use App\Models\GalleryItem;
use App\Models\ProcurementNotice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        $stats = [
            'documents_count' => Document::count(),
            'news_count' => Article::count(),
            'gallery_count' => GalleryItem::count(),
            'tenders_count' => ProcurementNotice::where('type', ProcurementNotice::TYPE_TENDER)->where('status', ProcurementNotice::STATUS_OPEN)->count(),
            'quotes_count' => ProcurementNotice::where('type', ProcurementNotice::TYPE_QUOTE)->where('status', ProcurementNotice::STATUS_OPEN)->count(),
            'users_count' => User::count(),
        ];

        $recentArticles = Article::query()->orderByDesc('created_at')->take(4)->get(['id', 'title', 'category', 'published_at', 'created_at']);
        $recentDocuments = Document::query()->with('subcategory:id,name')->orderByDesc('created_at')->take(4)->get(['id', 'title', 'document_subcategory_id', 'status', 'created_at']);
        $recentNotices = ProcurementNotice::query()->orderByDesc('created_at')->take(4)->get(['id', 'title', 'reference_no', 'type', 'status', 'closing_date', 'created_at']);

        return response()->json([
            'stats' => $stats,
            'recent_articles' => $recentArticles,
            'recent_documents' => $recentDocuments,
            'recent_notices' => $recentNotices,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }
}
