<?php

use App\Http\Controllers\Api\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Api\Admin\DashboardStatsController as AdminDashboardStatsController;
use App\Http\Controllers\Api\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Api\Admin\DocumentCategoryController as AdminDocumentCategoryController;
use App\Http\Controllers\Api\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Api\Admin\DocumentSubcategoryController as AdminDocumentSubcategoryController;
use App\Http\Controllers\Api\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Api\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Api\Admin\ProcurementController as AdminProcurementController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\VacancyController as AdminVacancyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentCatalogController;
use App\Http\Controllers\Api\DocumentDownloadController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProcurementController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\VacancyController;
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
Route::get('/procurement/years', [ProcurementController::class, 'years']);
Route::get('/procurement/categories', [ProcurementController::class, 'categories']);
Route::get('/procurement/{procurementNotice}', [ProcurementController::class, 'show']);
Route::get('/procurement/{procurementNotice}/download', [ProcurementController::class, 'download']);
Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);
Route::get('/departments', fn () => response()->json(['data' => \App\Models\Department::active()->get()]));
Route::get('/settings', [SettingController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user()->load(['department:id,name,code,slug', 'departments:id,name,code,slug']));
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile details & account settings
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::get('/profile/{user}', [ProfileController::class, 'viewUser']);

    Route::prefix('admin')->group(function () {
        // Departments Management
        Route::post('/departments/seed-defaults', [AdminDepartmentController::class, 'seedDefaults']);
        Route::apiResource('departments', AdminDepartmentController::class);

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
        Route::post('procurement/close-expired', [AdminProcurementController::class, 'closeExpired']);
        Route::apiResource('procurement', AdminProcurementController::class)
            ->parameters(['procurement' => 'procurement'])
            ->only(['index', 'store', 'update', 'destroy']);

        // Vacancies (Careers)
        Route::post('vacancies/{vacancy}/toggle-status', [AdminVacancyController::class, 'toggleStatus']);
        Route::apiResource('vacancies', AdminVacancyController::class)
            ->parameters(['vacancies' => 'vacancy'])
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        // Users & Roles
        Route::get('roles', [AdminUserController::class, 'roles']);
        Route::apiResource('users', AdminUserController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Settings
        Route::get('settings', [AdminSettingController::class, 'index']);
        Route::post('settings', [AdminSettingController::class, 'update']);

        // Audit Trail Logs
        Route::get('audit-logs/filters', [AdminAuditLogController::class, 'filters']);
        Route::get('audit-logs/export', [AdminAuditLogController::class, 'export']);
        Route::get('audit-logs', [AdminAuditLogController::class, 'index']);
        Route::get('audit-logs/{auditLog}', [AdminAuditLogController::class, 'show']);
    });
});
