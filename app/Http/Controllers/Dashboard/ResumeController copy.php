<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    /**
     * Display all available CV templates.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        // Get user's selected template or default to 'modern'
        $selectedTemplate = $user->cv_template ?? 'modern';

        // Define available templates
        $templates = [
            'modern' => [
                'name' => 'Modern',
                'description' => 'Clean and professional with a modern layout',
                'preview' => asset('images/templates/modern-preview.jpg'),
                'color' => '#2f7bff',
                'icon' => 'bi bi-layout-three-columns',
            ],
            'minimal' => [
                'name' => 'Minimal',
                'description' => 'Simple and elegant minimalist design',
                'preview' => asset('images/templates/minimal-preview.jpg'),
                'color' => '#1a2035',
                'icon' => 'bi bi-square',
            ],
            'creative' => [
                'name' => 'Creative',
                'description' => 'Bold and creative with accent colors',
                'preview' => asset('images/templates/creative-preview.jpg'),
                'color' => '#f97316',
                'icon' => 'bi bi-brush',
            ],
            'professional' => [
                'name' => 'Professional',
                'description' => 'Traditional professional layout for corporate roles',
                'preview' => asset('images/templates/professional-preview.jpg'),
                'color' => '#0f172a',
                'icon' => 'bi bi-briefcase',
            ],
            'sidebar' => [
                'name' => 'Sidebar',
                'description' => 'Two-column layout with sidebar for personal info',
                'preview' => asset('images/templates/sidebar-preview.jpg'),
                'color' => '#11998e',
                'icon' => 'bi bi-grid-1x2',
            ],
        ];

        return view('dashboard.resume.index', compact('templates', 'selectedTemplate', 'user', 'profile'));
    }

    /**
     * Save selected template preference.
     */
    public function saveTemplate(Request $request)
    {
        $request->validate([
            'template' => 'required|in:modern,minimal,creative,professional,sidebar',
        ]);

        $user = Auth::user();
        $user->cv_template = $request->template;
        $user->save();

        return redirect()
            ->route('dashboard.resume.preview', ['template' => $request->template])
            ->with('success', 'Template selected successfully!');
    }

    /**
     * Preview CV with selected template.
     */
    public function preview($template = null)
    {
        $user = Auth::user();

        // If no template specified, use saved template or default
        if (!$template) {
            $template = $user->cv_template ?? 'modern';
        }

        // Validate template exists
        $validTemplates = ['modern', 'minimal', 'creative', 'professional', 'sidebar'];
        if (!in_array($template, $validTemplates)) {
            $template = 'modern';
        }

        // Get user data
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks->where('is_active', true)->get();

        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');

        return view('dashboard.resume.templates.' . $template, compact(
            'user', 'profile', 'projects', 'skills', 'skillsByCategory',
            'experiences', 'education', 'socialLinks', 'template'
        ));
    }

    /**
     * Download CV as PDF.
     */
    public function download($template = null)
    {
        $user = Auth::user();

        // If no template specified, use saved template or default
        if (!$template) {
            $template = $user->cv_template ?? 'modern';
        }

        // Validate template exists
        $validTemplates = ['modern', 'minimal', 'creative', 'professional', 'sidebar'];
        if (!in_array($template, $validTemplates)) {
            $template = 'modern';
        }

        // Get user data
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks->where('is_active', true)->get();

        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');

        // Load PDF view
        $pdf = Pdf::loadView('dashboard.resume.templates.' . $template, compact(
            'user', 'profile', 'projects', 'skills', 'skillsByCategory',
            'experiences', 'education', 'socialLinks', 'template'
        ));

        // Configure PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('enable_php', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->download($user->name . '-CV-' . $template . '.pdf');
    }

    /**
     * Public view of CV (for visitors).
     */
    public function publicView($username = null)
    {
        if ($username) {
            $user = \App\Models\User::where('username', $username)->firstOrFail();
        } else {
            $user = Auth::user();
            if (!$user) {
                abort(404);
            }
        }

        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $skillsByCategory = $skills->groupBy('category');

        // Get user's selected template or default
        $template = $user->cv_template ?? 'modern';

        return view('dashboard.resume.templates.' . $template, compact(
            'user', 'profile', 'projects', 'skills', 'skillsByCategory',
            'experiences', 'education', 'socialLinks', 'template'
        ));
    }
}
