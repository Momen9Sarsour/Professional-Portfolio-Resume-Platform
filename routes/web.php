<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\SkillController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboardd', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])
    // ->middleware(['auth'])   // ← uncomment after running: php artisan make:auth  OR  laravel/breeze
    ->group(function () {

        // ── Overview ──────────────────────────────────────────────────────
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // ── Projects ──────────────────────────────────────────────────────
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');

        // ── Skills ──────────────────────────────────────────────────────
        Route::resource('skills', SkillController::class);
        Route::patch('skills/{project}/toggle', [SkillController::class, 'toggle'])->name('skills.toggle');

        // ── Placeholder routes (add controllers later) ────────────────────
        Route::get('profile',   fn() => view('dashboard.index'))->name('profile');
        // Route::get('skills',    fn() => view('dashboard.index'))->name('skills');
        Route::get('resume',    fn() => view('dashboard.index'))->name('resume');
        Route::get('messages',  fn() => view('dashboard.index'))->name('messages');
        Route::get('analytics', fn() => view('dashboard.index'))->name('analytics');
        Route::get('clients',   fn() => view('dashboard.index'))->name('clients');
        Route::get('settings',  fn() => view('dashboard.index'))->name('settings');
    });
Route::get('password/edit',  fn() => view('dashboard.index'))->name('password.edit');


require __DIR__ . '/auth.php';
