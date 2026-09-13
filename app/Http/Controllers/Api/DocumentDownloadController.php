<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;

class DocumentDownloadController extends Controller
{
    public function __invoke(Document $document): RedirectResponse
    {
        abort_unless($document->file_url, 404);
        abort_unless($document->status === Document::STATUS_PUBLISHED, 404);
        abort_if($document->published_at && $document->published_at->isFuture(), 404);
        abort_unless($document->subcategory?->is_active && $document->subcategory?->category?->is_active, 404);

        $document->increment('download_count');

        if (str_starts_with($document->file_url, '/')) {
            return redirect($document->file_url);
        }

        return redirect()->away($document->file_url);
    }
}
