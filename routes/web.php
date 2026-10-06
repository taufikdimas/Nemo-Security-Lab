<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\NetworkDiagnosticController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HoneypotController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileViewController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ConfigController as AdminConfigController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortal\ClientPortalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VulnDbController;
use App\Http\Controllers\PentestEngagementController;
use App\Http\Controllers\PentestFindingController;
use App\Http\Controllers\PentestReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Token authenticated feed for internal integrators
Route::get('/api/v1/assets', [AssetController::class, 'apiIndex'])
    ->middleware(['api.token', 'internal'])
    ->name('api.assets');

Route::middleware(['api.token', 'internal'])->prefix('api/v1')->group(function () {
    Route::get('/incidents', [IncidentController::class, 'apiIndex'])->name('api.incidents');

    Route::get('/users/me', function () {
        return response()->json(['data' => auth()->user()]);
    })->name('api.user.me');
});

// Authenticated routes
/*
 |--------------------------------------------------------------------------
 | Authenticated internal routes
 |--------------------------------------------------------------------------
 | `internal` menahan role client supaya tidak bisa membuka halaman internal
 | (products, assets, projects, incidents, clients, employees, vulndb, files,
 | reports, import, tools/diagnostic).
 |
 | 'dashboard' & 'logout' dikecualikan karena keduanya dibutuhkan agar alur
 | client tetap hidup: DashboardController mengarahkan client ke portal, dan
 | client harus bisa logout.
 */
Route::middleware(['auth', 'prefs', 'internal:dashboard,logout'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/profile/api', [ProfileController::class, 'apiAccess'])->name('profile.api');

    // Asset inventory export (must precede the resource: /assets/{asset} would shadow it)
    Route::get('/assets/export', [AssetController::class, 'export'])->name('assets.export');

    // Asset inventory
    Route::resource('assets', AssetController::class);

    // Incident management
    Route::resource('incidents', IncidentController::class);
    Route::patch('/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])
        ->name('incidents.status');

    Route::post('/incidents/{incident}/notes', [IncidentController::class, 'storeNote'])->name('incidents.notes.store');
    Route::patch('/incidents/{incident}/assign', [IncidentController::class, 'assign'])->name('incidents.assign');

    // Projects CRUD
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/comments', [ProjectController::class, 'storeComment'])->name('projects.comments.store');

    Route::patch('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.status');
    Route::post('/projects/{project}/members', [ProjectController::class, 'storeMember'])->name('projects.members.store');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'destroyMember'])->name('projects.members.destroy');

    // Clients CRUD
    Route::resource('clients', ClientController::class);

    // Employees CRUD (Khusus Administrator)
    Route::resource('employees', EmployeeController::class)->middleware('role:admin');

    // Vulnerability Database CRUD
    Route::resource('vulndb', VulnDbController::class)->parameters(['vulndb' => 'vuln']);

    // Network diagnostics
    Route::get('/tools/diagnostic', [NetworkDiagnosticController::class, 'form'])->name('tools.diagnostic');
    Route::post('/tools/diagnostic/run', [NetworkDiagnosticController::class, 'resolve'])->name('tools.diagnostic.run');

    Route::get('/tools/diagnostic/history', [NetworkDiagnosticController::class, 'history'])->name('tools.diagnostic.history');

    // Reporting
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/generate', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/download', [ReportController::class, 'download'])->name('reports.download');

    // Files
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/upload', [FileController::class, 'uploadForm'])->name('files.upload');
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.store');

    Route::get('/files/{file}', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    // File Viewer
    Route::get('/files/{file}/view', [FileViewController::class, 'view'])->name('files.view');

    // Import
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/users', [ImportController::class, 'importUsers'])->name('import.users');

    // Pentest Engagements, Reports, and Findings
    Route::prefix('pentest')->name('pentest.')->group(function () {
        Route::resource('engagements', PentestEngagementController::class);

        // Findings nested under engagement
        Route::get('/engagements/{engagement}/findings/create', [PentestFindingController::class, 'create'])->name('findings.create');
        Route::post('/engagements/{engagement}/findings', [PentestFindingController::class, 'store'])->name('findings.store');
        Route::get('/engagements/{engagement}/findings/{finding}', [PentestFindingController::class, 'show'])->name('findings.show');
        Route::get('/engagements/{engagement}/findings/{finding}/edit', [PentestFindingController::class, 'edit'])->name('findings.edit');
        Route::put('/engagements/{engagement}/findings/{finding}', [PentestFindingController::class, 'update'])->name('findings.update');

        // Reports
        Route::get('/engagements/{engagement}/reports/create', [PentestReportController::class, 'create'])->name('reports.create');
        Route::post('/engagements/{engagement}/reports', [PentestReportController::class, 'store'])->name('reports.store');
        Route::resource('reports', PentestReportController::class)->except(['create', 'store']);
        Route::post('/reports/{report}/publish', [PentestReportController::class, 'publish'])->name('reports.publish');
        Route::post('/reports/{report}/toggle-visibility', [PentestReportController::class, 'toggleClientVisible'])->name('reports.toggleVisibility');
        Route::get('/reports/{report}/download', [PentestReportController::class, 'download'])->name('reports.download');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // System configuration summary
        Route::get('/config', [AdminConfigController::class, 'index'])->name('config');

        // Platform activity log
        Route::get('/activity', [AdminDashboardController::class, 'activity'])->name('activity');

        // Inbound request monitoring
        Route::get('/threats', [HoneypotController::class, 'index'])->name('threats');

        // User management
        Route::resource('users', AdminUserController::class);

        Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

        // File management
        Route::get('/files', [AdminFileController::class, 'index'])->name('files.index');
        Route::post('/files/import-url', [AdminFileController::class, 'importFromUrl'])->name('files.import-url');
    });
});

/*
|--------------------------------------------------------------------------
| Client Portal
|--------------------------------------------------------------------------
| Terpisah dari grup internal di atas. Hanya akun ber-role 'client' yang
| bisa masuk, dan hanya ke 6 halaman ini — tidak ada link ke route internal.
| Diletakkan SEBELUM catch-all honeypot di bawah, jika tidak catch-all akan
| menelan /portal/*.
*/
Route::middleware(['auth', 'prefs', 'role:client'])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::get('/dashboard', [ClientPortalController::class, 'dashboard'])->name('dashboard');
        
        // Proyek Saya
        Route::get('/projects', [ClientPortalController::class, 'projects'])->name('projects');
        Route::get('/projects/{project}', [ClientPortalController::class, 'showProject'])->name('projects.show');
        
        // Insiden
        Route::get('/incidents', [ClientPortalController::class, 'incidents'])->name('incidents');
        
        // PENTEST
        Route::get('/pentest', [ClientPortalController::class, 'pentest'])->name('pentest');
        Route::get('/findings', [ClientPortalController::class, 'allFindings'])->name('findings');
        Route::get('/pentest/{engagement}', [ClientPortalController::class, 'showPentest'])->name('pentest.show');
        Route::get('/pentest/{engagement}/findings', [ClientPortalController::class, 'pentestFindings'])->name('pentest.findings');
        Route::get('/pentest/{engagement}/reports', [ClientPortalController::class, 'pentestReports'])->name('pentest.reports');
        
        // DOKUMEN: Laporan SOC & Laporan Pentest
        Route::get('/reports', [ClientPortalController::class, 'reports'])->name('reports');
        Route::get('/reports/{filename}/content', [ClientPortalController::class, 'reportContent'])->name('reports.content');
        Route::get('/reports/{filename}/download', [ClientPortalController::class, 'reportDownload'])->name('reports.download');
        
        Route::get('/pentest-reports', [ClientPortalController::class, 'allPentestReports'])->name('pentest-reports');
        Route::get('/pentest/reports/{report}', [ClientPortalController::class, 'showPentestReport'])->name('pentest.reports.show');
        Route::get('/pentest/reports/{report}/download', [ClientPortalController::class, 'downloadPentestReport'])->name('pentest.reports.download');
        
        // Akun
        Route::get('/profile', [ClientPortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [ClientPortalController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/avatar', [ClientPortalController::class, 'updateAvatar'])->name('profile.avatar');
    });

/*
 |--------------------------------------------------------------------------
 | Backup archive
 |--------------------------------------------------------------------------
 | INTERNAL ARTEFACT: meant for internal operators and administrators.
 |
 | Registered above the honeypot catch-all below, otherwise the catch-all
 | would swallow it and serve a decoy instead.
 |
 | Only `auth` guards this route. It is deliberately NOT inside the
 | `internal` group above, so a Client Portal session reaches it -- that is
 | the exercise: an authentication guard standing in for an authorization
 | guard. See BackupController::dump().
 */
Route::get('/.backup', [BackupController::class, 'dump'])
    ->middleware('auth')
    ->name('backup.dump');

// Catch-all for unmatched paths, registered last so it never shadows a real route
Route::get('/{any}', [HoneypotController::class, 'decoy'])
    ->where('any', '.*')
    ->name('honeypot.hit');
