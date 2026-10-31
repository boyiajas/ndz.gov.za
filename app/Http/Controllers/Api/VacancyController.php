<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vacancy::query()->active();

        if ($request->filled('status')) {
            $status = strtolower($request->query('status'));
            if (in_array($status, [Vacancy::STATUS_OPEN, Vacancy::STATUS_CLOSED], true)) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('department')) {
            $query->department($request->query('department'));
        }

        if ($request->filled('search')) {
            $query->search($request->query('search'));
        }

        // Sort in descending order from newest to oldest date/time
        $query->orderByDesc('created_at')->orderByDesc('closing_date')->orderByDesc('id');

        $vacancies = $query->get();

        return response()->json([
            'data' => $vacancies,
            'meta' => [
                'total' => $vacancies->count(),
                'open_count' => Vacancy::active()->open()->count(),
                'closed_count' => Vacancy::active()->closed()->count(),
            ],
        ]);
    }

    public function show(Vacancy $vacancy): JsonResponse
    {
        abort_unless($vacancy->is_active, 404);

        return response()->json([
            'data' => $vacancy,
        ]);
    }
}
