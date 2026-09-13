<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GalleryItem::query()
            ->active()
            ->orderBy('sort_order')
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $limit = $request->integer('limit', 0);
        if ($limit > 0) {
            $items = $query->take($limit)->get();
        } else {
            $items = $query->get();
        }

        return response()->json(['data' => $items]);
    }
}
