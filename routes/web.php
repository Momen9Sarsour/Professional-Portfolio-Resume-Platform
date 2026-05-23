<?php

use App\Http\Controllers\CVController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EducationController;
use App\Http\Controllers\Dashboard\ExperienceController;
use App\Http\Controllers\Dashboard\MessageController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\ResumeController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\SkillController;
use App\Http\Controllers\Dashboard\SocialLinkController;
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
    // Route::get('/resume', [ResumeController::class, 'index'])->name('resume.index');
    // Route::get('/resume/template/{template}', [ResumeController::class, 'show'])->name('resume.show');
    // Route::get('/resume/template/{template}/download', [ResumeController::class, 'download'])->name('resume.download');

    // ── Resume (CV Templates) ────────────────────────────────────────────
    Route::prefix('resume')->name('resume.')->group(function () {
        Route::get('/', [ResumeController::class, 'index'])->name('index');
        Route::post('/save-template', [ResumeController::class, 'saveTemplate'])->name('save-template');
        Route::get('/preview/{template?}', [ResumeController::class, 'preview'])->name('preview');
        Route::get('/download/{template?}', [ResumeController::class, 'download'])->name('download');
    });
    // Public CV view
    Route::get('/cv/{username?}', [ResumeController::class, 'publicView'])->name('cv.show');

    // ── Clients (User Management - Admin Only) ────────────────────────────
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{id}', [ClientController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClientController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-cv/{template?}', [ClientController::class, 'downloadCV'])->name('download-cv');
        Route::get('/{id}/preview-cv/{template?}', [ClientController::class, 'previewCV'])->name('preview-cv');
    });

    // ── Settings Routes ──────────────────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/update', [SettingsController::class, 'update'])->name('update');
        Route::delete('/reset', [SettingsController::class, 'reset'])->name('reset');
        Route::post('/test-email', [SettingsController::class, 'testEmail'])->name('test-email');
        Route::post('/preview-cv', [SettingsController::class, 'previewCV'])->name('preview-cv');
        Route::post('/download-preview', [SettingsController::class, 'downloadPreview'])->name('download-preview');
        Route::get('/cv-preview', [SettingsController::class, 'cvPreview'])->name('cv-preview');
        Route::post('/update-preview', [SettingsController::class, 'updatePreview'])->name('update-preview');
    });

    // ── Resume & Analytics (Future Pages) ───────────────────────────
    // Route::view('/resume', 'dashboard.resume')->name('resume');
    Route::view('/analytics', 'dashboard.analytics')->name('analytics');
    // Route::view('/clients', 'dashboard.clients')->name('clients');
    // Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});

// Include authentication routes (Breeze)
require __DIR__ . '/auth.php';
