<?php

use App\Http\Controllers\CVController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EducationController;
use App\Http\Controllers\Dashboard\ExperienceController;
use App\Http\Controllers\Dashboard\MessageController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\SkillController;
use App\Http\Controllers\Dashboard\SocialLinkController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\ProfileController as BreezeProfileController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// CV Routes (Public)
Route::get('/cv/{username?}', [CVController::class, 'show'])->name('cv.show');
Route::get('/cv/{username}/download', [CVController::class, 'download'])->name('cv.download');
// Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ============================================================
// AUTHENTICATED ROUTES (Breeze)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// DASHBOARD ROUTES (Authenticated + Verified)
// ============================================================
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {

    // ── Overview ──────────────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // ── Profile & Settings ────────────────────────────────────────────
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Password edit route (if needed for Breeze)
    Route::get('/password/edit', [ProfileController::class, 'edit'])->name('password.edit');

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
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread');

    // ── Resume & Analytics (Future Pages) ───────────────────────────
    Route::view('/resume', 'dashboard.resume')->name('resume');
    Route::view('/analytics', 'dashboard.analytics')->name('analytics');
    Route::view('/clients', 'dashboard.clients')->name('clients');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});

// Include authentication routes (Breeze)
require __DIR__ . '/auth.php';
