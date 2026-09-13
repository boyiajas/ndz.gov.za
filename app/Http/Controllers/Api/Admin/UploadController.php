<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:25600', // 25MB max
                'mimes:jpeg,jpg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            ],
            'folder' => ['nullable', 'string', 'alpha_dash'],
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder', 'uploads');
        $extension = $file->getClientOriginalExtension();
        $safeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $safeBase . '-' . time() . '-' . Str::random(6) . '.' . $extension;

        $path = $file->storeAs("uploads/{$folder}", $filename, 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'message' => 'File uploaded successfully.',
            'url' => $url,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ], 201);
    }
}
