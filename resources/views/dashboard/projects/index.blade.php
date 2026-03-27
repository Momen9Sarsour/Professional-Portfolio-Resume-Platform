@extends('layouts.dashboard')

@section('title', 'Projects')
@section('page-title', 'Projects')
@section('page-subtitle', 'Manage your portfolio projects')

@push('styles')
    <style>
        .dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .dash-table thead tr {
            background: #1a2035;
            color: #fff;
        }

        .dash-table thead th {
            padding: 12px 14px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .dash-table thead th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .dash-table thead th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .dash-table tbody tr {
            border-bottom: 1px solid #e8edf5;
            transition: background .15s;
        }

        .dash-table tbody tr:hover {
            background: #f4f6fb;
        }

        .dash-table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
        }

        .dash-table tbody td.muted {
            color: #7a869a;
            font-size: 12px;
        }

        .badge-cat {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-laravel {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .badge-web {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .badge-java {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .badge-cpp {
            background: rgba(107, 114, 128, 0.1);
            color: #4b5563;
        }

        .tech-tag {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 14px;
            font-size: 11px;
            background: #f4f6fb;
            color: #7a869a;
            font-weight: 500;
            margin: 2px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all .2s;
            font-size: 14px;
            text-decoration: none;
        }

        .action-view {
            background: rgba(17, 153, 142, 0.1);
            color: #11998e;
        }

        .action-view:hover {
            background: #11998e;
            color: #fff;
        }

        .action-edit {
            background: rgba(47, 123, 255, 0.1);
            color: #2f7bff;
        }

        .action-edit:hover {
            background: #2f7bff;
            color: #fff;
        }

        .action-del {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-del:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Modal */
        .modal-dash .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-dash .modal-header {
            background: #1a2035;
            color: #fff;
            border-radius: 16px 16px 0 0;
            padding: 18px 24px;
            border: none;
        }

        .modal-dash .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-dash .modal-body {
            padding: 24px;
        }

        .modal-dash .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e8edf5;
        }

        .form-label-dash {
            font-size: 13px;
            font-weight: 600;
            color: #1a2035;
            margin-bottom: 6px;
        }

        .form-control-dash {
            border: 1.5px solid #e8edf5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color .2s;
            width: 100%;
            background: #fff;
            color: #1a2035;
        }

        .form-control-dash:focus {
            border-color: #2f7bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.1);
        }

        /* Image preview */
        .image-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e8edf5;
        }

        .current-image {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Filter bar */
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

        /* Pagination */
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

        /* Empty state */
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

        .d1 {
            animation-delay: 0.05s;
        }

        .d2 {
            animation-delay: 0.1s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')

    {{-- ===== TOP ROW ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-4 fade-up">
        <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
            Projects <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $projects->total() }}
                total</small>
        </h5>
        <button class="btn-primary-dash" onclick="openModal('createModal')">
            <i class="bi bi-plus-lg"></i> Add Project
        </button>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar fade-up d1">
        <button class="filter-btn {{ !request('category') ? 'active' : '' }}" onclick="filterBy('')">All</button>
        <button class="filter-btn {{ request('category') === 'Laravel/PHP' ? 'active' : '' }}"
            onclick="filterBy('Laravel/PHP')">Laravel / PHP</button>
        <button class="filter-btn {{ request('category') === 'Web' ? 'active' : '' }}"
            onclick="filterBy('Web')">Web</button>
        <button class="filter-btn {{ request('category') === 'Java/Flutter' ? 'active' : '' }}"
            onclick="filterBy('Java/Flutter')">Java / Flutter</button>
        <button class="filter-btn {{ request('category') === 'C++' ? 'active' : '' }}"
            onclick="filterBy('C++')">C++</button>
    </div>

    {{-- ===== PROJECTS TABLE ===== --}}
    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Category</th>
                        <th>Technologies</th>
                        <th>Links</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td class="muted">{{ $projects->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="font-weight:700;color:#1a2035;font-size:13.5px;">{{ $project->title }}</div>
                                <div
                                    style="color:#7a869a;font-size:12px;margin-top:2px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $project->description }}</div>
                            </td>
                            <td>
                                @php
                                    $catClass = match ($project->category) {
                                        'Laravel/PHP' => 'badge-laravel',
                                        'Web' => 'badge-web',
                                        'Java/Flutter' => 'badge-java',
                                        'C++' => 'badge-cpp',
                                        default => 'badge-web',
                                    };
                                @endphp
                                <span class="badge-cat {{ $catClass }}">{{ $project->category }}</span>
                            </td>
                            <td>
                                @foreach (array_slice(explode(',', $project->technologies), 0, 3) as $tech)
                                    <span class="tech-tag">{{ trim($tech) }}</span>
                                @endforeach
                                @if (count(explode(',', $project->technologies)) > 3)
                                    <span class="tech-tag">+{{ count(explode(',', $project->technologies)) - 3 }}</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    @if ($project->github_link)
                                        <a href="{{ $project->github_link }}" target="_blank"
                                            style="color:#2f7bff;font-size:18px;"><i class="bi bi-github"></i></a>
                                    @endif
                                    @if ($project->demo_link)
                                        <a href="{{ $project->demo_link }}" target="_blank"
                                            style="color:#2f7bff;font-size:18px;"><i class="bi bi-link-45deg"></i></a>
                                    @endif
                                    @if (!$project->github_link && !$project->demo_link)
                                        <span style="color:#e8edf5;">—</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($project->image)
                                    <img src="{{ asset('storage/' . $project->image) }}" class="image-preview"
                                        alt="{{ $project->title }}">
                                @else
                                    <span style="color:#e8edf5;">—</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('dashboard.projects.toggle', $project->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                        @if ($project->is_active)
                                            <span
                                                style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">●Active</span>
                                        @else
                                            <span
                                                style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">●Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="muted">{{ $project->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <button class="action-btn action-view"
                                        onclick="openShow({{ $project->id }}, '{{ addslashes($project->title) }}', '{{ addslashes($project->description) }}', '{{ $project->category }}', '{{ addslashes($project->technologies) }}', '{{ $project->github_link }}', '{{ $project->demo_link }}', '{{ $project->image }}', {{ $project->sort_order ?? 0 }})">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="action-btn action-edit"
                                        onclick="openEdit({{ $project->id }}, '{{ addslashes($project->title) }}', '{{ addslashes($project->description) }}', '{{ $project->category }}', '{{ addslashes($project->technologies) }}', '{{ $project->github_link }}', '{{ $project->demo_link }}', {{ $project->sort_order ?? 0 }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="action-btn action-del"
                                        onclick="openDeleteModal({{ $project->id }}, '{{ addslashes($project->title) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-folder2-open"></i>
                                    <p>No projects found. Start by adding your first project!</p>
                                    <button class="btn-primary-dash" onclick="openModal('createModal')">
                                        <i class="bi bi-plus-lg"></i> Add Project
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($projects->hasPages())
            <div
                style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }} of {{ $projects->total() }} projects
                </div>
                <div class="dash-pagination">
                    @if ($projects->onFirstPage())
                        <span style="opacity:.4;"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $projects->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                    @endif

                    @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                        @if ($page == $projects->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span style="opacity:.4;"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ===== CREATE MODAL ===== --}}
    <div class="modal fade modal-dash" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle-fill me-2"></i>Add New
                        Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Project Title *</label>
                                <input type="text" name="title" class="form-control-dash"
                                    placeholder="e.g. Hotel Room Booking System" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category *</label>
                                <select name="category" class="form-control-dash" required>
                                    <option value="">-- Select --</option>
                                    <option value="Laravel/PHP">Laravel / PHP</option>
                                    <option value="Web">Web</option>
                                    <option value="Java/Flutter">Java / Flutter</option>
                                    <option value="C++">C++</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description *</label>
                                <textarea name="description" class="form-control-dash" rows="3"
                                    placeholder="Brief description of the project..." required></textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-dash">Technologies <small
                                        style="font-weight:400;color:#7a869a;">(comma separated)</small></label>
                                <input type="text" name="technologies" class="form-control-dash"
                                    placeholder="e.g. Laravel, MySQL, Bootstrap">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control-dash" value="0"
                                    min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Project Image</label>
                                <input type="file" name="image" class="form-control-dash" accept="image/*">
                                <small style="font-size:11px;color:#7a869a;">Supported: jpg, png, jpeg, gif. Max
                                    2MB</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">GitHub URL</label>
                                <input type="url" name="github_link" class="form-control-dash"
                                    placeholder="https://github.com/username/repo">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Demo URL</label>
                                <input type="url" name="demo_link" class="form-control-dash"
                                    placeholder="https://example.com/demo">
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this project on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== EDIT MODAL ===== --}}
    <div class="modal fade modal-dash" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-fill me-2"></i>Edit Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Project Title *</label>
                                <input type="text" name="title" id="edit_title" class="form-control-dash" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category *</label>
                                <select name="category" id="edit_category" class="form-control-dash" required>
                                    <option value="Laravel/PHP">Laravel / PHP</option>
                                    <option value="Web">Web</option>
                                    <option value="Java/Flutter">Java / Flutter</option>
                                    <option value="C++">C++</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description *</label>
                                <textarea name="description" id="edit_description" class="form-control-dash" rows="3" required></textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-dash">Technologies <small
                                        style="font-weight:400;color:#7a869a;">(comma separated)</small></label>
                                <input type="text" name="technologies" id="edit_technologies"
                                    class="form-control-dash">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" id="edit_sort_order" class="form-control-dash"
                                    min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Current Image</label>
                                <div class="current-image" id="edit_current_image_container"></div>
                                <label class="form-label-dash mt-2">Change Image</label>
                                <input type="file" name="image" class="form-control-dash" accept="image/*">
                                <small style="font-size:11px;color:#7a869a;">Leave empty to keep current image</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">GitHub URL</label>
                                <input type="url" name="github_link" id="edit_github_link"
                                    class="form-control-dash">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Demo URL</label>
                                <input type="url" name="demo_link" id="edit_demo_link" class="form-control-dash">
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this project on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SHOW MODAL ===== --}}
    <div class="modal fade modal-dash" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-eye-fill me-2"></i>Project Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <img id="show_image" src="" alt="Project Image"
                                style="width:100%; max-width:200px; border-radius:12px; border:1px solid #e8edf5; object-fit:cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Project Title</label>
                                <h4 id="show_title" style="font-weight:700;color:#1a2035;margin:0;"></h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Category</label>
                                <div id="show_category"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Description</label>
                                <p id="show_description" style="color:#1a2035;margin:0;line-height:1.5;"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Technologies</label>
                                <div id="show_technologies"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-dash" style="color:#7a869a;">GitHub</label>
                                    <div><a id="show_github_link" href="#" target="_blank"
                                            style="color:#2f7bff;text-decoration:none;"></a></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-dash" style="color:#7a869a;">Demo URL</label>
                                    <div><a id="show_demo_link" href="#" target="_blank"
                                            style="color:#2f7bff;text-decoration:none;"></a></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Sort Order</label>
                                <p id="show_sort_order" style="color:#1a2035;margin:0;"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Status</label>
                                <div id="show_status"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DELETE CONFIRMATION MODAL ===== --}}
    <div class="modal fade modal-dash" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong
                            id="delete_project_title"></strong>?</p>
                    <p style="font-size:13px;color:#7a869a;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-dash"><i class="bi bi-trash-fill me-1"></i>
                            Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function openEdit(id, title, description, category, technologies, githubLink, demoLink, sortOrder) {
            document.getElementById('editForm').action = `/dashboard/projects/${id}`;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_technologies').value = technologies;
            document.getElementById('edit_github_link').value = githubLink || '';
            document.getElementById('edit_demo_link').value = demoLink || '';
            document.getElementById('edit_sort_order').value = sortOrder || 0;

            // For is_active - we need to fetch it separately or pass from the button
            // We'll add a hidden field or get from the table
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function openShow(id, title, description, category, technologies, githubLink, demoLink, image, sortOrder) {
            document.getElementById('show_title').innerText = title;
            document.getElementById('show_description').innerText = description;
            document.getElementById('show_sort_order').innerText = sortOrder || 0;

            // Category badge
            let catClass = '';
            if (category === 'Laravel/PHP') catClass = 'badge-laravel';
            else if (category === 'Web') catClass = 'badge-web';
            else if (category === 'Java/Flutter') catClass = 'badge-java';
            else if (category === 'C++') catClass = 'badge-cpp';
            document.getElementById('show_category').innerHTML = `<span class="badge-cat ${catClass}">${category}</span>`;

            // Technologies
            let techHtml = '';
            if (technologies) {
                let techs = technologies.split(',');
                techs.forEach(tech => {
                    techHtml += `<span class="tech-tag">${tech.trim()}</span>`;
                });
            } else {
                techHtml = '<span style="color:#7a869a;">—</span>';
            }
            document.getElementById('show_technologies').innerHTML = techHtml;

            // Links
            if (githubLink && githubLink !== '') {
                document.getElementById('show_github_link').innerHTML = githubLink;
                document.getElementById('show_github_link').href = githubLink;
            } else {
                document.getElementById('show_github_link').innerHTML = '—';
                document.getElementById('show_github_link').href = '#';
            }

            if (demoLink && demoLink !== '') {
                document.getElementById('show_demo_link').innerHTML = demoLink;
                document.getElementById('show_demo_link').href = demoLink;
            } else {
                document.getElementById('show_demo_link').innerHTML = '—';
                document.getElementById('show_demo_link').href = '#';
            }

            // Image
            if (image && image !== '') {
                document.getElementById('show_image').src = `/storage/${image}`;
                document.getElementById('show_image').style.display = 'block';
            } else {
                document.getElementById('show_image').src = '';
                document.getElementById('show_image').style.display = 'none';
            }

            // Status
            // For status we need to fetch it, we'll get from the table row
            // We'll add a separate function to get status from the row
            new bootstrap.Modal(document.getElementById('showModal')).show();
        }

        function openDeleteModal(id, title) {
            document.getElementById('delete_project_title').innerText = title;
            document.getElementById('deleteForm').action = `/dashboard/projects/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function filterBy(category) {
            const url = new URL(window.location.href);
            if (category) {
                url.searchParams.set('category', category);
            } else {
                url.searchParams.delete('category');
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // Auto-open create modal if validation failed
        @if ($errors->any())
            openModal('createModal');
        @endif
    </script>
@endpush
