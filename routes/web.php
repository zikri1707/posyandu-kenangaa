<?php

// --- CONTROLLERS UMUM ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\ArticleController;
// --- CONTROLLERS ADMIN (LOKASI DI FOLDER 'Web') ---
use App\Http\Controllers\Web\GalleryController;
use App\Http\Controllers\Web\GalleryFolderController;
use App\Http\Controllers\Web\MedicalRecordController;
use App\Http\Controllers\Web\PatientController;
use App\Http\Controllers\Web\PedukuhanController;
use App\Http\Controllers\Web\PosyanduController;
use App\Http\Controllers\Web\PublicController;
use App\Http\Controllers\Web\ScheduleController;
use App\Http\Controllers\Web\UserController;
use App\Livewire\Admin\Management\ArticleManagement;
// --- LIVEWIRE COMPONENTS ---
use App\Livewire\Admin\Management\GalleryManagement;
use App\Livewire\Admin\Management\MedicalRecordManagement;
use App\Livewire\Admin\Management\PedukuhanManagement;
use App\Livewire\Admin\Management\PosyanduManagement;
use App\Livewire\Admin\Management\ScheduleManagement;
use App\Livewire\Admin\Management\UserManagement;
use App\Livewire\Admin\PatientManagement\Index as PatientManagementIndex;
use Illuminate\Support\Facades\Route;

// Home Page - Public Home
Route::get('/', [PublicController::class, 'home'])->name('public.home');

// Public routes
Route::get('/articles', [App\Http\Controllers\Web\PublicArticleController::class, 'index'])->name('public.articles.index');
Route::get('/articles/{slug}', [App\Http\Controllers\Web\PublicArticleController::class, 'show'])->name('public.articles.show');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    // Throttle: maksimal 5 percobaan login per menit per IP (anti brute-force)
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');

});

// Protected Routes (Require Login)
Route::middleware(['auth'])->group(function () {

    // Dashboard (Admin Dashboard)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics
    Route::get('admin/analytics', function () {
        return view('admin.analytics.index');
    })->name('admin.analytics');

    // 1. PATIENT MANAGEMENT
    Route::get('admin/patients', PatientManagementIndex::class)->name('admin.patients.index');
    Route::get('admin/patients/create', [PatientController::class, 'create'])->name('admin.patients.create');
    // ... rest of patient routes
    Route::post('admin/patients', [PatientController::class, 'store'])->name('admin.patients.store');
    Route::get('admin/patients/import', [PatientController::class, 'importForm'])->name('admin.patients.import');
    Route::post('admin/patients/import', [PatientController::class, 'import'])->name('admin.patients.import.store');
    Route::get('admin/patients/template', [PatientController::class, 'downloadTemplate'])->name('admin.patients.template');
    Route::get('admin/patients/{patient}', [PatientController::class, 'show'])->name('admin.patients.show');
    Route::get('admin/patients/{patient}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
    Route::get('admin/patients/{patient}/growth-chart', \App\Livewire\Admin\PatientManagement\GrowthChart::class)->name('admin.patients.growth-chart');
    Route::put('admin/patients/{patient}', [PatientController::class, 'update'])->name('admin.patients.update');
    Route::delete('admin/patients/{patient}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');

    // 2. POSYANDU
    Route::get('admin/posyandu', PosyanduManagement::class)->name('admin.posyandu.index');
    Route::get('admin/posyandu/create', [PosyanduController::class, 'create'])->name('admin.posyandu.create');
    Route::post('admin/posyandu', [PosyanduController::class, 'store'])->name('admin.posyandu.store');
    Route::get('admin/posyandu/{posyandu}', [PosyanduController::class, 'show'])->name('admin.posyandu.show');
    Route::get('admin/posyandu/{posyandu}/edit', [PosyanduController::class, 'edit'])->name('admin.posyandu.edit');
    Route::put('admin/posyandu/{posyandu}', [PosyanduController::class, 'update'])->name('admin.posyandu.update');
    Route::delete('admin/posyandu/{posyandu}', [PosyanduController::class, 'destroy'])->name('admin.posyandu.destroy');

    // 3. SCHEDULES
    Route::get('admin/schedules', ScheduleManagement::class)->name('admin.schedules.index');
    Route::get('admin/schedules/create', \App\Livewire\Admin\Management\ScheduleCreate::class)->name('admin.schedules.create');
    Route::get('admin/schedules/{schedule}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::get('admin/schedules/{schedule}/edit', \App\Livewire\Admin\Management\ScheduleUpdate::class)->name('admin.schedules.edit');
    Route::put('admin/schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('admin/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');

    // 4. GALLERY
    Route::get('admin/gallery', GalleryManagement::class)->name('admin.gallery.index');
    // Folder
    Route::get('admin/gallery/create', [GalleryFolderController::class, 'create'])->name('admin.gallery.create');
    Route::post('admin/gallery', [GalleryFolderController::class, 'store'])->name('admin.gallery.store');
    Route::get('admin/gallery/{folder}', [GalleryFolderController::class, 'show'])->name('admin.gallery.show');
    Route::get('admin/gallery/{folder}/edit', [GalleryFolderController::class, 'edit'])->name('admin.gallery.edit');
    Route::put('admin/gallery/{folder}', [GalleryFolderController::class, 'update'])->name('admin.gallery.update');
    Route::delete('admin/gallery/{folder}', [GalleryFolderController::class, 'destroy'])->name('admin.gallery.destroy');
    // Media di dalam folder
    Route::get('admin/gallery/{folder}/media/create', [GalleryController::class, 'create'])->name('admin.gallery.media.create');
    Route::post('admin/gallery/{folder}/media', [GalleryController::class, 'store'])->name('admin.gallery.media.store');
    Route::delete('admin/gallery/{folder}/media/{gallery}', [GalleryController::class, 'destroy'])->name('admin.gallery.media.destroy');
    Route::put('admin/gallery/{folder}/media/{gallery}', [GalleryController::class, 'update'])->name('admin.gallery.media.update');

    // 5. ARTICLES
    Route::get('admin/articles', ArticleManagement::class)->name('admin.articles.index');
    Route::get('admin/articles/create', \App\Livewire\Admin\Management\ArticleCreate::class)->name('admin.articles.create');
    Route::get('admin/articles/{article}', \App\Livewire\Admin\Management\ArticleShow::class)->name('admin.articles.show');
    Route::get('admin/articles/{article}/edit', \App\Livewire\Admin\Management\ArticleUpdate::class)->name('admin.articles.edit');
    // PUT and DELETE are handled by the components/service now, but we keep the show route if needed for public view/preview
    Route::delete('admin/articles/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');

    // 6. MEDICAL RECORDS
    Route::get('admin/medical-records', MedicalRecordManagement::class)->name('admin.medical-records.index');
    Route::get('admin/medical-records/bulk', \App\Livewire\Admin\MedicalRecord\BulkMeasurementEntry::class)->name('admin.medical-records.bulk');
    Route::get('admin/medical-records/create', [MedicalRecordController::class, 'create'])->name('admin.medical-records.create');
    Route::post('admin/medical-records', [MedicalRecordController::class, 'store'])->name('admin.medical-records.store');
    Route::get('admin/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('admin.medical-records.show');
    Route::get('admin/medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('admin.medical-records.edit');
    Route::put('admin/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('admin.medical-records.update');
    Route::delete('admin/medical-records/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('admin.medical-records.destroy');

    // 7. USERS
    Route::middleware(['role:superadmin'])->group(function () {
        // Role & Permission Management
        Route::get('admin/settings/roles', \App\Livewire\Admin\Settings\RolePermissionManagement::class)->name('admin.settings.roles');

        Route::get('admin/users', UserManagement::class)->name('admin.users.index');
        Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // ---------------------------------------------------------
    // 8. ACTIVITY LOGS (CONTROLLER)
    // ---------------------------------------------------------
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('admin/activity-logs', [App\Http\Controllers\Web\ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
        Route::get('admin/activity-logs/{activityLog}', [App\Http\Controllers\Web\ActivityLogController::class, 'show'])->name('admin.activity-logs.show');
        Route::get('admin/activity-logs/statistics', [App\Http\Controllers\Web\ActivityLogController::class, 'statistics'])->name('admin.activity-logs.statistics');
    });

    // ---------------------------------------------------------
    // 9. REPORTS (MONTHLY REPORTS - Livewire)
    // ---------------------------------------------------------
    Route::middleware(['role:superadmin,admin,kader'])->group(function () {
        Route::get('admin/reports', [\App\Http\Controllers\Web\ReportController::class, 'index'])->name('admin.reports.index');
        Route::post('admin/reports/export-excel', [\App\Http\Controllers\Web\ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
        Route::post('admin/reports/export-pdf', [\App\Http\Controllers\Web\ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
        Route::get('admin/reports/individual/{patient}', [\App\Http\Controllers\Web\ReportController::class, 'showIndividual'])->name('admin.reports.individual');
        Route::post('admin/reports/individual/{patient}/export-pdf', [\App\Http\Controllers\Web\ReportController::class, 'exportIndividualPdf'])->name('admin.reports.individual.pdf');
        Route::post('admin/reports/individual/{patient}/export-excel', [\App\Http\Controllers\Web\ReportController::class, 'exportIndividualExcel'])->name('admin.reports.individual.excel');
    });

    // 10. PEDUKUHANS
    Route::get('admin/pedukuhans', PedukuhanManagement::class)->name('admin.pedukuhans.index');
    Route::get('admin/pedukuhans/create', [PedukuhanController::class, 'create'])->name('admin.pedukuhans.create');
    Route::post('admin/pedukuhans', [PedukuhanController::class, 'store'])->name('admin.pedukuhans.store');
    Route::get('admin/pedukuhans/{pedukuhan}', [PedukuhanController::class, 'show'])->name('admin.pedukuhans.show');
    Route::get('admin/pedukuhans/{pedukuhan}/edit', [PedukuhanController::class, 'edit'])->name('admin.pedukuhans.edit');
    Route::put('admin/pedukuhans/{pedukuhan}', [PedukuhanController::class, 'update'])->name('admin.pedukuhans.update');
    Route::delete('admin/pedukuhans/{pedukuhan}', [PedukuhanController::class, 'destroy'])->name('admin.pedukuhans.destroy');

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Route for 404 page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
