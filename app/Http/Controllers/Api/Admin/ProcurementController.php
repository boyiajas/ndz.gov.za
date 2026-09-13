<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcurementNotice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcurementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeProcurement($request);

        $query = ProcurementNotice::query()->with('creator:id,name')->orderByDesc('created_at');

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
                  ->orWhere('reference_no', 'like', $term);
            });
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeProcurement($request);

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in([ProcurementNotice::TYPE_TENDER, ProcurementNotice::TYPE_QUOTE])],
            'status' => ['required', 'string', Rule::in([ProcurementNotice::STATUS_OPEN, ProcurementNotice::STATUS_CLOSED])],
            'financial_year' => ['required', 'string', 'max:50'],
            'reference_no' => ['required', 'string', 'max:100', 'unique:procurement_notices,reference_no'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'closing_date' => ['nullable', 'date'],
            'briefing_date' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'document_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $validated['created_by'] = $request->user()->id;

        $notice = ProcurementNotice::create($validated);

        return response()->json(['data' => $notice->fresh('creator:id,name')], 201);
    }

    public function update(Request $request, ProcurementNotice $procurement): JsonResponse
    {
        $this->authorizeProcurement($request);

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in([ProcurementNotice::TYPE_TENDER, ProcurementNotice::TYPE_QUOTE])],
            'status' => ['required', 'string', Rule::in([ProcurementNotice::STATUS_OPEN, ProcurementNotice::STATUS_CLOSED])],
            'financial_year' => ['required', 'string', 'max:50'],
            'reference_no' => ['required', 'string', 'max:100', Rule::unique('procurement_notices', 'reference_no')->ignore($procurement->id)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'closing_date' => ['nullable', 'date'],
            'briefing_date' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'document_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $procurement->update($validated);

        return response()->json(['data' => $procurement->fresh('creator:id,name')]);
    }

    public function destroy(Request $request, ProcurementNotice $procurement): JsonResponse
    {
        $this->authorizeProcurement($request);

        $procurement->delete();

        return response()->json(['message' => 'Procurement notice deleted successfully.']);
    }

    private function authorizeProcurement(Request $request): void
    {
        abort_unless($request->user()?->canManageProcurement(), 403, 'Your role cannot manage tenders and quotes.');
    }
}
