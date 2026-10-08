<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentDownloadController extends Controller
{
    public function __invoke(Document $document): BinaryFileResponse|RedirectResponse
    {
        abort_unless($document->file_url, 404);
        abort_unless($document->status === Document::STATUS_PUBLISHED, 404);
        abort_if($document->published_at && $document->published_at->isFuture(), 404);
        $category = $document->category ?: $document->subcategory?->category;
        abort_unless($category?->is_active, 404);
        if ($document->subcategory) {
            abort_unless($document->subcategory->is_active, 404);
        }

        $document->increment('download_count');

        // If local public file exists, serve it directly to prevent host/port redirect issues
        if (str_starts_with($document->file_url, '/storage/')) {
            $relativePath = substr($document->file_url, strlen('/storage/'));
            $absolutePath = storage_path('app/public/' . $relativePath);
            if (file_exists($absolutePath)) {
                return response()->download($absolutePath);
            }
        }

        if (str_starts_with($document->file_url, '/')) {
            return redirect($document->file_url);
        }

        return redirect()->away($document->file_url);
    }
}
