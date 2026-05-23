@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your application settings with live preview')

@push('styles')
<style>
    /* ============================================================
       SETTINGS PAGE STYLES
    ============================================================ */
    .settings-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 28px;
        border: 1px solid #e8edf5;
        transition: all 0.3s;
    }

    .settings-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .settings-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e8edf5;
    }

    .settings-icon {
        width: 52px;
        height: 52px;
        background: rgba(47, 123, 255, 0.1);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #2f7bff;
    }

    .settings-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a2035;
        margin: 0;
    }

    .settings-desc {
        font-size: 13px;
        color: #7a869a;
        margin: 4px 0 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a2035;
        margin-bottom: 8px;
        display: block;
    }

    .form-label i {
        margin-right: 6px;
        color: #2f7bff;
        font-size: 12px;
    }

    .form-control-custom {
        border: 1.5px solid #e8edf5;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13.5px;
        width: 100%;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #2f7bff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
    }

    textarea.form-control-custom {
        resize: vertical;
        min-height: 80px;
    }

    input[type="color"].form-control-custom {
        height: 50px;
        padding: 5px;
    }

    select.form-control-custom {
        cursor: pointer;
    }

    .color-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .color-preview {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 2px solid #e8edf5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .image-preview {
        margin-top: 12px;
        max-width: 120px;
        border-radius: 12px;
        border: 1px solid #e8edf5;
        padding: 8px;
        background: #f8fafc;
    }

    .image-preview img {
        width: 100%;
        border-radius: 8px;
    }

    .code-editor {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 12px;
        background: #1e293b;
        color: #e2e8f0;
    }

    /* ============================================================
       TAB STYLES
    ============================================================ */
    .tab-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 28px;
        background: white;
        padding: 8px;
        border-radius: 20px;
        border: 1px solid #e8edf5;
    }

    .tab-btn {
        padding: 10px 24px;
        border: none;
        background: transparent;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        color: #7a869a;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn.active {
        background: #2f7bff;
        color: white;
        box-shadow: 0 4px 12px rgba(47, 123, 255, 0.3);
    }

    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ============================================================
       LIVE PREVIEW SIDEBAR
    ============================================================ */
    .live-preview-sidebar {
        position: sticky;
        top: 90px;
    }

    .preview-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        border: 1px solid #e8edf5;
    }

    .preview-title {
        font-size: 14px;
        font-weight: 800;
        color: #1a2035;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8edf5;
    }

    .preview-title i {
        color: #2f7bff;
        font-size: 18px;
    }

    .dashboard-preview {
        background: #f0f2f8;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .preview-sidebar {
        background: var(--preview-sidebar-bg, #111827);
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
    }

    .preview-sidebar-item {
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 6px;
        color: #8b97b0;
        font-size: 11px;
    }

    .preview-sidebar-item.active {
        background: var(--preview-primary, #2f7bff);
        color: white;
    }

    .preview-header {
        background: var(--preview-header-bg, #1a2035);
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 12px;
        color: white;
        font-size: 12px;
    }

    .preview-content {
        background: white;
        border-radius: 10px;
        padding: 12px;
    }

    .preview-card-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px;
        margin-bottom: 6px;
        font-size: 11px;
    }

    /* ============================================================
       CV PREVIEW MODAL
    ============================================================ */
    .cv-preview-modal .modal-dialog {
        max-width: 90%;
        width: 900px;
    }

    .cv-preview-modal .modal-body {
        padding: 0;
        background: #f0f2f8;
    }

    .cv-preview-frame {
        width: 100%;
        height: 70vh;
        border: none;
        border-radius: 0 0 20px 20px;
    }

    /* ============================================================
       THEME TOGGLE
    ============================================================ */
    .theme-toggle-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 50px;
        width: fit-content;
    }

    .theme-toggle-btn {
        background: transparent;
        border: none;
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: #7a869a;
    }

    .theme-toggle-btn.active {
        background: #2f7bff;
        color: white;
        box-shadow: 0 2px 8px rgba(47,123,255,0.3);
    }

    .preview-badge {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 4px;
        margin-left: 8px;
        vertical-align: middle;
    }

    .help-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .reset-btn {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .reset-btn:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .btn-primary-dash {
        background: #2f7bff;
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-dash:hover {
        background: #1a5fcc;
        transform: translateY(-2px);
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e8edf5;
        transition: 0.3s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2f7bff;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
@endpush

@section('content')

<div class="row g-4">
    {{-- Main Settings Form --}}
    <div class="col-lg-8">
        <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
            @csrf
            @method('PUT')

            {{-- Tab Navigation --}}
            <div class="tab-buttons">
                @php
                    $sections = [
                        'general' => ['title' => 'General', 'icon' => 'bi bi-gear-fill', 'description' => 'Basic site configuration'],
                        'appearance' => ['title' => 'Appearance', 'icon' => 'bi bi-palette-fill', 'description' => 'Dashboard color scheme'],
                        'cv_theme' => ['title' => 'CV Theme', 'icon' => 'bi bi-file-earmark-person-fill', 'description' => 'Customize your CV appearance'],
                        'social' => ['title' => 'Social Links', 'icon' => 'bi bi-share-fill', 'description' => 'Your social media profiles'],
                        'email' => ['title' => 'Email', 'icon' => 'bi bi-envelope-fill', 'description' => 'SMTP configuration'],
                        'advanced' => ['title' => 'Advanced', 'icon' => 'bi bi-code-square', 'description' => 'Custom code and maintenance'],
                    ];
                @endphp
                @foreach($sections as $key => $section)
                    <button type="button" class="tab-btn {{ $loop->first ? 'active' : '' }}" data-tab="{{ $key }}">
                        <i class="{{ $section['icon'] }} me-2"></i>
                        {{ $section['title'] }}
                    </button>
                @endforeach
            </div>

            {{-- General Tab --}}
            <div class="tab-pane active" id="tab-general">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-gear-fill"></i></div>
                        <div>
                            <h3 class="settings-title">General Settings</h3>
                            <p class="settings-desc">Basic site configuration</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-building"></i> Site Name</label>
                                <input type="text" name="site_name" class="form-control-custom" value="{{ old('site_name', $settings['site_name'] ?? 'Mo\'men Sarsour CV') }}" placeholder="Enter site name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-tag"></i> Site Title</label>
                                <input type="text" name="site_title" class="form-control-custom" value="{{ old('site_title', $settings['site_title'] ?? 'Portfolio & CV') }}" placeholder="Enter site title">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-file-text"></i> Site Description</label>
                                <textarea name="site_description" class="form-control-custom" rows="2" placeholder="Describe your site">{{ old('site_description', $settings['site_description'] ?? 'Professional portfolio and CV management system') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-keywords"></i> SEO Keywords</label>
                                <input type="text" name="site_keywords" class="form-control-custom" value="{{ old('site_keywords', $settings['site_keywords'] ?? 'portfolio, cv, resume, laravel') }}" placeholder="Comma separated">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-envelope"></i> Footer Text</label>
                                <input type="text" name="footer_text" class="form-control-custom" value="{{ old('footer_text', $settings['footer_text'] ?? '© ' . date('Y') . ' Mo\'men Sarsour. All rights reserved.') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-image"></i> Site Logo</label>
                                <input type="file" name="site_logo" class="form-control-custom" accept="image/*">
                                @if(!empty($settings['site_logo']))
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-star"></i> Favicon</label>
                                <input type="file" name="site_favicon" class="form-control-custom" accept="image/*">
                                @if(!empty($settings['site_favicon']))
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appearance Tab --}}
            <div class="tab-pane" id="tab-appearance">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-palette-fill"></i></div>
                        <div>
                            <h3 class="settings-title">Appearance</h3>
                            <p class="settings-desc">Dashboard color scheme</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-droplet"></i> Primary Color</label>
                                <div class="color-group">
                                    <input type="color" name="primary_color" class="form-control-custom color-input" style="width: 70px;" value="{{ old('primary_color', $settings['primary_color'] ?? '#2f7bff') }}">
                                    <input type="text" class="form-control-custom color-text" value="{{ old('primary_color', $settings['primary_color'] ?? '#2f7bff') }}" readonly style="flex: 1;">
                                    <div class="color-preview color-preview-box" style="background: {{ old('primary_color', $settings['primary_color'] ?? '#2f7bff') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-palette"></i> Secondary Color</label>
                                <div class="color-group">
                                    <input type="color" name="secondary_color" class="form-control-custom color-input" style="width: 70px;" value="{{ old('secondary_color', $settings['secondary_color'] ?? '#1a2035') }}">
                                    <input type="text" class="form-control-custom color-text" value="{{ old('secondary_color', $settings['secondary_color'] ?? '#1a2035') }}" readonly style="flex: 1;">
                                    <div class="color-preview color-preview-box" style="background: {{ old('secondary_color', $settings['secondary_color'] ?? '#1a2035') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-brightness-high"></i> Accent Color</label>
                                <div class="color-group">
                                    <input type="color" name="accent_color" class="form-control-custom color-input" style="width: 70px;" value="{{ old('accent_color', $settings['accent_color'] ?? '#11998e') }}">
                                    <input type="text" class="form-control-custom color-text" value="{{ old('accent_color', $settings['accent_color'] ?? '#11998e') }}" readonly style="flex: 1;">
                                    <div class="color-preview color-preview-box" style="background: {{ old('accent_color', $settings['accent_color'] ?? '#11998e') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-layout-sidebar"></i> Sidebar Background</label>
                                <div class="color-group">
                                    <input type="color" name="sidebar_bg" class="form-control-custom color-input" style="width: 70px;" value="{{ old('sidebar_bg', $settings['sidebar_bg'] ?? '#111827') }}">
                                    <input type="text" class="form-control-custom color-text" value="{{ old('sidebar_bg', $settings['sidebar_bg'] ?? '#111827') }}" readonly style="flex: 1;">
                                    <div class="color-preview color-preview-box" style="background: {{ old('sidebar_bg', $settings['sidebar_bg'] ?? '#111827') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-window"></i> Default Theme</label>
                                <div class="theme-toggle-wrapper" id="themeToggleWrapper">
                                    <button type="button" class="theme-toggle-btn {{ ($settings['default_theme'] ?? 'light') == 'light' ? 'active' : '' }}" data-theme="light">
                                        <i class="bi bi-sun-fill me-1"></i> Light
                                    </button>
                                    <button type="button" class="theme-toggle-btn {{ ($settings['default_theme'] ?? 'light') == 'dark' ? 'active' : '' }}" data-theme="dark">
                                        <i class="bi bi-moon-fill me-1"></i> Dark
                                    </button>
                                </div>
                                <input type="hidden" name="default_theme" id="defaultThemeInput" value="{{ $settings['default_theme'] ?? 'light' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CV Theme Tab with Live Preview --}}
            <div class="tab-pane" id="tab-cv_theme">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-file-earmark-person-fill"></i></div>
                        <div>
                            <h3 class="settings-title">CV Theme Customizer</h3>
                            <p class="settings-desc">Customize your CV appearance with live preview</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-droplet"></i> CV Primary Color</label>
                                <div class="color-group">
                                    <input type="color" name="cv_primary" class="form-control-custom cv-color-input" style="width: 70px;" value="{{ old('cv_primary', $settings['cv_primary'] ?? '#2f7bff') }}">
                                    <input type="text" class="form-control-custom cv-color-text" value="{{ old('cv_primary', $settings['cv_primary'] ?? '#2f7bff') }}" readonly style="flex: 1;">
                                    <div class="color-preview cv-preview-box" style="background: {{ old('cv_primary', $settings['cv_primary'] ?? '#2f7bff') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-palette"></i> CV Secondary Color</label>
                                <div class="color-group">
                                    <input type="color" name="cv_secondary" class="form-control-custom cv-color-input" style="width: 70px;" value="{{ old('cv_secondary', $settings['cv_secondary'] ?? '#1a2035') }}">
                                    <input type="text" class="form-control-custom cv-color-text" value="{{ old('cv_secondary', $settings['cv_secondary'] ?? '#1a2035') }}" readonly style="flex: 1;">
                                    <div class="color-preview cv-preview-box" style="background: {{ old('cv_secondary', $settings['cv_secondary'] ?? '#1a2035') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-star-fill"></i> CV Accent Color</label>
                                <div class="color-group">
                                    <input type="color" name="cv_accent" class="form-control-custom cv-color-input" style="width: 70px;" value="{{ old('cv_accent', $settings['cv_accent'] ?? '#f59e0b') }}">
                                    <input type="text" class="form-control-custom cv-color-text" value="{{ old('cv_accent', $settings['cv_accent'] ?? '#f59e0b') }}" readonly style="flex: 1;">
                                    <div class="color-preview cv-preview-box" style="background: {{ old('cv_accent', $settings['cv_accent'] ?? '#f59e0b') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-type"></i> CV Text Color</label>
                                <div class="color-group">
                                    <input type="color" name="cv_text_color" class="form-control-custom cv-color-input" style="width: 70px;" value="{{ old('cv_text_color', $settings['cv_text_color'] ?? '#1e293b') }}">
                                    <input type="text" class="form-control-custom cv-color-text" value="{{ old('cv_text_color', $settings['cv_text_color'] ?? '#1e293b') }}" readonly style="flex: 1;">
                                    <div class="color-preview cv-preview-box" style="background: {{ old('cv_text_color', $settings['cv_text_color'] ?? '#1e293b') }};"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-layout-three-columns"></i> CV Header Style</label>
                                <select name="cv_header_style" class="form-control-custom cv-style-select">
                                    <option value="gradient-1" {{ (old('cv_header_style', $settings['cv_header_style'] ?? 'gradient-1') == 'gradient-1') ? 'selected' : '' }}>✨ Gradient Blue</option>
                                    <option value="gradient-2" {{ (old('cv_header_style', $settings['cv_header_style'] ?? '') == 'gradient-2') ? 'selected' : '' }}>💜 Gradient Purple</option>
                                    <option value="gradient-3" {{ (old('cv_header_style', $settings['cv_header_style'] ?? '') == 'gradient-3') ? 'selected' : '' }}>🧡 Gradient Orange</option>
                                    <option value="gradient-4" {{ (old('cv_header_style', $settings['cv_header_style'] ?? '') == 'gradient-4') ? 'selected' : '' }}>💚 Gradient Green</option>
                                    <option value="solid" {{ (old('cv_header_style', $settings['cv_header_style'] ?? '') == 'solid') ? 'selected' : '' }}>🎨 Solid Color</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label"><i class="bi bi-grid"></i> Default CV Layout</label>
                                <select name="cv_layout" class="form-control-custom">
                                    <option value="modern" {{ (old('cv_layout', $settings['cv_layout'] ?? 'modern') == 'modern') ? 'selected' : '' }}>Modern - Clean & Professional</option>
                                    <option value="minimal" {{ (old('cv_layout', $settings['cv_layout'] ?? '') == 'minimal') ? 'selected' : '' }}>Minimal - Simple & Elegant</option>
                                    <option value="creative" {{ (old('cv_layout', $settings['cv_layout'] ?? '') == 'creative') ? 'selected' : '' }}>Creative - Bold & Colorful</option>
                                    <option value="professional" {{ (old('cv_layout', $settings['cv_layout'] ?? '') == 'professional') ? 'selected' : '' }}>Professional - Classic</option>
                                    <option value="sidebar" {{ (old('cv_layout', $settings['cv_layout'] ?? '') == 'sidebar') ? 'selected' : '' }}>Sidebar - Two Column</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Live Preview Button --}}
                    <div class="mt-4 pt-3 border-top">
                        <button type="button" class="btn-primary-dash" onclick="openCVPreview()">
                            <i class="bi bi-eye-fill me-2"></i> Live Preview CV
                        </button>
                        <small class="help-text ms-3">Click to see how your CV will look with these colors</small>
                    </div>
                </div>
            </div>

            {{-- Social Links Tab --}}
            <div class="tab-pane" id="tab-social">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-share-fill"></i></div>
                        <div>
                            <h3 class="settings-title">Social Links</h3>
                            <p class="settings-desc">Your social media profiles</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-facebook"></i> Facebook</label><input type="url" name="facebook_url" class="form-control-custom" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/username"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-twitter-x"></i> Twitter / X</label><input type="url" name="twitter_url" class="form-control-custom" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}" placeholder="https://twitter.com/username"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-linkedin"></i> LinkedIn</label><input type="url" name="linkedin_url" class="form-control-custom" value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" placeholder="https://linkedin.com/in/username"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-github"></i> GitHub</label><input type="url" name="github_url" class="form-control-custom" value="{{ old('github_url', $settings['github_url'] ?? 'https://github.com/Momen9Sarsour') }}" placeholder="https://github.com/username"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-instagram"></i> Instagram</label><input type="url" name="instagram_url" class="form-control-custom" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/username"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-youtube"></i> YouTube</label><input type="url" name="youtube_url" class="form-control-custom" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/@username"></div></div>
                    </div>
                </div>
            </div>

            {{-- Email Tab --}}
            <div class="tab-pane" id="tab-email">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h3 class="settings-title">Email Settings</h3>
                            <p class="settings-desc">SMTP configuration</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-envelope"></i> Contact Email</label><input type="email" name="contact_email" class="form-control-custom" value="{{ old('contact_email', $settings['contact_email'] ?? 'momensarsour5@gmail.com') }}" placeholder="admin@example.com"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-server"></i> SMTP Host</label><input type="text" name="smtp_host" class="form-control-custom" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="smtp.gmail.com"></div></div>
                        <div class="col-md-4"><div class="form-group"><label class="form-label"><i class="bi bi-plug"></i> SMTP Port</label><input type="number" name="smtp_port" class="form-control-custom" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}" placeholder="587"></div></div>
                        <div class="col-md-4"><div class="form-group"><label class="form-label"><i class="bi bi-shield-lock"></i> SMTP Encryption</label><select name="smtp_encryption" class="form-control-custom"><option value="tls" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? 'tls') == 'tls') ? 'selected' : '' }}>TLS</option><option value="ssl" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option></select></div></div>
                        <div class="col-md-4"><div class="form-group"><label class="form-label"><i class="bi bi-person"></i> SMTP Username</label><input type="text" name="smtp_username" class="form-control-custom" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" placeholder="your-email@gmail.com"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-key"></i> SMTP Password</label><input type="password" name="smtp_password" class="form-control-custom" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}" placeholder="••••••••" autocomplete="off"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-send"></i> Test Email</label><button type="button" class="btn-outline-secondary w-100" onclick="openTestEmailModal()"><i class="bi bi-envelope-paper me-2"></i> Send Test Email</button></div></div>
                    </div>
                </div>
            </div>

            {{-- Advanced Tab --}}
            <div class="tab-pane" id="tab-advanced">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon"><i class="bi bi-code-square"></i></div>
                        <div>
                            <h3 class="settings-title">Advanced Settings</h3>
                            <p class="settings-desc">Custom code and maintenance</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12"><div class="form-group"><label class="form-label"><i class="bi bi-code-slash"></i> Custom CSS</label><textarea name="custom_css" class="form-control-custom code-editor" rows="6" placeholder="/* Add your custom CSS here */">{{ old('custom_css', $settings['custom_css'] ?? '') }}</textarea><div class="help-text">This CSS will be injected into the page header.</div></div></div>
                        <div class="col-12"><div class="form-group"><label class="form-label"><i class="bi bi-filetype-js"></i> Custom JavaScript</label><textarea name="custom_js" class="form-control-custom code-editor" rows="6" placeholder="// Add your custom JavaScript here">{{ old('custom_js', $settings['custom_js'] ?? '') }}</textarea><div class="help-text">This JavaScript will be injected before the closing body tag.</div></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-graph-up"></i> Google Analytics ID</label><input type="text" name="google_analytics_id" class="form-control-custom" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" placeholder="G-XXXXXXXXXX"></div></div>
                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="bi bi-tools"></i> Maintenance Mode</label><label style="display: flex; align-items: center; gap: 12px; cursor: pointer;"><div class="switch"><input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '' }}><span class="slider"></span></div><span>Enable Maintenance Mode</span></label></div></div>
                        <div class="col-12"><div class="form-group"><label class="form-label"><i class="bi bi-chat"></i> Maintenance Message</label><textarea name="maintenance_message" class="form-control-custom" rows="3" placeholder="Message for maintenance mode">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'We are currently updating our site. Please check back soon!') }}</textarea></div></div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center gap-3 mt-4" style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #e8edf5;">
                <div><button type="button" class="reset-btn" onclick="confirmReset()"><i class="bi bi-arrow-repeat me-2"></i> Reset to Default</button></div>
                <div><button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-2"></i> Save All Settings</button></div>
            </div>
        </form>
    </div>

    {{-- Live Preview Sidebar --}}
    <div class="col-lg-4">
        <div class="live-preview-sidebar">
            <div class="preview-card">
                <div class="preview-title">
                    <i class="bi bi-eye-fill"></i>
                    <span>Live Preview</span>
                </div>
                <div class="dashboard-preview" id="dashboardPreview">
                    <div class="preview-sidebar" id="previewSidebar">
                        <div class="preview-sidebar-item">Dashboard</div>
                        <div class="preview-sidebar-item active">Projects</div>
                        <div class="preview-sidebar-item">Skills</div>
                        <div class="preview-sidebar-item">Messages</div>
                    </div>
                    <div class="preview-header" id="previewHeader">
                        <i class="bi bi-house-fill me-2"></i> Dashboard / Overview
                    </div>
                    <div class="preview-content">
                        <div class="preview-card-item" style="background: var(--preview-primary, #2f7bff); color: white; padding: 10px;">Welcome back, Admin!</div>
                        <div class="preview-card-item">Total Projects: 12</div>
                        <div class="preview-card-item">Active Skills: 8</div>
                        <div class="preview-card-item">Messages: 3</div>
                    </div>
                </div>
                <div class="help-text text-center mt-2">Colors update in real-time as you change them</div>
            </div>
        </div>
    </div>
</div>

{{-- CV Preview Modal --}}
<div class="modal fade modal-dash cv-preview-modal" id="cvPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-person-fill me-2"></i>CV Live Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="cvPreviewFrame" class="cv-preview-frame" src="{{ route('dashboard.settings.cv-preview') }}" title="CV Preview"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-primary-dash" onclick="refreshCVPreview()">
                    <i class="bi bi-arrow-repeat me-1"></i> Refresh Preview
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Reset Modal --}}
<div class="modal fade modal-dash" id="resetModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2" style="color: #ef4444;"></i>Reset Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div style="font-size: 48px; margin-bottom: 16px; color: #ef4444;"><i class="bi bi-arrow-repeat"></i></div>
                <p style="font-size: 15px; color: #1a2035; margin-bottom: 8px;">Are you sure you want to reset all settings?</p>
                <p style="font-size: 13px; color: #7a869a;">This will restore all default values and cannot be undone.</p>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-2">
                <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('dashboard.settings.reset') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-dash"><i class="bi bi-check-lg me-1"></i> Yes, Reset All</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Test Email Modal --}}
<div class="modal fade modal-dash" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-envelope-paper-fill me-2"></i>Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dashboard.settings.test-email') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Send a test email to verify your SMTP settings.</p>
                    <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="test_email" class="form-control-custom" placeholder="your-email@example.com" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-dash"><i class="bi bi-send me-1"></i> Send Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ============================================================
    // TAB SWITCHING
    // ============================================================
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tab-' + this.getAttribute('data-tab')).classList.add('active');
            localStorage.setItem('activeSettingsTab', this.getAttribute('data-tab'));
        });
    });

    const savedTab = localStorage.getItem('activeSettingsTab');
    if (savedTab) {
        const tabBtn = document.querySelector(`.tab-btn[data-tab="${savedTab}"]`);
        if (tabBtn) tabBtn.click();
    }

    // ============================================================
    // LIVE COLOR PREVIEW - Dashboard
    // ============================================================
    function updateLivePreview() {
        const primaryColor = document.querySelector('input[name="primary_color"]')?.value || '#2f7bff';
        const secondaryColor = document.querySelector('input[name="secondary_color"]')?.value || '#1a2035';
        const sidebarBg = document.querySelector('input[name="sidebar_bg"]')?.value || '#111827';

        document.documentElement.style.setProperty('--preview-primary', primaryColor);
        document.documentElement.style.setProperty('--preview-sidebar-bg', sidebarBg);
        document.documentElement.style.setProperty('--preview-header-bg', secondaryColor);

        const previewSidebar = document.getElementById('previewSidebar');
        const previewHeader = document.getElementById('previewHeader');
        if (previewSidebar) previewSidebar.style.background = sidebarBg;
        if (previewHeader) previewHeader.style.background = secondaryColor;

        const activeItem = document.querySelector('.preview-sidebar-item.active');
        if (activeItem) activeItem.style.background = primaryColor;

        const primaryCard = document.querySelector('.preview-card-item[style*="background"]');
        if (primaryCard) primaryCard.style.background = primaryColor;
    }

    document.querySelectorAll('.color-input').forEach(input => {
        input.addEventListener('input', function() {
            const group = this.closest('.color-group');
            if (group) {
                const textInput = group.querySelector('.color-text');
                const preview = group.querySelector('.color-preview-box');
                if (textInput) textInput.value = this.value;
                if (preview) preview.style.background = this.value;
            }
            updateLivePreview();
        });
    });

    // ============================================================
    // CV COLOR PREVIEW - Update colors for CV preview
    // ============================================================
    function updateCVColors() {
        const cvColors = {
            cv_primary: document.querySelector('input[name="cv_primary"]')?.value || '#2f7bff',
            cv_secondary: document.querySelector('input[name="cv_secondary"]')?.value || '#1a2035',
            cv_accent: document.querySelector('input[name="cv_accent"]')?.value || '#f59e0b',
            cv_text_color: document.querySelector('input[name="cv_text_color"]')?.value || '#1e293b',
            cv_header_style: document.querySelector('select[name="cv_header_style"]')?.value || 'gradient-1',
            cv_layout: document.querySelector('select[name="cv_layout"]')?.value || 'modern'
        };

        sessionStorage.setItem('cvPreviewColors', JSON.stringify(cvColors));

        // Refresh iframe if open
        const iframe = document.getElementById('cvPreviewFrame');
        if (iframe && iframe.src && iframe.style.display !== 'none') {
            refreshCVPreview();
        }
    }

    document.querySelectorAll('.cv-color-input, .cv-style-select, select[name="cv_layout"]').forEach(input => {
        input.addEventListener('input', updateCVColors);
        input.addEventListener('change', updateCVColors);
    });

    // ============================================================
    // THEME TOGGLE (Light/Dark)
    // ============================================================
    document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.theme-toggle-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const theme = this.getAttribute('data-theme');
            document.getElementById('defaultThemeInput').value = theme;

            // Preview theme change
            if (theme === 'dark') {
                document.body.style.background = '#0f172a';
                document.querySelectorAll('.settings-card').forEach(card => {
                    card.style.background = '#1e293b';
                    card.style.borderColor = '#334155';
                });
                document.querySelector('.tab-buttons').style.background = '#1e293b';
                document.querySelector('.tab-buttons').style.borderColor = '#334155';
                document.querySelector('.preview-card').style.background = '#1e293b';
                document.querySelector('.preview-card').style.borderColor = '#334155';
                document.querySelector('.dashboard-preview').style.background = '#0f172a';
                document.querySelector('.preview-content').style.background = '#1e293b';
                document.querySelector('.preview-content .preview-card-item:first-child').style.background = '#334155';
            } else {
                document.body.style.background = '#f0f2f8';
                document.querySelectorAll('.settings-card').forEach(card => {
                    card.style.background = 'white';
                    card.style.borderColor = '#e8edf5';
                });
                document.querySelector('.tab-buttons').style.background = 'white';
                document.querySelector('.tab-buttons').style.borderColor = '#e8edf5';
                document.querySelector('.preview-card').style.background = 'white';
                document.querySelector('.preview-card').style.borderColor = '#e8edf5';
                document.querySelector('.dashboard-preview').style.background = '#f0f2f8';
                document.querySelector('.preview-content').style.background = 'white';
                document.querySelector('.preview-content .preview-card-item:first-child').style.background = 'var(--preview-primary, #2f7bff)';
            }
        });
    });

    // ============================================================
    // MODAL FUNCTIONS
    // ============================================================
    function openCVPreview() {
        updateCVColors();
        const iframe = document.getElementById('cvPreviewFrame');
        iframe.src = "{{ route('dashboard.settings.cv-preview') }}?t=" + Date.now();
        new bootstrap.Modal(document.getElementById('cvPreviewModal')).show();
    }

    function refreshCVPreview() {
        const iframe = document.getElementById('cvPreviewFrame');
        iframe.src = "{{ route('dashboard.settings.cv-preview') }}?t=" + Date.now();
    }

    function confirmReset() {
        new bootstrap.Modal(document.getElementById('resetModal')).show();
    }

    function openTestEmailModal() {
        new bootstrap.Modal(document.getElementById('testEmailModal')).show();
    }

    // Initialize live preview on load
    document.addEventListener('DOMContentLoaded', function() {
        updateLivePreview();
        updateCVColors();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-dash').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
@endpush
