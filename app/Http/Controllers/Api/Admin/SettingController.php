<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeSettings($request);

        $settings = Setting::all()->pluck('value', 'key')->map(function ($val) {
            $decoded = json_decode($val, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
        });

        return response()->json(['data' => $settings]);
    }

    public function update(Request $request): JsonResponse
    {
        $this->authorizeSettings($request);

        $validated = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        $all = Setting::all()->pluck('value', 'key')->map(function ($val) {
            $decoded = json_decode($val, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
        });

        return response()->json([
            'message' => 'Settings updated successfully.',
            'data' => $all,
        ]);
    }

    private function authorizeSettings(Request $request): void
    {
        abort_unless($request->user()?->canManageSettings(), 403, 'Only administrators can update site settings.');
    }
}
