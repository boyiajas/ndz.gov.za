<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VacancyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeVacancies($request);

        $query = Vacancy::query()->with('creator:id,name');

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('department') && $request->query('department') !== 'all') {
            $query->where('department', $request->query('department'));
        }

        if ($request->filled('search')) {
            $query->search($request->query('search'));
        }

        $vacancies = $query->orderByDesc('created_at')->get();

        return response()->json([
            'data' => $vacancies,
            'meta' => [
                'total' => Vacancy::count(),
                'open_count' => Vacancy::open()->count(),
                'closed_count' => Vacancy::closed()->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeVacancies($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:150'],
            'status' => ['required', 'string', Rule::in(Vacancy::STATUSES)],
            'closing_date' => ['nullable', 'date'],
            'remuneration' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'document_url' => ['nullable', 'string', 'max:2048'],
            'document_name' => ['nullable', 'string', 'max:255'],
            'application_url' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'file' => ['nullable', 'file', 'max:25600', 'mimes:pdf,doc,docx,zip'],
        ]);

        if ($request->hasFile('file')) {
            $uploaded = $this->handleFileUpload($request->file('file'));
            $validated['document_url'] = $uploaded['url'];
            $validated['document_name'] = $uploaded['name'];
        }

        $validated['location'] = !empty($validated['location']) ? $validated['location'] : 'Creighton Main Office';
        $validated['application_url'] = !empty($validated['application_url']) ? $validated['application_url'] : 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y';
        $validated['created_by'] = $request->user()->id;
        $validated['is_active'] = $request->boolean('is_active', true);

        unset($validated['file']);

        $vacancy = Vacancy::create($validated);

        return response()->json([
            'message' => 'Vacancy created successfully.',
            'data' => $vacancy->fresh('creator:id,name'),
        ], 201);
    }

    public function show(Request $request, Vacancy $vacancy): JsonResponse
    {
        $this->authorizeVacancies($request);

        return response()->json([
            'data' => $vacancy->load('creator:id,name'),
        ]);
    }

    public function update(Request $request, Vacancy $vacancy): JsonResponse
    {
        $this->authorizeVacancies($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:150'],
            'status' => ['required', 'string', Rule::in(Vacancy::STATUSES)],
            'closing_date' => ['nullable', 'date'],
            'remuneration' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'document_url' => ['nullable', 'string', 'max:2048'],
            'document_name' => ['nullable', 'string', 'max:255'],
            'application_url' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'file' => ['nullable', 'file', 'max:25600', 'mimes:pdf,doc,docx,zip'],
        ]);

        if ($request->hasFile('file')) {
            $uploaded = $this->handleFileUpload($request->file('file'));
            $validated['document_url'] = $uploaded['url'];
            $validated['document_name'] = $uploaded['name'];
        }

        if ($request->boolean('remove_document')) {
            $validated['document_url'] = null;
            $validated['document_name'] = null;
        }

        unset($validated['file'], $validated['remove_document']);

        $vacancy->update($validated);

        return response()->json([
            'message' => 'Vacancy updated successfully.',
            'data' => $vacancy->fresh('creator:id,name'),
        ]);
    }

    public function destroy(Request $request, Vacancy $vacancy): JsonResponse
    {
        $this->authorizeVacancies($request);

        $vacancy->delete();

        return response()->json([
            'message' => 'Vacancy deleted successfully.',
        ]);
    }

    public function toggleStatus(Request $request, Vacancy $vacancy): JsonResponse
    {
        $this->authorizeVacancies($request);

        $newStatus = $vacancy->status === Vacancy::STATUS_OPEN
            ? Vacancy::STATUS_CLOSED
            : Vacancy::STATUS_OPEN;

        $vacancy->update(['status' => $newStatus]);

        return response()->json([
            'message' => "Vacancy status changed to {$newStatus}.",
            'data' => $vacancy->fresh('creator:id,name'),
        ]);
    }

    private function handleFileUpload($file): array
    {
        $extension = $file->getClientOriginalExtension();
        $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = 'vacancy-' . $safeBase . '-' . time() . '-' . Str::random(5) . '.' . $extension;

        $path = $file->storeAs('uploads/vacancies', $filename, 'public');
        $url = Storage::disk('public')->url($path);

        return [
            'url' => $url,
            'name' => $file->getClientOriginalName(),
        ];
    }

    private function authorizeVacancies(Request $request): void
    {
        abort_unless($request->user()?->canManageVacancies(), 403, 'Unauthorized to manage vacancies.');
    }
}
