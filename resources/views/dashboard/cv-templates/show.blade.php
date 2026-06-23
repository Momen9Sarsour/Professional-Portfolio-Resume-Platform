@extends('layouts.dashboard')

@section('title', $cvTemplate->name)
@section('page-title', $cvTemplate->name)
@section('page-subtitle', 'Template Details')

@push('styles')
    <style>
        .detail-card {
            background: white;
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e8edf5;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #7a869a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 14px;
            color: #1a2035;
            margin-bottom: 16px;
        }

        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-default {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-active {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .code-preview {
            background: #1e293b;
            border-radius: 12px;
            padding: 16px;
            overflow-x: auto;
            max-height: 400px;
        }

        .code-preview pre {
            margin: 0;
            color: #e2e8f0;
            font-family: 'Monaco', monospace;
            font-size: 12px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .btn-primary-dash {
            background: #2f7bff;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-light-dash {
            background: #f4f6fb;
            color: #1a2035;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1.5px solid #2f7bff;
            color: #2f7bff;
            padding: 8px 20px;
            border-radius: 12px;
            font-weight: 600;
        }

        .card-box {
            background: white;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e8edf5;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            {{-- Basic Info --}}
            <div class="detail-card">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h3 class="mb-0">{{ $cvTemplate->name }}</h3>
                    <div class="d-flex gap-2">
                        @if ($cvTemplate->is_default)
                            <span class="badge-status badge-default"><i class="bi bi-star-fill me-1"></i> Default</span>
                        @endif
                        @if ($cvTemplate->is_active)
                            <span class="badge-status badge-active"><i class="bi bi-eye-fill me-1"></i> Active</span>
                        @else
                            <span class="badge-status badge-inactive"><i class="bi bi-eye-slash-fill me-1"></i>
                                Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Slug / URL</div>
                        <div class="detail-value"><code>{{ $cvTemplate->slug }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Blade File</div>
                        <div class="detail-value"><code>cv-templates/{{ $cvTemplate->blade_file }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Sort Order</div>
                        <div class="detail-value">{{ $cvTemplate->sort_order }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Created By</div>
                        <div class="detail-value">{{ $cvTemplate->created_by ?? 'System' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">{{ $cvTemplate->created_at->format('F d, Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $cvTemplate->updated_at->diffForHumans() }}</div>
                    </div>
                    @if ($cvTemplate->description)
                        <div class="col-12">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">{{ $cvTemplate->description }}</div>
                        </div>
                    @endif
                    @if ($cvTemplate->preview_image)
                        <div class="col-12">
                            <div class="detail-label">Preview Image</div>
                            <img src="{{ asset('storage/' . $cvTemplate->preview_image) }}" alt="{{ $cvTemplate->name }}"
                                style="max-width: 300px; border-radius: 12px; border: 1px solid #e8edf5; margin-top: 8px;">
                        </div>
                    @endif
                </div>
            </div>

            {{-- HTML Code --}}
            <div class="detail-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-filetype-html me-2"></i> HTML Structure</h5>
                    <button class="btn-sm btn-outline-primary" onclick="copyCode('htmlCode')">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                </div>
                <div class="code-preview">
                    <pre id="htmlCode">{{ $cvTemplate->html_code }}</pre>
                </div>
            </div>

            {{-- CSS Code --}}
            @if ($cvTemplate->css_code)
                <div class="detail-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="bi bi-filetype-css me-2"></i> Custom CSS</h5>
                        <button class="btn-sm btn-outline-primary" onclick="copyCode('cssCode')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <div class="code-preview">
                        <pre id="cssCode">{{ $cvTemplate->css_code }}</pre>
                    </div>
                </div>
            @endif

            {{-- JavaScript Code --}}
            @if ($cvTemplate->js_code)
                <div class="detail-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="bi bi-filetype-js me-2"></i> Custom JavaScript</h5>
                        <button class="btn-sm btn-outline-primary" onclick="copyCode('jsCode')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <div class="code-preview">
                        <pre id="jsCode">{{ $cvTemplate->js_code }}</pre>
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between gap-3 mt-4">
                <a href="{{ route('dashboard.cv-templates.index') }}" class="btn-light-dash">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.cv-templates.preview', $cvTemplate) }}" target="_blank"
                        class="btn-outline-primary">
                        <i class="bi bi-eye-fill me-1"></i> Live Preview
                    </a>
                    <a href="{{ route('dashboard.cv-templates.edit', $cvTemplate) }}" class="btn-primary-dash">
                        <i class="bi bi-pencil-fill me-1"></i> Edit Template
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function copyCode(elementId) {
            var element = document.getElementById(elementId);
            var text = element.innerText;
            navigator.clipboard.writeText(text);

            // Show toast notification
            var toastContainer = document.getElementById('toast-container');
            if (toastContainer) {
                var toast = document.createElement('div');
                toast.className = 'toast-item show';
                toast.innerHTML = `
                <span class="toast-ico">📋</span>
                <div class="toast-txt"><p>Copied!</p><small>Code copied to clipboard</small></div>
                <button class="toast-x" onclick="this.parentElement.remove()">×</button>
            `;
                toastContainer.appendChild(toast);
                setTimeout(function() {
                    toast.remove();
                }, 2000);
            }
        }
    </script>
@endpush
