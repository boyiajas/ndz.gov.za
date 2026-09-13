<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Article::query()
            ->published()
            ->with('author:id,name')
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        if ($request->boolean('featured')) {
            $query->featured();
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('search')) {
            $term = '%' . $request->query('search') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('excerpt', 'like', $term)
                  ->orWhere('content', 'like', $term);
            });
        }

        $limit = $request->integer('limit', 0);
        if ($limit > 0) {
            $articles = $query->take($limit)->get();
        } else {
            $articles = $query->get();
        }

        return response()->json(['data' => $articles]);
    }

    public function show(string $idOrSlug): JsonResponse
    {
        $article = Article::query()
            ->published()
            ->with('author:id,name')
            ->where(function ($q) use ($idOrSlug) {
                $q->where('slug', $idOrSlug);
                if (is_numeric($idOrSlug)) {
                    $q->orWhere('id', (int) $idOrSlug);
                }
            })
            ->firstOrFail();

        return response()->json(['data' => $article]);
    }
}
