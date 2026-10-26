<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcurementNotice;
use App\Services\AuditService;
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
            'category' => ['nullable', 'string', 'max:100'],
            'financial_year' => ['required', 'string', 'max:50'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'closing_date' => ['nullable', 'date'],
            'briefing_date' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'document_url' => ['nullable', 'string', 'max:2048'],
            'download_count' => ['nullable', 'integer', 'min:0'],
            'file_name' => ['nullable', 'string', 'max:255'],
            'file_size' => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($validated['category'])) {
            $validated['category'] = $validated['type'] === ProcurementNotice::TYPE_QUOTE ? 'Quotations' : 'Open Tenders';
        }

        if (empty($validated['reference_no'])) {
            $prefix = $validated['type'] === ProcurementNotice::TYPE_QUOTE ? 'NDZ-RFQ' : 'NDZ-TDR';
            $validated['reference_no'] = $prefix . '-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        }

        $validated['created_by'] = $request->user()->id;

        $notice = ProcurementNotice::create($validated);

        AuditService::log(
            action: 'create',
            module: 'Procurement',
            description: "Published {$notice->type} notice: '{$notice->reference_no}' - {$notice->title} [Category: {$notice->category}, Year: {$notice->financial_year}]",
            auditable: $notice,
            newValues: [
                'reference_no' => $notice->reference_no,
                'title' => $notice->title,
                'category' => $notice->category,
                'type' => $notice->type,
                'financial_year' => $notice->financial_year,
            ]
        );

        return response()->json(['data' => $notice->fresh('creator:id,name')], 201);
    }

    public function update(Request $request, ProcurementNotice $procurement): JsonResponse
    {
        $this->authorizeProcurement($request);

        $oldValues = [
            'reference_no' => $procurement->reference_no,
            'title' => $procurement->title,
            'category' => $procurement->category,
            'status' => $procurement->status,
        ];

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in([ProcurementNotice::TYPE_TENDER, ProcurementNotice::TYPE_QUOTE])],
            'status' => ['required', 'string', Rule::in([ProcurementNotice::STATUS_OPEN, ProcurementNotice::STATUS_CLOSED])],
            'category' => ['nullable', 'string', 'max:100'],
            'financial_year' => ['required', 'string', 'max:50'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'closing_date' => ['nullable', 'date'],
            'briefing_date' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'document_url' => ['nullable', 'string', 'max:2048'],
            'download_count' => ['nullable', 'integer', 'min:0'],
            'file_name' => ['nullable', 'string', 'max:255'],
            'file_size' => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($validated['category'])) {
            $validated['category'] = $validated['type'] === ProcurementNotice::TYPE_QUOTE ? 'Quotations' : 'Open Tenders';
        }

        $procurement->update($validated);

        AuditService::log(
            action: 'update',
            module: 'Procurement',
            description: "Updated {$procurement->type} notice: '{$procurement->reference_no}' - {$procurement->title}",
            auditable: $procurement,
            oldValues: $oldValues,
            newValues: [
                'reference_no' => $procurement->reference_no,
                'title' => $procurement->title,
                'category' => $procurement->category,
                'status' => $procurement->status,
            ]
        );

        return response()->json(['data' => $procurement->fresh('creator:id,name')]);
    }

    public function destroy(Request $request, ProcurementNotice $procurement): JsonResponse
    {
        $this->authorizeProcurement($request);

        AuditService::log(
            action: 'delete',
            module: 'Procurement',
            description: "Deleted {$procurement->type} notice: '{$procurement->reference_no}' - {$procurement->title}",
            auditable: $procurement,
            oldValues: [
                'reference_no' => $procurement->reference_no,
                'title' => $procurement->title,
                'category' => $procurement->category,
            ]
        );

        $procurement->delete();

        return response()->json(['message' => 'Procurement notice deleted successfully.']);
    }

    public function closeExpired(Request $request): JsonResponse
    {
        $this->authorizeProcurement($request);

        $job = new \App\Jobs\CloseExpiredListingsJob();
        $result = $job->handle();

        $message = "Auto-close check completed: {$result['tenders_closed']} tender(s), {$result['quotes_closed']} quote(s), and {$result['vacancies_closed']} vacancy/vacancies closed.";

        return response()->json([
            'message' => $message,
            'data' => $result,
        ]);
    }

    private function authorizeProcurement(Request $request): void
    {
        abort_unless($request->user()?->canManageProcurement(), 403, 'Your role cannot manage tenders and quotes.');
    }
}
