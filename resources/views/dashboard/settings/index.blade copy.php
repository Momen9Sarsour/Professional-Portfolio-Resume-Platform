@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your application settings')

@push('styles')
<style>
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

    .btn-outline-secondary {
        background: transparent;
        border: 1.5px solid #e8edf5;
        color: #1a2035;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        background: #f8fafc;
        border-color: #2f7bff;
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

    .help-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .preview-badge {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 4px;
        margin-left: 8px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">

        {{-- Live Preview Alert --}}
        <div class="alert alert-info mb-4" style="background: rgba(47, 123, 255, 0.1); border: none; border-radius: 16px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2" style="font-size: 18px; color: #2f7bff;"></i>
                <div>
                    <strong>Live Preview Mode</strong><br>
                    <small>Color changes apply immediately across the dashboard and CV templates when you save.</small>
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tab Navigation --}}
            <div class="tab-buttons">
                @foreach($sections as $key => $section)
                    <button type="button" class="tab-btn {{ $loop->first ? 'active' : '' }}" data-tab="{{ $key }}">
                        <i class="{{ $section['icon'] }} me-2"></i>
                        {{ $section['title'] }}
                    </button>
                @endforeach
            </div>

            {{-- Tab Panes --}}
            @foreach($sections as $sectionKey => $section)
            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="tab-{{ $sectionKey }}">
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-icon">
                            <i class="{{ $section['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="settings-title">{{ $section['title'] }}</h3>
                            <p class="settings-desc">{{ $section['description'] }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($section['fields'] as $fieldKey => $field)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-dot"></i>
                                    {{ $field['label'] }}
                                </label>

                                @if($field['type'] == 'textarea')
                                    <textarea name="{{ $fieldKey }}" class="form-control-custom" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}">{{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }}</textarea>

                                @elseif($field['type'] == 'code')
                                    <textarea name="{{ $fieldKey }}" class="form-control-custom code-editor" rows="6" placeholder="{{ $field['placeholder'] ?? '' }}">{{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }}</textarea>
                                    <div class="help-text">Add custom CSS/JS code. This will be injected into the page header/footer.</div>

                                @elseif($field['type'] == 'image')
                                    <input type="file" name="{{ $fieldKey }}" class="form-control-custom" accept="image/*">
                                    @if(!empty($settings[$fieldKey]))
                                    <div class="image-preview">
                                        <img src="{{ asset('storage/' . $settings[$fieldKey]) }}" alt="{{ $field['label'] }}">
                                        <small class="text-muted d-block mt-1">Current image</small>
                                    </div>
                                    @endif
                                    <div class="help-text">Recommended size: 200x200px for logo, 64x64px for favicon</div>

                                @elseif($field['type'] == 'color')
                                    <div class="color-group">
                                        <input type="color" name="{{ $fieldKey }}" class="form-control-custom" style="width: 70px;" value="{{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }}">
                                        <input type="text" class="form-control-custom" value="{{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }}" readonly style="flex: 1; background: #f8fafc;">
                                        <div class="color-preview" style="background: {{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }};"></div>
                                    </div>
                                    <div class="help-text">Select a color for {{ strtolower($field['label']) }}</div>

                                @elseif($field['type'] == 'checkbox')
                                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                        <div class="switch">
                                            <input type="checkbox" name="{{ $fieldKey }}" value="1" {{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </div>
                                        <span>Enable {{ strtolower($field['label']) }}</span>
                                    </label>
                                    <div class="help-text">Toggle to turn {{ strtolower($field['label']) }} on/off</div>

                                @elseif($field['type'] == 'select')
                                    <select name="{{ $fieldKey }}" class="form-control-custom">
                                        @foreach($field['options'] as $optValue => $optLabel)
                                            <option value="{{ $optValue }}" {{ (old($fieldKey, $settings[$fieldKey] ?? $field['default']) == $optValue) ? 'selected' : '' }}>
                                                {{ $optLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="help-text">Choose from available options</div>

                                @elseif($field['type'] == 'password')
                                    <input type="password" name="{{ $fieldKey }}" class="form-control-custom" value="{{ old($fieldKey, $settings[$fieldKey] ?? '') }}" placeholder="{{ $field['placeholder'] ?? '••••••••' }}" autocomplete="off">
                                    <div class="help-text">Leave empty to keep current value</div>

                                @else
                                    <input type="{{ $field['type'] }}" name="{{ $fieldKey }}" class="form-control-custom" value="{{ old($fieldKey, $settings[$fieldKey] ?? $field['default']) }}" placeholder="{{ $field['placeholder'] ?? $field['label'] }}">
                                @endif

                                @error($fieldKey)
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center gap-3 mt-4" style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #e8edf5;">
                <div>
                    <button type="button" class="reset-btn" onclick="confirmReset()">
                        <i class="bi bi-arrow-repeat me-2"></i> Reset to Default
                    </button>
                </div>
                <div>
                    <button type="submit" class="btn-primary-dash">
                        <i class="bi bi-check-lg me-2"></i> Save All Settings
                    </button>
                </div>
            </div>
        </form>
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
                <div style="font-size: 48px; margin-bottom: 16px; color: #ef4444;">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <p style="font-size: 15px; color: #1a2035; margin-bottom: 8px;">Are you sure you want to reset all settings?</p>
                <p style="font-size: 13px; color: #7a869a;">This will restore all default values and cannot be undone.</p>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-2">
                <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('dashboard.settings.reset') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-dash">
                        <i class="bi bi-check-lg me-1"></i> Yes, Reset All
                    </button>
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
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="test_email" class="form-control-custom" placeholder="your-email@example.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-dash">
                        <i class="bi bi-send me-1"></i> Send Test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById('tab-' + tabId).classList.add('active');

            // Save active tab to localStorage
            localStorage.setItem('activeSettingsTab', tabId);
        });
    });

    // Restore active tab from localStorage
    const savedTab = localStorage.getItem('activeSettingsTab');
    if (savedTab) {
        const tabBtn = document.querySelector(`.tab-btn[data-tab="${savedTab}"]`);
        if (tabBtn) {
            tabBtn.click();
        }
    }

    // Color preview live update
    document.querySelectorAll('input[type="color"]').forEach(input => {
        input.addEventListener('input', function() {
            const group = this.closest('.color-group');
            if (group) {
                const textInput = group.querySelector('input[type="text"]');
                const preview = group.querySelector('.color-preview');
                if (textInput) textInput.value = this.value;
                if (preview) preview.style.background = this.value;
            }
        });
    });

    // Confirm reset
    function confirmReset() {
        new bootstrap.Modal(document.getElementById('resetModal')).show();
    }

    // Test email modal
    function testEmail() {
        new bootstrap.Modal(document.getElementById('testEmailModal')).show();
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-dash').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
@endpush
