@extends('layouts.dashboard')

@section('title', 'Edit ' . $cvTemplate->name)
@section('page-title', 'Edit CV Template')
@section('page-subtitle', 'Modify your custom CV template')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">

<style>
    .editor-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e8edf5;
        margin-bottom: 20px;
    }

    .editor-header {
        background: #1e293b;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
        font-size: 12px;
        font-weight: 600;
    }

    .CodeMirror {
        height: 400px;
        font-size: 13px;
    }

    .variables-sidebar {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    .var-group {
        margin-bottom: 24px;
    }

    .var-group-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a2035;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8edf5;
    }

    .var-item {
        margin-bottom: 12px;
        padding: 8px;
        background: white;
        border-radius: 10px;
        border: 1px solid #e8edf5;
    }

    .var-item:hover {
        border-color: #2f7bff;
    }

    .var-code {
        font-family: monospace;
        font-size: 12px;
        background: #1e293b;
        color: #e2e8f0;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 6px;
    }

    .insert-var-btn {
        background: none;
        border: none;
        color: #2f7bff;
        cursor: pointer;
        font-size: 11px;
        margin-top: 6px;
    }

    .preview-frame {
        width: 100%;
        height: 500px;
        border: 1px solid #e8edf5;
        border-radius: 16px;
    }

    .image-preview {
        margin-top: 12px;
        max-width: 200px;
        border-radius: 12px;
        border: 1px solid #e8edf5;
        padding: 8px;
    }

    .image-preview img {
        width: 100%;
        border-radius: 8px;
    }

    .nav-tabs-custom {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid #e8edf5;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .nav-tab-custom {
        padding: 8px 20px;
        background: transparent;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        color: #7a869a;
        cursor: pointer;
    }

    .nav-tab-custom.active {
        background: #2f7bff;
        color: white;
    }

    .tab-pane-custom {
        display: none;
    }

    .tab-pane-custom.active {
        display: block;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
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

    .help-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .form-section {
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid #e8edf5;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a2035;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-label-dash {
        font-size: 13px;
        font-weight: 600;
        color: #1a2035;
        margin-bottom: 6px;
    }

    .form-control-dash {
        border: 1.5px solid #e8edf5;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13.5px;
        width: 100%;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e8edf5;
    }
</style>
@endpush

@section('content')

<div class="row g-4">

    <div class="col-lg-8">

        <div class="card-box">

            {{-- Status Badges --}}
            <div class="mb-4 d-flex gap-2">

                @if($cvTemplate->is_default)
                    <span class="badge-status badge-default">
                        <i class="bi bi-star-fill me-1"></i>
                        Default Template
                    </span>
                @endif

                @if($cvTemplate->is_active)
                    <span class="badge-status badge-active">
                        <i class="bi bi-eye-fill me-1"></i>
                        Active
                    </span>
                @else
                    <span class="badge-status badge-inactive">
                        <i class="bi bi-eye-slash-fill me-1"></i>
                        Inactive
                    </span>
                @endif

            </div>

            <form action="{{ route('dashboard.cv-templates.update', $cvTemplate) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="templateForm">

                @csrf
                @method('PUT')

                {{-- Basic Info --}}
                <div class="form-section">

                    <div class="section-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Basic Information</span>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label-dash">Template Name *</label>

                            <input type="text"
                                   name="name"
                                   class="form-control-dash"
                                   value="{{ old('name', $cvTemplate->name) }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-dash">Sort Order</label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control-dash"
                                   value="{{ old('sort_order', $cvTemplate->sort_order) }}"
                                   min="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label-dash">Description</label>

                            <textarea name="description"
                                      class="form-control-dash"
                                      rows="2">{{ old('description', $cvTemplate->description) }}</textarea>
                        </div>

                        <div class="col-md-6">

                            <label class="form-label-dash">Current Image</label>

                            @if($cvTemplate->preview_image)
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $cvTemplate->preview_image) }}"
                                         alt="Current">
                                </div>
                            @else
                                <p class="text-muted">No image uploaded</p>
                            @endif

                            <label class="form-label-dash mt-2">
                                Change Image
                            </label>

                            <input type="file"
                                   name="preview_image"
                                   class="form-control-dash"
                                   accept="image/*"
                                   id="previewImageInput">

                            <div class="image-preview"
                                 id="newImagePreview"
                                 style="display:none;">

                                <img id="previewImg"
                                     src=""
                                     alt="New Preview">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="d-flex gap-4 mt-4">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           {{ $cvTemplate->is_active ? 'checked' : '' }}>

                                    Active
                                </label>

                                @if(!$cvTemplate->is_default)
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox"
                                               name="is_default"
                                               value="1">

                                        Set as Default
                                    </label>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Code Tabs --}}
                <div class="form-section">

                    <div class="section-title">
                        <i class="bi bi-code-square"></i>
                        <span>Template Code</span>
                    </div>

                    <div class="nav-tabs-custom">

                        <button type="button"
                                class="nav-tab-custom active"
                                data-tab="html">
                            HTML
                        </button>

                        <button type="button"
                                class="nav-tab-custom"
                                data-tab="css">
                            CSS
                        </button>

                        <button type="button"
                                class="nav-tab-custom"
                                data-tab="js">
                            JavaScript
                        </button>

                    </div>

                    {{-- HTML --}}
                    <div id="tab-html" class="tab-pane-custom active">

                        <div class="editor-wrapper">

                            <div class="editor-header">
                                <span>
                                    <i class="bi bi-filetype-html"></i>
                                    HTML Structure
                                </span>
                            </div>

                            <textarea id="htmlEditor"
                                      name="html_code"
                                      class="code-editor">{{ old('html_code', $cvTemplate->html_code) }}</textarea>

                        </div>

                    </div>

                    {{-- CSS --}}
                    <div id="tab-css" class="tab-pane-custom">

                        <div class="editor-wrapper">

                            <div class="editor-header">
                                <span>
                                    <i class="bi bi-filetype-css"></i>
                                    Custom CSS
                                </span>
                            </div>

                            <textarea id="cssEditor"
                                      name="css_code"
                                      class="code-editor">{{ old('css_code', $cvTemplate->css_code) }}</textarea>

                        </div>

                    </div>

                    {{-- JS --}}
                    <div id="tab-js" class="tab-pane-custom">

                        <div class="editor-wrapper">

                            <div class="editor-header">
                                <span>
                                    <i class="bi bi-filetype-js"></i>
                                    Custom JavaScript
                                </span>
                            </div>

                            <textarea id="jsEditor"
                                      name="js_code"
                                      class="code-editor">{{ old('js_code', $cvTemplate->js_code) }}</textarea>

                        </div>

                    </div>

                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-3 mt-4">

                    <a href="{{ route('dashboard.cv-templates.index') }}"
                       class="btn-light-dash">
                        Cancel
                    </a>

                    <button type="button"
                            class="btn-outline-primary"
                            onclick="openPreview()">

                        <i class="bi bi-eye-fill me-1"></i>
                        Preview

                    </button>

                    <button type="submit"
                            class="btn-primary-dash">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Template

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- Variables Sidebar --}}
    <div class="col-lg-4">

        <div class="variables-sidebar">

            <h6 class="mb-3">
                <i class="bi bi-database-fill me-2"></i>
                Available Variables
            </h6>

            {{-- User Variables --}}
            <div class="var-group">

                <div class="var-group-title">
                    User
                </div>

                <div class="var-item">
                    <div class="var-code">@{{ $user->name }}</div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@{{ $user->name }}')">

                        Insert

                    </button>
                </div>

                <div class="var-item">
                    <div class="var-code">@{{ $user->email }}</div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@{{ $user->email }}')">

                        Insert

                    </button>
                </div>

            </div>

            {{-- Profile --}}
            <div class="var-group">

                <div class="var-group-title">
                    Profile
                </div>

                <div class="var-item">
                    <div class="var-code">@{{ $profile->title }}</div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@{{ $profile->title }}')">

                        Insert

                    </button>
                </div>

                <div class="var-item">
                    <div class="var-code">@{{ $profile->bio }}</div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@{{ $profile->bio }}')">

                        Insert

                    </button>
                </div>

            </div>

            {{-- Projects --}}
            <div class="var-group">

                <div class="var-group-title">
                    Projects
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($projects as $project)
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($projects as $project)\n<h3>{{ $project->title }}</h3>\n<p>{{ $project->description }}</p>\n@endforeach')">

                        Insert Loop

                    </button>

                </div>
                @endverbatim

            </div>

            {{-- Skills --}}
            <div class="var-group">

                <div class="var-group-title">
                    Skills
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($skills as $skill)
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($skills as $skill)\n<span>{{ $skill->name }} ({{ $skill->level }}%)</span>\n@endforeach')">

                        Insert Loop

                    </button>

                </div>
                @endverbatim

            </div>

            {{-- Experiences --}}
            <div class="var-group">

                <div class="var-group-title">
                    Experiences
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($experiences as $exp)
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($experiences as $exp)\n<div>{{ $exp->job_title }} at {{ $exp->company }}</div>\n@endforeach')">

                        Insert Loop

                    </button>

                </div>
                @endverbatim

            </div>

        </div>

    </div>

</div>

{{-- Preview Modal --}}
<div class="modal fade modal-dash"
     id="previewModal"
     tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-eye-fill me-2"></i>
                    Template Preview: {{ $cvTemplate->name }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body p-0">
                <iframe id="previewFrame"
                        class="preview-frame"
                        title="Template Preview"></iframe>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>

<script>

    var htmlEditor = CodeMirror.fromTextArea(
        document.getElementById("htmlEditor"),
        {
            mode: "xml",
            theme: "dracula",
            lineNumbers: true,
            autoCloseTags: true,
            matchBrackets: true,
            viewportMargin: Infinity
        }
    );

    var cssEditor = CodeMirror.fromTextArea(
        document.getElementById("cssEditor"),
        {
            mode: "css",
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            viewportMargin: Infinity
        }
    );

    var jsEditor = CodeMirror.fromTextArea(
        document.getElementById("jsEditor"),
        {
            mode: "javascript",
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            viewportMargin: Infinity
        }
    );

    document.querySelectorAll('.nav-tab-custom').forEach(function(tab) {

        tab.addEventListener('click', function() {

            document.querySelectorAll('.nav-tab-custom').forEach(function(t) {
                t.classList.remove('active');
            });

            document.querySelectorAll('.tab-pane-custom').forEach(function(pane) {
                pane.classList.remove('active');
            });

            this.classList.add('active');

            var tabId = this.getAttribute('data-tab');

            document.getElementById('tab-' + tabId)
                    .classList.add('active');

            htmlEditor.refresh();
            cssEditor.refresh();
            jsEditor.refresh();

        });

    });

    function insertCode(code) {

        var activeTab = document.querySelector('.nav-tab-custom.active')
                                .getAttribute('data-tab');

        var editor;

        if (activeTab === 'html') {
            editor = htmlEditor;
        } else if (activeTab === 'css') {
            editor = cssEditor;
        } else {
            editor = jsEditor;
        }

        var cursor = editor.getCursor();

        editor.replaceRange(code, cursor);

        editor.focus();

    }

    function openPreview() {

        var modal = new bootstrap.Modal(
            document.getElementById('previewModal')
        );

        modal.show();

        setTimeout(function () {
            refreshPreview();
        }, 500);

    }

    function refreshPreview() {

        var html = htmlEditor.getValue();
        var css = cssEditor.getValue();
        var js = jsEditor.getValue();

        var fullHtml =
            '<!DOCTYPE html>' +
            '<html>' +
            '<head>' +
            '<meta charset="UTF-8">' +
            '<title>Preview</title>' +
            '<style>' + css + '</style>' +
            '</head>' +
            '<body>' +
            html +
            '<script>' + js + '<\/script>' +
            '</body>' +
            '</html>';

        document.getElementById('previewFrame').srcdoc = fullHtml;

    }

    var previewImageInput =
        document.getElementById('previewImageInput');

    if (previewImageInput) {

        previewImageInput.addEventListener('change', function(e) {

            var file = e.target.files[0];

            if (file) {

                var reader = new FileReader();

                reader.onload = function(e) {

                    document.getElementById('newImagePreview')
                            .style.display = 'block';

                    document.getElementById('previewImg')
                            .src = e.target.result;

                };

                reader.readAsDataURL(file);

            }

        });

    }

</script>

@endpush
