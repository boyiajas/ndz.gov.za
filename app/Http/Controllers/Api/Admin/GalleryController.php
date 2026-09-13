<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeContent($request);

        $query = GalleryItem::query()
            ->with('creator:id,name')
            ->orderBy('sort_order')
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeContent($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'image_url' => ['required', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $item = GalleryItem::create($validated);

        return response()->json(['data' => $item->fresh('creator:id,name')], 201);
    }

    public function update(Request $request, GalleryItem $gallery): JsonResponse
    {
        $this->authorizeContent($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'image_url' => ['required', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $gallery->update($validated);

        return response()->json(['data' => $gallery->fresh('creator:id,name')]);
    }

    public function destroy(Request $request, GalleryItem $gallery): JsonResponse
    {
        $this->authorizeContent($request);

        $gallery->delete();

        return response()->json(['message' => 'Gallery photo deleted successfully.']);
    }

    private function authorizeContent(Request $request): void
    {
        abort_unless($request->user()?->canManageContent(), 403, 'Your role cannot manage gallery photos.');
    }
}
