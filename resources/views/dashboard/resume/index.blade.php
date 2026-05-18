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
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .template-preview i {
        font-size: 80px;
        color: #2f7bff;
        opacity: 0.5;
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

    .preview-icon {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    @if($user->cv_template && $user->cv_template != 'modern')
        <a href="{{ route('dashboard.resume.preview', $user->cv_template) }}" class="btn-primary-dash">
            <i class="bi bi-eye-fill me-1"></i> View Current CV
        </a>
    @endif
</div>

<div class="row g-4 fade-up d1">
    @foreach($templates as $key => $template)
        <div class="col-lg-4 col-md-6">
            <div class="template-card {{ $selectedTemplate == $key ? 'selected' : '' }}">
                <div class="template-preview">
                    <div class="preview-icon" style="background: linear-gradient(135deg, {{ $template['color'] }} 0%, {{ $template['color'] }}88 100%);">
                        <i class="{{ $template['icon'] }}" style="font-size: 60px; color: white; opacity: 1;"></i>
                    </div>
                </div>
                <div class="template-info">
                    <h4 class="template-name">{{ $template['name'] }}</h4>
                    <p class="template-description">{{ $template['description'] }}</p>

                    @if($selectedTemplate == $key)
                        <span class="badge-current">
                            <i class="bi bi-check-circle-fill me-1"></i> Current Template
                        </span>
                    @else
                        <form action="{{ route('dashboard.resume.save-template') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template" value="{{ $key }}">
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
