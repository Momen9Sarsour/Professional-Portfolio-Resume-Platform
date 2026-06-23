@extends('layouts.dashboard')

@section('title', 'Resume Templates')
@section('page-title', 'Resume Templates')
@section('page-subtitle', 'Choose your favorite CV design')

@push('styles')
<style>
    .template-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e8edf5;
        cursor: pointer;
    }

    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .template-card.selected {
        border: 2px solid #2f7bff;
        box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
    }

    .template-preview {
        position: relative;
        background: #f8fafc;
        padding: 20px;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .template-preview img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 12px;
    }

    .template-preview .no-image {
        text-align: center;
        color: #94a3b8;
    }

    .template-preview .no-image i {
        font-size: 60px;
        opacity: 0.4;
    }

    .template-info {
        padding: 20px;
        text-align: center;
    }

    .template-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a2035;
        margin-bottom: 8px;
    }

    .template-description {
        font-size: 13px;
        color: #7a869a;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .template-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .badge-system {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-custom {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-default {
        background: #d1fae5;
        color: #065f46;
    }

    .select-btn {
        background: #f4f6fb;
        color: #1a2035;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        width: 100%;
    }

    .select-btn:hover {
        background: #2f7bff;
        color: white;
    }

    .select-btn.active {
        background: #2f7bff;
        color: white;
    }

    .badge-current {
        display: inline-block;
        background: #d1fae5;
        color: #065f46;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
    }

    .section-divider {
        margin: 40px 0 24px;
        text-align: center;
        position: relative;
    }

    .section-divider span {
        background: #f0f2f8;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 700;
        color: #7a869a;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 1;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e8edf5;
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 fade-up">
    <div>
        <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
            Resume Templates
        </h5>
        <p style="color: #7a869a; font-size: 13px; margin-top: 4px;">Choose a design that represents you best</p>
    </div>
    @if($selectedTemplate && $selectedTemplate != 'modern')
        <a href="{{ route('dashboard.resume.preview', $selectedTemplate) }}" class="btn-primary-dash">
            <i class="bi bi-eye-fill me-1"></i> View Current CV
        </a>
    @endif
</div>

{{-- System Templates (Built-in) --}}
@if($systemTemplates->count() > 0)
    <div class="section-divider">
        <span><i class="bi bi-star-fill me-2" style="color: #f59e0b;"></i> Built-in Templates</span>
    </div>

    <div class="row g-4 fade-up d1">
        @foreach($systemTemplates as $template)
            <div class="col-lg-4 col-md-6">
                <div class="template-card {{ $selectedTemplate == $template->slug ? 'selected' : '' }}">
                    <div class="template-preview">
                        @if($template->preview_image && file_exists(storage_path('app/public/' . $template->preview_image)))
                            <img src="{{ asset('storage/' . $template->preview_image) }}" alt="{{ $template->name }}">
                        @else
                            <div class="no-image">
                                <i class="bi bi-image"></i>
                                <p>No preview</p>
                            </div>
                        @endif
                        <div style="position: absolute; top: 10px; right: 10px;">
                            @if($template->is_default)
                                <span class="template-badge badge-default">Default</span>
                            @endif
                            <span class="template-badge badge-system">System</span>
                        </div>
                    </div>
                    <div class="template-info">
                        <h4 class="template-name">{{ $template->name }}</h4>
                        <p class="template-description">{{ $template->description ?? 'No description' }}</p>

                        @if($selectedTemplate == $template->slug)
                            <span class="badge-current">
                                <i class="bi bi-check-circle-fill me-1"></i> Current Template
                            </span>
                        @else
                            <form action="{{ route('dashboard.resume.save-template') }}" method="POST">
                                @csrf
                                <input type="hidden" name="template_id" value="{{ $template->id }}">
                                <button type="submit" class="select-btn">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Select & Preview
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Custom Templates (Added by Admin) --}}
@if($customTemplates->count() > 0)
    <div class="section-divider">
        <span><i class="bi bi-plus-circle-fill me-2" style="color: #2f7bff;"></i> Custom Templates</span>
    </div>

    <div class="row g-4 fade-up d2">
        @foreach($customTemplates as $template)
            <div class="col-lg-4 col-md-6">
                <div class="template-card {{ $selectedTemplate == $template->slug ? 'selected' : '' }}">
                    <div class="template-preview">
                        @if($template->preview_image && file_exists(storage_path('app/public/' . $template->preview_image)))
                            <img src="{{ asset('storage/' . $template->preview_image) }}" alt="{{ $template->name }}">
                        @else
                            <div class="no-image">
                                <i class="bi bi-image"></i>
                                <p>No preview</p>
                            </div>
                        @endif
                        <div style="position: absolute; top: 10px; right: 10px;">
                            <span class="template-badge badge-custom">Custom</span>
                        </div>
                    </div>
                    <div class="template-info">
                        <h4 class="template-name">{{ $template->name }}</h4>
                        <p class="template-description">{{ $template->description ?? 'No description' }}</p>

                        @if($selectedTemplate == $template->slug)
                            <span class="badge-current">
                                <i class="bi bi-check-circle-fill me-1"></i> Current Template
                            </span>
                        @else
                            <form action="{{ route('dashboard.resume.save-template') }}" method="POST">
                                @csrf
                                <input type="hidden" name="template_id" value="{{ $template->id }}">
                                <button type="submit" class="select-btn">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Select & Preview
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($templates->count() == 0)
    <div class="empty-state text-center py-5">
        <i class="bi bi-layout-three-columns" style="font-size: 60px; color: #94a3b8; opacity: 0.4; display: block; margin-bottom: 16px;"></i>
        <p style="color: #7a869a;">No templates available. Please contact the administrator.</p>
    </div>
@endif

@if($selectedTemplate && $selectedTemplate != 'modern')
    <div class="mt-5 text-center fade-up d2">
        <a href="{{ route('dashboard.resume.preview', $selectedTemplate) }}" class="btn-primary-dash" style="padding: 12px 32px;">
            <i class="bi bi-eye-fill me-2"></i> View My CV
        </a>
        <a href="{{ route('dashboard.resume.download', $selectedTemplate) }}" class="btn-light-dash" style="padding: 12px 32px; margin-left: 12px;">
            <i class="bi bi-download me-2"></i> Download PDF
        </a>
    </div>
@endif

@endsection
