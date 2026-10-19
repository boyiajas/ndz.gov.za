<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProcurementNotice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProcurementNotice::query()->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('financial_year')) {
            $year = $request->query('financial_year');
            $hyphenYear = str_replace('/', '-', $year);
            $slashYear = str_replace('-', '/', $year);
            $query->where(function ($q) use ($year, $hyphenYear, $slashYear) {
                $q->where('financial_year', $year)
                  ->orWhere('financial_year', $hyphenYear)
                  ->orWhere('financial_year', $slashYear);
            });
        }

        if ($request->filled('search')) {
            $term = '%' . $request->query('search') . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('reference_no', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        return response()->json(['data' => $query->get()]);
    }

    public function years(): JsonResponse
    {
        $years = ProcurementNotice::query()
            ->select('financial_year')
            ->distinct()
            ->orderByDesc('financial_year')
            ->pluck('financial_year')
            ->map(fn ($y) => str_replace('/', '-', $y))
            ->unique()
            ->values();

        return response()->json(['data' => $years]);
    }

    public function categories(): JsonResponse
    {
        $categories = ProcurementNotice::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        return response()->json(['data' => $categories]);
    }

    public function show(ProcurementNotice $procurementNotice): JsonResponse
    {
        return response()->json(['data' => $procurementNotice]);
    }

    public function download(ProcurementNotice $procurementNotice)
    {
        $procurementNotice->increment('download_count');

        if ($procurementNotice->document_url) {
            return redirect()->away($procurementNotice->document_url);
        }

        return response()->json(['message' => 'No document available for this listing.'], 404);
    }
}
