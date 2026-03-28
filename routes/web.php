<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EducationController;
use App\Http\Controllers\Dashboard\ExperienceController;
use App\Http\Controllers\Dashboard\MessageController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\SkillController;
use App\Http\Controllers\Dashboard\SocialLinkController;
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
    ->group(function () {
        // ── Overview ──────────────────────────────────────────────────────
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // ── Profile & Settings ────────────────────────────────────────────
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        // ── Projects ──────────────────────────────────────────────────────
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');

        // ── Skills ──────────────────────────────────────────────────────
        Route::resource('skills', SkillController::class);
        Route::patch('skills/{skill}/toggle', [SkillController::class, 'toggle'])->name('skills.toggle');

        // ── Experiences ──────────────────────────────────────────────────
        Route::resource('experiences', ExperienceController::class);
        Route::patch('experiences/{experience}/toggle', [ExperienceController::class, 'toggle'])->name('experiences.toggle');

        // ── Education ────────────────────────────────────────────────────
        Route::resource('education', EducationController::class);
        Route::patch('education/{education}/toggle', [EducationController::class, 'toggle'])->name('education.toggle');

        // ── Social Links ────────────────────────────────────────────────
        Route::resource('social-links', SocialLinkController::class);
        Route::patch('social-links/{socialLink}/toggle', [SocialLinkController::class, 'toggle'])->name('social-links.toggle');

        // ── Messages (Admin Only) ───────────────────────────────────────
        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');


        // ── Placeholder routes (add controllers later) ────────────────────
        Route::get('profile',   fn() => view('dashboard.index'))->name('profile');
        Route::get('resume',    fn() => view('dashboard.index'))->name('resume');
        Route::get('analytics', fn() => view('dashboard.index'))->name('analytics');
        Route::get('clients',   fn() => view('dashboard.index'))->name('clients');
        Route::get('settings',  fn() => view('dashboard.index'))->name('settings');
    });

// ── Public Routes (Frontend) ──────────────────────────────────────────
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/cv/{username?}', [CVController::class, 'show'])->name('cv.show');
// Route::get('/cv/{username}/download', [CVController::class, 'download'])->name('cv.download');
// Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('password/edit',  fn() => view('dashboard.index'))->name('password.edit');


require __DIR__ . '/auth.php';
