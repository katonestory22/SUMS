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
    TechnicalController
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

        return Storage::disk('public')->download($path);

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

    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
