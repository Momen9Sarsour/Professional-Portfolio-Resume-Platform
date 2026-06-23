<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CvTemplate;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    /**
     * Display all available CV templates from database.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        // Get user's selected template or default
        $selectedTemplateId = $user->cv_template_id ?? null;

        // Get all active templates from database
        $templates = CvTemplate::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('sort_order')
            ->get();

        // Get system templates for display
        $systemTemplates = CvTemplate::where('is_system', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get custom templates (added by admin)
        $customTemplates = CvTemplate::where('is_system', false)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $selectedTemplate = $user->cvTemplate ? $user->cvTemplate->slug : 'modern';

        return view('dashboard.resume.index', compact(
            'templates',
            'systemTemplates',
            'customTemplates',
            'selectedTemplate',
            'selectedTemplateId',
            'user',
            'profile'
        ));
    }

    /**
     * Save selected template preference.
     */
    public function saveTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:cv_templates,id',
        ]);

        $user = Auth::user();
        $user->cv_template_id = $request->template_id;
        $user->save();

        $template = CvTemplate::find($request->template_id);

        return redirect()
            ->route('dashboard.resume.preview', ['template' => $template->slug])
            ->with('success', 'Template "' . $template->name . '" selected successfully!');
    }

    /**
     * Preview CV with selected template.
     */
    public function preview($template = null)
    {
        $user = Auth::user();

        // If no template specified, use saved template or default
        if (!$template) {
            if ($user->cvTemplate) {
                $template = $user->cvTemplate->slug;
            } else {
                $default = CvTemplate::getDefaultTemplate();
                $template = $default ? $default->slug : 'modern';
            }
        }

        // Find template in database
        $cvTemplate = CvTemplate::where('slug', $template)->first();
        if (!$cvTemplate) {
            $default = CvTemplate::getDefaultTemplate();
            $template = $default ? $default->slug : 'modern';
            $cvTemplate = CvTemplate::where('slug', $template)->first();
        }

        // Get user data
         /** @var \App\Models\User $user */
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');

        // Determine view path
        $viewPath = $cvTemplate->getViewPath();

        return view('dashboard.'.$viewPath, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template'
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
            if ($user->cvTemplate) {
                $template = $user->cvTemplate->slug;
            } else {
                $default = CvTemplate::getDefaultTemplate();
                $template = $default ? $default->slug : 'modern';
            }
        }

        // Find template in database
        $cvTemplate = CvTemplate::where('slug', $template)->first();
        if (!$cvTemplate) {
            $default = CvTemplate::getDefaultTemplate();
            $template = $default ? $default->slug : 'modern';
            $cvTemplate = CvTemplate::where('slug', $template)->first();
        }

        // Get user data
         /** @var \App\Models\User $user */
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');

        $viewPath = $cvTemplate->getViewPath();

        // Load PDF view
        $pdf = Pdf::loadView($viewPath, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template'
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
        if ($user->cvTemplate) {
            $template = $user->cvTemplate->slug;
        } else {
            $default = CvTemplate::getDefaultTemplate();
            $template = $default ? $default->slug : 'modern';
        }

        $cvTemplate = CvTemplate::where('slug', $template)->first();
        $viewPath = $cvTemplate ? $cvTemplate->getViewPath() : 'cv-templates.modern';

        return view($viewPath, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template'
        ));
    }
}
