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

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('financial_year')) {
            $query->where('financial_year', $request->query('financial_year'));
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

    public function show(ProcurementNotice $procurementNotice): JsonResponse
    {
        return response()->json(['data' => $procurementNotice]);
    }
}
