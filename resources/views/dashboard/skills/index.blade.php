@extends('layouts.dashboard')

@section('title', 'Skills')
@section('page-title', 'Skills')
@section('page-subtitle', 'Manage your technical skills')

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

        .badge-skill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-frontend {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .badge-backend {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .badge-database {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .badge-devops {
            background: rgba(107, 114, 128, 0.1);
            color: #4b5563;
        }

        .badge-mobile {
            background: rgba(17, 153, 142, 0.1);
            color: #11998e;
        }

        .badge-other {
            background: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }

        .level-bar {
            width: 100%;
            background: #e8edf5;
            border-radius: 10px;
            height: 6px;
            overflow: hidden;
        }

        .level-fill {
            background: #2f7bff;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .level-text {
            font-size: 11px;
            font-weight: 600;
            color: #1a2035;
            margin-left: 8px;
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

        /* Filter bar */
        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            align-items: center;
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
            Skills <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $skills->total() }}
                total</small>
        </h5>
        <button class="btn-primary-dash" onclick="openModal('createModal')">
            <i class="bi bi-plus-lg"></i> Add Skill
        </button>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar fade-up d1">
        <form action="{{ route('dashboard.skills.index') }}" method="GET"
            style="display: flex; gap: 8px; flex-wrap: wrap; width: 100%;">
            <input type="text" name="search" class="search-input" placeholder="Search by name..."
                value="{{ request('search') }}">

            <select name="category" class="form-control-dash" style="width: auto; min-width: 150px;">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}</option>
                @endforeach
            </select>

            <select name="status" class="form-control-dash" style="width: auto; min-width: 120px;">
                <option value="">All Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="filter-btn" style="background: #2f7bff; color: #fff;">Filter</button>
            @if (request('search') || request('category') || request('status'))
                <a href="{{ route('dashboard.skills.index') }}" class="filter-btn"
                    style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</a>
            @endif
        </form>
    </div>

    {{-- ===== SKILLS TABLE ===== --}}
    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Skill Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skills as $skill)
                        <tr>
                            <td class="muted">{{ $skills->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="font-weight:700;color:#1a2035;font-size:13.5px;">{{ $skill->name }}</div>
                            </td>
                            <td>
                                @php
                                    $catClass = match ($skill->category) {
                                        'Frontend' => 'badge-frontend',
                                        'Backend' => 'badge-backend',
                                        'Database' => 'badge-database',
                                        'DevOps' => 'badge-devops',
                                        'Mobile' => 'badge-mobile',
                                        default => 'badge-other',
                                    };
                                @endphp
                                <span class="badge-skill {{ $catClass }}">{{ $skill->category ?? 'Other' }}</span>
                            </td>
                            <td style="min-width: 150px;">
                                @if ($skill->level !== null)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="level-bar" style="flex: 1;">
                                            <div class="level-fill" style="width: {{ $skill->level }}%;"></div>
                                        </div>
                                        <span class="level-text">{{ $skill->level }}%</span>
                                    </div>
                                @else
                                    <span style="color:#7a869a;">—</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('dashboard.skills.toggle', $skill->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                        @if ($skill->is_active)
                                            <span
                                                style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">●
                                                Active</span>
                                        @else
                                            <span
                                                style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">●
                                                Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="muted">{{ $skill->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <button class="action-btn action-view"
                                        onclick="openShow({{ $skill->id }}, '{{ addslashes($skill->name) }}', '{{ addslashes($skill->category) }}', {{ $skill->level ?? 'null' }})">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="action-btn action-edit"
                                        onclick="openEdit({{ $skill->id }}, '{{ addslashes($skill->name) }}', '{{ addslashes($skill->category) }}', {{ $skill->level ?? 'null' }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="action-btn action-del"
                                        onclick="openDeleteModal({{ $skill->id }}, '{{ addslashes($skill->name) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-code-slash"></i>
                                    <p>No skills found. Start by adding your first skill!</p>
                                    <button class="btn-primary-dash" onclick="openModal('createModal')">
                                        <i class="bi bi-plus-lg"></i> Add Skill
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($skills->hasPages())
            <div
                style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $skills->firstItem() }}–{{ $skills->lastItem() }} of {{ $skills->total() }} skills
                </div>
                <div class="dash-pagination">
                    {{ $skills->links() }}
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
                        Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.skills.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if (request()->has('user_id'))
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        @endif
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Skill Name *</label>
                                <input type="text" name="name" class="form-control-dash"
                                    placeholder="e.g. Laravel, React, Python" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category</label>
                                <select name="category" class="form-control-dash">
                                    <option value="">-- Select Category --</option>
                                    <option value="Frontend">Frontend</option>
                                    <option value="Backend">Backend</option>
                                    <option value="Database">Database</option>
                                    <option value="DevOps">DevOps</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Skill Level <small
                                        style="font-weight:400;color:#7a869a;">(0-100%)</small></label>
                                <input type="range" name="level" class="form-control-dash" min="0"
                                    max="100" value="50" oninput="this.nextElementSibling.value = this.value">
                                <output
                                    style="display: block; text-align: center; margin-top: 5px; font-size: 12px; color: #2f7bff;">50%</output>
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this skill on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save
                            Skill</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-fill me-2"></i>Edit Skill
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        @if (request()->has('user_id'))
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        @endif
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label-dash">Skill Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control-dash" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-dash">Category</label>
                                <select name="category" id="edit_category" class="form-control-dash">
                                    <option value="">-- Select Category --</option>
                                    <option value="Frontend">Frontend</option>
                                    <option value="Backend">Backend</option>
                                    <option value="Database">Database</option>
                                    <option value="DevOps">DevOps</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Skill Level <small
                                        style="font-weight:400;color:#7a869a;">(0-100%)</small></label>
                                <input type="range" name="level" id="edit_level" class="form-control-dash"
                                    min="0" max="100" oninput="this.nextElementSibling.value = this.value">
                                <output id="edit_level_output"
                                    style="display: block; text-align: center; margin-top: 5px; font-size: 12px; color: #2f7bff;">50%</output>
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this skill on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update
                            Skill</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-eye-fill me-2"></i>Skill Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Skill Name</label>
                                <h4 id="show_name" style="font-weight:700;color:#1a2035;margin:0;"></h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Category</label>
                                <div id="show_category"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Skill Level</label>
                                <div id="show_level"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Status</label>
                                <div id="show_status"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Created At</label>
                                <p id="show_created" style="color:#1a2035;margin:0;"></p>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete Skill
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong
                            id="delete_skill_name"></strong>?</p>
                    <p style="font-size:13px;color:#7a869a;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        @if (request()->has('user_id'))
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        @endif
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

        function openEdit(id, name, category, level) {
            document.getElementById('editForm').action = `/dashboard/skills/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category').value = category || '';
            const levelInput = document.getElementById('edit_level');
            const levelOutput = document.getElementById('edit_level_output');
            levelInput.value = level || 0;
            levelOutput.value = level || 0 + '%';
            levelOutput.textContent = (level || 0) + '%';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function openShow(id, name, category, level) {
            document.getElementById('show_name').innerText = name;

            // Category badge
            let catClass = '';
            if (category === 'Frontend') catClass = 'badge-frontend';
            else if (category === 'Backend') catClass = 'badge-backend';
            else if (category === 'Database') catClass = 'badge-database';
            else if (category === 'DevOps') catClass = 'badge-devops';
            else if (category === 'Mobile') catClass = 'badge-mobile';
            else catClass = 'badge-other';

            document.getElementById('show_category').innerHTML =
                `<span class="badge-skill ${catClass}">${category || 'Other'}</span>`;

            // Level display
            if (level !== null && level !== undefined) {
                document.getElementById('show_level').innerHTML = `
                <div style="display: flex; align-items: center; gap: 8px; max-width: 300px;">
                    <div class="level-bar" style="flex: 1;">
                        <div class="level-fill" style="width: ${level}%;"></div>
                    </div>
                    <span class="level-text">${level}%</span>
                </div>
            `;
            } else {
                document.getElementById('show_level').innerHTML = '<span style="color:#7a869a;">—</span>';
            }

            new bootstrap.Modal(document.getElementById('showModal')).show();
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete_skill_name').innerText = name;
            document.getElementById('deleteForm').action = `/dashboard/skills/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Auto-open create modal if validation failed
        @if ($errors->any())
            openModal('createModal');
        @endif

        // Level range input update
        document.addEventListener('DOMContentLoaded', function() {
            const rangeInputs = document.querySelectorAll('input[type="range"]');
            rangeInputs.forEach(input => {
                const output = input.nextElementSibling;
                if (output && output.tagName === 'OUTPUT') {
                    input.addEventListener('input', function() {
                        output.value = this.value;
                        output.textContent = this.value + '%';
                    });
                }
            });
        });
    </script>
@endpush
