<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeContent($request);

        $query = Article::query()
            ->with(['author:id,name', 'creator:id,name'])
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('search')) {
            $term = '%' . $request->query('search') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('excerpt', 'like', $term);
            });
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeContent($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['author_id'] = $request->user()->id;
        $validated['published_at'] = $validated['published_at'] ?? now()->toDateString();
        $validated['read_time'] = $validated['read_time'] ?? '2 min';

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $validated['slug'] = $slug;

        $article = Article::create($validated);

        return response()->json(['data' => $article->fresh(['author:id,name', 'creator:id,name'])], 201);
    }

    public function update(Request $request, Article $news): JsonResponse
    {
        $this->authorizeContent($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $validated['updated_by'] = $request->user()->id;

        $news->update($validated);

        return response()->json(['data' => $news->fresh(['author:id,name', 'creator:id,name', 'updater:id,name'])]);
    }

    public function destroy(Request $request, Article $news): JsonResponse
    {
        $this->authorizeContent($request);

        $news->delete();

        return response()->json(['message' => 'Article deleted successfully.']);
    }

    private function authorizeContent(Request $request): void
    {
        abort_unless($request->user()?->canManageContent(), 403, 'Your role cannot manage news and blog articles.');
    }
}
