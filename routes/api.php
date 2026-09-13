<?php

use App\Http\Controllers\Api\Admin\DashboardStatsController as AdminDashboardStatsController;
use App\Http\Controllers\Api\Admin\DocumentCategoryController as AdminDocumentCategoryController;
use App\Http\Controllers\Api\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Api\Admin\DocumentSubcategoryController as AdminDocumentSubcategoryController;
use App\Http\Controllers\Api\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Api\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Api\Admin\ProcurementController as AdminProcurementController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentCatalogController;
use App\Http\Controllers\Api\DocumentDownloadController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProcurementController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', fn () => response()->json(['status' => 'ok', 'app' => config('app.name')]));

// Public auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public document routes
Route::get('/document-catalog', [DocumentCatalogController::class, 'index']);
Route::get('/document-catalog/{documentCategory:slug}', [DocumentCatalogController::class, 'category']);
Route::get('/document-catalog/{documentCategory:slug}/{subcategorySlug}', [DocumentCatalogController::class, 'show']);
Route::get('/documents/{document}/download', DocumentDownloadController::class);

// Public content routes (News, Gallery, Procurement, Settings)
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{idOrSlug}', [NewsController::class, 'show']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/procurement', [ProcurementController::class, 'index']);
Route::get('/procurement/{procurementNotice}', [ProcurementController::class, 'show']);
Route::get('/settings', [SettingController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->group(function () {
        // Dashboard overview stats
        Route::get('/dashboard-stats', AdminDashboardStatsController::class);

        // Upload service
        Route::post('/upload', AdminUploadController::class);

        // Documents
        Route::apiResource('document-categories', AdminDocumentCategoryController::class)
            ->parameters(['document-categories' => 'documentCategory'])
            ->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('document-subcategories', AdminDocumentSubcategoryController::class)
            ->parameters(['document-subcategories' => 'documentSubcategory'])
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('documents', AdminDocumentController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // News & Blogs
        Route::apiResource('news', AdminNewsController::class)
            ->parameters(['news' => 'news'])
            ->only(['index', 'store', 'update', 'destroy']);

        // Event Gallery
        Route::apiResource('gallery', AdminGalleryController::class)
            ->parameters(['gallery' => 'gallery'])
            ->only(['index', 'store', 'update', 'destroy']);

        // Procurement (Tenders & Quotes)
        Route::apiResource('procurement', AdminProcurementController::class)
            ->parameters(['procurement' => 'procurement'])
            ->only(['index', 'store', 'update', 'destroy']);

        // Users & Roles
        Route::get('roles', [AdminUserController::class, 'roles']);
        Route::apiResource('users', AdminUserController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Settings
        Route::get('settings', [AdminSettingController::class, 'index']);
        Route::post('settings', [AdminSettingController::class, 'update']);
    });
});
