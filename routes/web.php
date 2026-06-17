<?php

use App\Http\Controllers\{
    ActivityController,
    ActivityProgressHistoryController,
    AllocationController,
    ClientController,
    UserController,
    DirectorController,
    AdminController,
    ExpenseController,
    FinanceController,
    PhaseController,
    ProfileController,
    ProjectController,
    TechnicalController,
    ReportController,
    CompanyExpenseController
};

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::get('/', fn() => view('welcome'))->name('home');

Route::middleware(['auth', 'verified', 'active'])->group(function () {

    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'director' => redirect()->route('director.dashboard'),
            'finance' => redirect()->route('finance.dashboard'),
            'technical' => redirect()->route('technical.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // dashboards
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    Route::prefix('director')->middleware('role:director,admin')->group(function () {
        Route::get('/dashboard', [DirectorController::class, 'index'])->name('director.dashboard');
    });

    Route::prefix('finance')->middleware('role:finance,director,admin')->group(function () {
        Route::get('/dashboard', [FinanceController::class, 'index'])->name('finance.dashboard');
    });

    Route::prefix('technical')->middleware('role:technical,director,admin')->group(function () {
        Route::get('/dashboard', [TechnicalController::class, 'index'])->name('technical.dashboard');
    });

    /*
    | CORE
    */
    Route::resource('projects', ProjectController::class);

    Route::get('projects/{project}/expenses', [ProjectController::class, 'expenses'])
        ->name('projects.expenses');

    Route::resource('allocations', AllocationController::class);

    Route::resource('expenses', ExpenseController::class)->except(['create']);

    Route::get('expenses/create/{allocation}', [ExpenseController::class, 'create'])
        ->name('expenses.create');

    Route::resource('clients', ClientController::class);

    Route::get('projects/{project}/overview', [ProjectController::class, 'overview'])
        ->name('projects.overview');

    Route::prefix('director')->middleware('role:director,admin')->group(function () {

        Route::get('/dashboard', [DirectorController::class, 'index'])
            ->name('director.dashboard');

        Route::get('/users', [DirectorController::class, 'users'])
            ->name('director.users');
    });


    Route::get('/file/download/{type}/{file}', function ($type, $file) {

        $allowed = ['receipts', 'activity-evidence'];

        if (!in_array($type, $allowed)) {
            abort(403);
        }

        $path = $type . '/' . $file;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path); // @phpstan-ignore-line

    })->name('file.download');

    /*
    | PHASES + ACTIVITIES
    */
    Route::get('projects/{project}/phases/create', [PhaseController::class, 'create'])
        ->name('phases.create');

    Route::post('projects/{project}/phases', [PhaseController::class, 'store'])
        ->name('phases.store');

    Route::get('activities/create/{phase}', [ActivityController::class, 'create'])
        ->name('activities.create');

    Route::post('activities', [ActivityController::class, 'store'])
        ->name('activities.store');

    Route::post('activities/{activity}/progress', [ActivityProgressHistoryController::class, 'store'])
        ->name('activities.progress.store');

    Route::get('activities/{activity}/history', [ActivityProgressHistoryController::class, 'index'])
        ->name('activities.progress.history');

    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    /*
    | PROFILE
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });



    // Finance & Technical — upload
    Route::get('/reports/create', [ReportController::class, 'create'])
        ->name('reports.create')
        ->middleware('role:finance,technical,admin');

    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store')
        ->middleware('role:finance,technical,admin');

    Route::post('/reports/generate', [ReportController::class, 'generate'])
        ->name('reports.generate')
        ->middleware('role:finance,technical,admin');

    // Director — view all
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index')
        ->middleware('role:director,admin');

    // Download & preview — shared
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])
        ->name('reports.download')->middleware('role:finance,technical,director,admin');

    Route::get('/reports/{report}/preview', [ReportController::class, 'preview'])
        ->name('reports.preview')->middleware('role:finance,technical,director,admin');
    Route::get('/reports/my', [ReportController::class, 'myReports'])
        ->name('reports.my')
        ->middleware('role:finance,technical,admin');

    Route::resource('users', UserController::class);


    // Finance — company expenses
    Route::middleware('role:finance,admin')->group(function () {
        Route::get('/company-expenses', [CompanyExpenseController::class, 'index'])
            ->name('company-expenses.index');
        Route::get('/company-expenses/create', [CompanyExpenseController::class, 'create'])
            ->name('company-expenses.create');
        Route::post('/company-expenses', [CompanyExpenseController::class, 'store'])
            ->name('company-expenses.store');
        Route::get('/company-expenses/{companyExpense}/edit', [CompanyExpenseController::class, 'edit'])
            ->name('company-expenses.edit');
        Route::put('/company-expenses/{companyExpense}', [CompanyExpenseController::class, 'update'])
            ->name('company-expenses.update');
    });

    // Report generation — finance and director
    Route::middleware('role:finance,director,admin')
        ->post('/company-expenses/report', [CompanyExpenseController::class, 'generateReport'])
        ->name('company-expenses.report');

    // Director — audit log
    Route::middleware('role:director,admin')
        ->get('/company-expenses/audit', [CompanyExpenseController::class, 'audit'])
        ->name('company-expenses.audit');

    // Expense edit
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])
        ->name('expenses.edit');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])
        ->name('expenses.update');

    // Unified audit log
    Route::get('/audit', [DirectorController::class, 'audit'])
        ->name('director.audit')
        ->middleware('role:director,admin');
});


require __DIR__ . '/auth.php';
