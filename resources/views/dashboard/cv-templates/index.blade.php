@extends('layouts.dashboard')

@section('title', 'CV Templates')
@section('page-title', 'CV Templates')
@section('page-subtitle', 'Manage all CV templates')

@push('styles')
<style>
    .template-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e8edf5;
        margin-bottom: 24px;
    }

    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .template-preview {
        height: 200px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .template-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .template-preview .no-image {
        text-align: center;
        color: #94a3b8;
    }

    .template-preview .no-image i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .template-info {
        padding: 20px;
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

    .badge-default {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-system {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-custom {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .template-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e8edf5;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 8px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        border: 1px solid #e8edf5;
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        color: #7a869a;
        margin-top: 4px;
    }

    .filter-bar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-btn {
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        border: 2px solid #2f7bff;
        cursor: pointer;
        transition: all .2s;
        background: #fff;
        color: #2f7bff;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: #2f7bff;
        color: #fff;
    }

    .search-input {
        padding: 7px 14px;
        border-radius: 50px;
        font-size: 13px;
        border: 2px solid #e8edf5;
        background: #fff;
        min-width: 200px;
    }

    .search-input:focus {
        border-color: #2f7bff;
        outline: none;
    }

    .dash-pagination {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .dash-pagination a,
    .dash-pagination span {
        padding: 7px 13px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: 1.5px solid #e8edf5;
        color: #7a869a;
        text-decoration: none;
        transition: all .2s;
    }

    .dash-pagination a:hover {
        background: #2f7bff;
        color: #fff;
        border-color: #2f7bff;
    }

    .dash-pagination span.current {
        background: #2f7bff;
        color: #fff;
        border-color: #2f7bff;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7a869a;
    }

    .empty-state i {
        font-size: 60px;
        display: block;
        margin-bottom: 16px;
        opacity: .4;
    }

    .empty-state p {
        font-size: 15px;
        margin-bottom: 20px;
    }

    .btn-primary-dash {
        background: #2f7bff;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary-dash:hover {
        background: #1a5fcc;
        color: white;
    }

    .btn-light-dash {
        background: #f4f6fb;
        color: #1a2035;
        border: none;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s;
    }

    .btn-light-dash:hover {
        background: #e8edf5;
    }

    .btn-danger-dash {
        background: #ef4444;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s;
    }

    .btn-danger-dash:hover {
        background: #dc2626;
    }

    .card-box {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .fade-up {
        animation: fadeUp 0.4s ease-out forwards;
        opacity: 0;
    }

    .d1 { animation-delay: 0.05s; }
    .d2 { animation-delay: 0.1s; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 fade-up">
    <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
        CV Templates <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $templates->total() ?? 0 }} total</small>
    </h5>
    <a href="{{ route('dashboard.cv-templates.create') }}" class="btn-primary-dash">
        <i class="bi bi-plus-lg"></i> Add Template
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4 fade-up d1">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number" style="color: #2f7bff;">{{ $systemCount ?? 0 }}</div>
            <div class="stat-label">⭐ System Templates</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number" style="color: #f59e0b;">{{ $customCount ?? 0 }}</div>
            <div class="stat-label">➕ Custom Templates</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number" style="color: #22c55e;">{{ $activeCount ?? 0 }}</div>
            <div class="stat-label">✅ Active Templates</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number" style="color: #1a2035;">{{ $templates->total() ?? 0 }}</div>
            <div class="stat-label">📊 Total Templates</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar fade-up d2">
    <form action="{{ route('dashboard.cv-templates.index') }}" method="GET" style="display: flex; gap: 8px; flex-wrap: wrap; width: 100%;">
        <input type="text" name="search" class="search-input" placeholder="Search templates..." value="{{ request('search') }}">

        <select name="type" class="form-control" style="width: auto; min-width: 150px;">
            <option value="">All Types</option>
            <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>⭐ System Templates</option>
            <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>➕ Custom Templates</option>
        </select>

        <select name="status" class="form-control" style="width: auto; min-width: 120px;">
            <option value="">All Status</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button type="submit" class="filter-btn" style="background: #2f7bff; color: #fff;">Filter</button>
        @if(request('search') || request('type') || request('status'))
            <a href="{{ route('dashboard.cv-templates.index') }}" class="filter-btn" style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</a>
        @endif
    </form>
</div>

{{-- Templates Grid --}}
<div class="row fade-up d2">
    @forelse($templates as $template)
    <div class="col-md-6 col-lg-4">
        <div class="template-card">
            <div class="template-preview">
                @if($template->preview_image && Storage::disk('public')->exists($template->preview_image))
                    <img src="{{ asset('storage/' . $template->preview_image) }}" alt="{{ $template->name }}">
                @elseif($template->thumbnail && Storage::disk('public')->exists($template->thumbnail))
                    <img src="{{ asset('storage/' . $template->thumbnail) }}" alt="{{ $template->name }}">
                @else
                    <div class="no-image">
                        <i class="bi bi-image"></i>
                        <p>No preview available</p>
                    </div>
                @endif

                <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end;">
                    @if($template->is_system)
                        <span class="template-badge badge-system">System</span>
                    @else
                        <span class="template-badge badge-custom">Custom</span>
                    @endif
                    @if($template->is_default)
                        <span class="template-badge badge-default">Default</span>
                    @endif
                    @if($template->is_active)
                        <span class="template-badge badge-active">Active</span>
                    @else
                        <span class="template-badge badge-inactive">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="template-info">
                <h4 class="template-name">{{ $template->name }}</h4>
                <p class="template-description">{{ Str::limit($template->description ?? 'No description', 100) }}</p>
                <div class="template-meta mb-2">
                    <small class="text-muted">
                        <i class="bi bi-file-text"></i> {{ $template->blade_file }}
                    </small>
                    @if($template->created_by)
                        <span class="ms-2 text-muted" style="font-size: 11px;">
                            <i class="bi bi-person"></i> {{ $template->created_by }}
                        </span>
                    @endif
                </div>
                <div class="template-actions">
                    <a href="{{ route('dashboard.cv-templates.show', $template) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye-fill"></i> View
                    </a>
                    <a href="{{ route('dashboard.cv-templates.edit', $template) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                    <a href="{{ route('dashboard.cv-templates.preview', $template) }}" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye-fill"></i> Preview
                    </a>
                    <form action="{{ route('dashboard.cv-templates.toggle', $template) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-{{ $template->is_active ? 'warning' : 'success' }}">
                            <i class="bi bi-{{ $template->is_active ? 'eye-slash-fill' : 'eye-fill' }}"></i>
                            {{ $template->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    @if(!$template->is_default)
                        <form action="{{ route('dashboard.cv-templates.set-default', $template) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-star-fill"></i> Set Default
                            </button>
                        </form>
                    @endif
                    @if(!$template->is_system || $systemCount > 1)
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTemplate({{ $template->id }}, '{{ addslashes($template->name) }}')">
                            <i class="bi bi-trash-fill"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="bi bi-layout-three-columns"></i>
            <p>No CV templates found. Start by adding your first template!</p>
            <a href="{{ route('dashboard.cv-templates.create') }}" class="btn-primary-dash">
                <i class="bi bi-plus-lg"></i> Add Template
            </a>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($templates->hasPages())
    <div class="mt-4">
        {{ $templates->links() }}
    </div>
@endif

{{-- Delete Modal --}}
<div class="modal fade modal-dash" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-trash-fill me-2"></i>Delete Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteTemplateName"></strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-dash">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function deleteTemplate(id, name) {
        document.getElementById('deleteTemplateName').innerText = name;
        document.getElementById('deleteForm').action = `/dashboard/cv-templates/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
