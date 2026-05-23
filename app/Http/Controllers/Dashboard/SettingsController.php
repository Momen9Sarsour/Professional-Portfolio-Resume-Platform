<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $sections = [
            'general' => [
                'title' => 'General Settings',
                'icon' => 'bi bi-gear-fill',
                'description' => 'Basic site configuration',
                'fields' => [
                    'site_name' => ['label' => 'Site Name', 'type' => 'text', 'default' => 'Mo\'men Sarsour CV', 'placeholder' => 'Enter site name'],
                    'site_title' => ['label' => 'Site Title', 'type' => 'text', 'default' => 'Portfolio & CV', 'placeholder' => 'Enter site title'],
                    'site_description' => ['label' => 'Site Description', 'type' => 'textarea', 'default' => 'Professional portfolio and CV management system', 'placeholder' => 'Describe your site'],
                    'site_keywords' => ['label' => 'SEO Keywords', 'type' => 'text', 'default' => 'portfolio, cv, resume, laravel', 'placeholder' => 'Comma separated keywords'],
                    'site_logo' => ['label' => 'Site Logo', 'type' => 'image', 'default' => null],
                    'site_favicon' => ['label' => 'Favicon', 'type' => 'image', 'default' => null],
                    'footer_text' => ['label' => 'Footer Text', 'type' => 'text', 'default' => '© ' . date('Y') . ' Mo\'men Sarsour. All rights reserved.', 'placeholder' => 'Footer copyright text'],
                ]
            ],
            'appearance' => [
                'title' => 'Appearance',
                'icon' => 'bi bi-palette-fill',
                'description' => 'Dashboard color scheme',
                'fields' => [
                    'primary_color' => ['label' => 'Primary Color', 'type' => 'color', 'default' => '#2f7bff'],
                    'secondary_color' => ['label' => 'Secondary Color', 'type' => 'color', 'default' => '#1a2035'],
                    'accent_color' => ['label' => 'Accent Color', 'type' => 'color', 'default' => '#11998e'],
                    'sidebar_bg' => ['label' => 'Sidebar Background', 'type' => 'color', 'default' => '#111827'],
                    'header_bg' => ['label' => 'Header Background', 'type' => 'color', 'default' => '#1a2035'],
                    'default_theme' => ['label' => 'Default Theme', 'type' => 'select', 'options' => ['light' => 'Light', 'dark' => 'Dark'], 'default' => 'light'],
                ]
            ],
            'cv_theme' => [
                'title' => 'CV Theme Customizer',
                'icon' => 'bi bi-file-earmark-person-fill',
                'description' => 'Customize your CV appearance',
                'fields' => [
                    'cv_primary' => ['label' => 'CV Primary Color', 'type' => 'color', 'default' => '#2f7bff'],
                    'cv_secondary' => ['label' => 'CV Secondary Color', 'type' => 'color', 'default' => '#1a2035'],
                    'cv_accent' => ['label' => 'CV Accent Color', 'type' => 'color', 'default' => '#f59e0b'],
                    'cv_text_color' => ['label' => 'CV Text Color', 'type' => 'color', 'default' => '#1e293b'],
                    'cv_header_style' => ['label' => 'CV Header Style', 'type' => 'select', 'options' => [
                        'gradient-1' => 'Gradient Blue',
                        'gradient-2' => 'Gradient Purple',
                        'gradient-3' => 'Gradient Orange',
                        'gradient-4' => 'Gradient Green',
                        'solid' => 'Solid Color'
                    ], 'default' => 'gradient-1'],
                    'cv_layout' => ['label' => 'CV Layout', 'type' => 'select', 'options' => [
                        'modern' => 'Modern',
                        'minimal' => 'Minimal',
                        'creative' => 'Creative',
                        'professional' => 'Professional',
                        'sidebar' => 'Sidebar'
                    ], 'default' => 'modern'],
                ]
            ],
            'social' => [
                'title' => 'Social Links',
                'icon' => 'bi bi-share-fill',
                'description' => 'Your social media profiles',
                'fields' => [
                    'facebook_url' => ['label' => 'Facebook', 'type' => 'url', 'default' => null, 'placeholder' => 'https://facebook.com/username'],
                    'twitter_url' => ['label' => 'Twitter / X', 'type' => 'url', 'default' => null, 'placeholder' => 'https://twitter.com/username'],
                    'linkedin_url' => ['label' => 'LinkedIn', 'type' => 'url', 'default' => null, 'placeholder' => 'https://linkedin.com/in/username'],
                    'github_url' => ['label' => 'GitHub', 'type' => 'url', 'default' => 'https://github.com/Momen9Sarsour', 'placeholder' => 'https://github.com/username'],
                    'instagram_url' => ['label' => 'Instagram', 'type' => 'url', 'default' => null, 'placeholder' => 'https://instagram.com/username'],
                    'youtube_url' => ['label' => 'YouTube', 'type' => 'url', 'default' => null, 'placeholder' => 'https://youtube.com/@username'],
                ]
            ],
            'email' => [
                'title' => 'Email Settings',
                'icon' => 'bi bi-envelope-fill',
                'description' => 'SMTP and contact configuration',
                'fields' => [
                    'contact_email' => ['label' => 'Contact Email', 'type' => 'email', 'default' => 'momensarsour5@gmail.com', 'placeholder' => 'admin@example.com'],
                    'smtp_host' => ['label' => 'SMTP Host', 'type' => 'text', 'default' => null, 'placeholder' => 'smtp.gmail.com'],
                    'smtp_port' => ['label' => 'SMTP Port', 'type' => 'number', 'default' => null, 'placeholder' => '587'],
                    'smtp_encryption' => ['label' => 'SMTP Encryption', 'type' => 'select', 'options' => ['tls' => 'TLS', 'ssl' => 'SSL'], 'default' => 'tls'],
                    'smtp_username' => ['label' => 'SMTP Username', 'type' => 'text', 'default' => null, 'placeholder' => 'your-email@gmail.com'],
                    'smtp_password' => ['label' => 'SMTP Password', 'type' => 'password', 'default' => null, 'placeholder' => '••••••••'],
                ]
            ],
            'advanced' => [
                'title' => 'Advanced Settings',
                'icon' => 'bi bi-code-square',
                'description' => 'Custom code and maintenance',
                'fields' => [
                    'custom_css' => ['label' => 'Custom CSS', 'type' => 'code', 'default' => null, 'placeholder' => '/* Add your custom CSS here */'],
                    'custom_js' => ['label' => 'Custom JavaScript', 'type' => 'code', 'default' => null, 'placeholder' => '// Add your custom JavaScript here'],
                    'google_analytics_id' => ['label' => 'Google Analytics ID', 'type' => 'text', 'default' => null, 'placeholder' => 'G-XXXXXXXXXX'],
                    'maintenance_mode' => ['label' => 'Maintenance Mode', 'type' => 'checkbox', 'default' => false],
                    'maintenance_message' => ['label' => 'Maintenance Message', 'type' => 'textarea', 'default' => 'We are currently updating our site. Please check back soon!', 'placeholder' => 'Message for maintenance mode'],
                ]
            ],
        ];

        return view('dashboard.settings.index', compact('settings', 'sections'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $setting = Setting::firstOrCreate(['key' => $key]);

            // Handle checkbox values
            if ($setting->type === 'checkbox' || (isset($sections['advanced']['fields'][$key]['type']) && $sections['advanced']['fields'][$key]['type'] === 'checkbox')) {
                $value = $request->has($key) ? '1' : '0';
            }

            // Handle file upload
            if ($request->hasFile($key)) {
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
                $path = $request->file($key)->store('settings', 'public');
                $value = $path;
            }

            $setting->value = $value;
            $setting->save();
        }

        // Clear cache to apply changes
        Cache::forget('site_settings');

        // Handle maintenance mode
        if ($request->has('maintenance_mode')) {
            if ($request->maintenance_mode == '1') {
                Artisan::call('down', ['--secret' => 'secret-key-123']);
            } else {
                Artisan::call('up');
            }
        }

        return redirect()
            ->route('dashboard.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function reset()
    {
        Setting::truncate();
        Cache::forget('site_settings');

        // Turn off maintenance mode if on
        Artisan::call('up');

        return redirect()
            ->route('dashboard.settings.index')
            ->with('success', 'Settings reset to default!');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email from your CV system.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('Test Email from CV System');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function previewCV(Request $request)
    {
        // Get all settings from request
        $settings = $request->except(['_token', '_method', 'preview', 'download']);

        // Get current user data for preview
        $user = Auth::user()->id;
        // $user = auth()->user();
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->get();
        $skills = $user->skills()->where('is_active', true)->get();
        $experiences = $user->experiences()->where('is_active', true)->get();
        $education = $user->education()->where('is_active', true)->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $template = $settings['cv_layout'] ?? $user->cv_template ?? 'modern';
        $skillsByCategory = $skills->groupBy('category');

        $html = view('dashboard.resume.templates.' . $template, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template',
            'settings'
        ))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function downloadPreview(Request $request)
    {
        $settings = $request->except(['_token', '_method', 'preview', 'download']);

        $user = Auth::user()->id;
        // $user = auth()->user();
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->get();
        $skills = $user->skills()->where('is_active', true)->get();
        $experiences = $user->experiences()->where('is_active', true)->get();
        $education = $user->education()->where('is_active', true)->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $template = $settings['cv_layout'] ?? $user->cv_template ?? 'modern';
        $skillsByCategory = $skills->groupBy('category');

        $pdf = Pdf::loadView('dashboard.resume.templates.' . $template, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template',
            'settings'
        ));

        return $pdf->download('CV-Preview.pdf');
    }

    public function cvPreview()
    {
        // Get current user or first user for preview
        // dd(Auth::user()->id);
        $user = Auth::user()->id ?? \App\Models\User::first();
        $profile = $user->profile ?? new \App\Models\Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();
        $skillsByCategory = $skills->groupBy('category');

        // Get preview colors from session or settings
        $cvColors = session('cv_preview_colors', []);

        $template = $cvColors['cv_layout'] ?? ($user->cv_template ?? 'modern');

        return view('dashboard.resume.templates.' . $template, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template',
            'cvColors'
        ));
    }
    

    public function updatePreview(Request $request)
    {
        session(['cv_preview_colors' => $request->all()]);
        return response()->json(['success' => true]);
    }
}
