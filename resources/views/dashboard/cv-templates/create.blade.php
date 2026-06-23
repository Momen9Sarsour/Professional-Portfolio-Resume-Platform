@extends('layouts.dashboard')

@section('title', 'Add CV Template')
@section('page-title', 'Add New CV Template')
@section('page-subtitle', 'Create a custom CV template with HTML/CSS/JS')

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

    {{-- Main Form Column --}}
    <div class="col-lg-8">

        <div class="card-box">

            <form action="{{ route('dashboard.cv-templates.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="templateForm">

                @csrf

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
                                   value="{{ old('name') }}"
                                   required>

                            <small class="help-text">
                                Unique name for this template
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-dash">Sort Order</label>

                            <input type="number"
                                   name="sort_order"
                                   class="form-control-dash"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0">

                            <small class="help-text">
                                Lower numbers appear first
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label-dash">Description</label>

                            <textarea name="description"
                                      class="form-control-dash"
                                      rows="2"
                                      placeholder="Describe what this template looks like...">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Template Code --}}
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

                                @verbatim
                                <span>
                                    Use Blade syntax:
                                    {{ $user->name }},
                                    @foreach($skills as $skill)
                                </span>
                                @endverbatim

                            </div>

                            <textarea id="htmlEditor"
                                      name="html_code"
                                      class="code-editor"></textarea>

                        </div>
                    </div>

                    {{-- CSS --}}
                    <div id="tab-css" class="tab-pane-custom">

                        <div class="editor-wrapper">

                            <div class="editor-header">
                                <span>Custom CSS</span>
                            </div>

                            <textarea id="cssEditor"
                                      name="css_code"
                                      class="code-editor"></textarea>

                        </div>
                    </div>

                    {{-- JS --}}
                    <div id="tab-js" class="tab-pane-custom">

                        <div class="editor-wrapper">

                            <div class="editor-header">
                                <span>Custom JavaScript</span>
                            </div>

                            <textarea id="jsEditor"
                                      name="js_code"
                                      class="code-editor"></textarea>

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">

                    <button type="submit" class="btn-primary-dash">
                        Create Template
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- Variables Sidebar --}}
    <div class="col-lg-4">

        <div class="variables-sidebar">

            <h6 class="mb-3">
                Available Variables
            </h6>

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

                    <div class="var-code">
                        {{ $project->title }}
                    </div>

                    <div class="var-code">
                        {{ $project->description }}
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($projects as $project)\n<h3>{{ $project->title }}</h3>\n<p>{{ $project->description }}</p>\n@endforeach')">

                        Insert Example

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

                    <div class="var-code">
                        {{ $skill->name }}
                    </div>

                    <div class="var-code">
                        {{ $skill->level }}
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($skills as $skill)\n<span>{{ $skill->name }}</span>\n@endforeach')">

                        Insert Example

                    </button>

                </div>
                @endverbatim

            </div>

            {{-- Experience --}}
            <div class="var-group">

                <div class="var-group-title">
                    Experiences
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($experiences as $exp)
                    </div>

                    <div class="var-code">
                        {{ $exp->job_title }}
                    </div>

                    <div class="var-code">
                        {{ $exp->company }}
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($experiences as $exp)\n<div>{{ $exp->job_title }}</div>\n@endforeach')">

                        Insert Example

                    </button>

                </div>
                @endverbatim

            </div>

            {{-- Education --}}
            <div class="var-group">

                <div class="var-group-title">
                    Education
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($education as $edu)
                    </div>

                    <div class="var-code">
                        {{ $edu->degree }}
                    </div>

                    <div class="var-code">
                        {{ $edu->university }}
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($education as $edu)\n<div>{{ $edu->degree }}</div>\n@endforeach')">

                        Insert Example

                    </button>

                </div>
                @endverbatim

            </div>

            {{-- Social --}}
            <div class="var-group">

                <div class="var-group-title">
                    Social Links
                </div>

                @verbatim
                <div class="var-item">

                    <div class="var-code">
                        @foreach($socialLinks as $link)
                    </div>

                    <div class="var-code">
                        {{ $link->platform }}
                    </div>

                    <div class="var-code">
                        {{ $link->url }}
                    </div>

                    <button type="button"
                            class="insert-var-btn"
                            onclick="insertCode('@foreach($socialLinks as $link)\n<a href=\"{{ $link->url }}\">{{ $link->platform }}</a>\n@endforeach')">

                        Insert Example

                    </button>

                </div>
                @endverbatim

            </div>

            @verbatim
            <div class="alert alert-info mt-3"
                 style="font-size:12px; background:rgba(47,123,255,0.1); border:none;">

                <strong>Tip:</strong>
                Use @if and always close @foreach with @endforeach

            </div>
            @endverbatim

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>

<script>
    let htmlEditor = CodeMirror.fromTextArea(
        document.getElementById("htmlEditor"),
        {
            mode: "xml",
            theme: "dracula",
            lineNumbers: true
        }
    );

    let cssEditor = CodeMirror.fromTextArea(
        document.getElementById("cssEditor"),
        {
            mode: "css",
            theme: "dracula",
            lineNumbers: true
        }
    );

    let jsEditor = CodeMirror.fromTextArea(
        document.getElementById("jsEditor"),
        {
            mode: "javascript",
            theme: "dracula",
            lineNumbers: true
        }
    );

    function insertCode(code) {
        htmlEditor.replaceSelection(code);
        htmlEditor.focus();
    }


</script>

@endpush
