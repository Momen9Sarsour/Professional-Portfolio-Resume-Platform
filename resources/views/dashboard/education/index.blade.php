@extends('layouts.dashboard')

@section('title', 'Education')
@section('page-title', 'Education')
@section('page-subtitle', 'Manage your educational background')

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

    .date-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        background: #f4f6fb;
        color: #7a869a;
    }

    .present-badge {
        background: #d1fae5;
        color: #065f46;
    }

    .university-name {
        font-weight: 600;
        color: #2f7bff;
        font-size: 13px;
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
            Education <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $education->total() }} total</small>
        </h5>
        <button class="btn-primary-dash" onclick="openModal('createModal')">
            <i class="bi bi-plus-lg"></i> Add Education
        </button>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar fade-up d1">
        <input type="text" id="searchInput" class="search-input" placeholder="Search university or degree..." value="{{ request('search') }}">
        <select id="statusFilter" class="form-control-dash" style="width: auto; min-width: 120px;">
            <option value="">All Status</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button class="filter-btn" onclick="applyFilters()" style="background: #2f7bff; color: #fff;">Filter</button>
        @if(request('search') || request('status'))
        <button class="filter-btn" onclick="clearFilters()" style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</button>
        @endif
        <button class="filter-btn {{ !request('search') && !request('status') ? 'active' : '' }}" onclick="filterBy('')">All</button>
    </div>

    {{-- ===== EDUCATION TABLE ===== --}}
    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Degree</th>
                        <th>University</th>
                        <th>Period</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($education as $edu)
                        <tr>
                            <td class="muted">{{ $education->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="font-weight:700;color:#1a2035;font-size:13.5px;">{{ $edu->degree }}</div>
                                <div class="date-badge mt-1">Order: {{ $edu->sort_order }}</div>
                            </td>
                            <td>
                                <div class="university-name">{{ $edu->university }}</div>
                            </td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                                    @if($edu->end_date)
                                        {{ \Carbon\Carbon::parse($edu->end_date)->format('M Y') }}
                                    @else
                                        <span class="date-badge present-badge">Present</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $edu->description ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('dashboard.education.toggle', $edu->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                        @if($edu->is_active)
                                            <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">● Active</span>
                                        @else
                                            <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">● Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <button class="action-btn action-view" onclick="openShow({{ $edu->id }}, '{{ addslashes($edu->degree) }}', '{{ addslashes($edu->university) }}', '{{ $edu->start_date }}', '{{ $edu->end_date }}', '{{ addslashes($edu->description) }}', {{ $edu->sort_order }})">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="action-btn action-edit" onclick="openEdit({{ $edu->id }}, '{{ addslashes($edu->degree) }}', '{{ addslashes($edu->university) }}', '{{ $edu->start_date }}', '{{ $edu->end_date }}', '{{ addslashes($edu->description) }}', {{ $edu->sort_order }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="action-btn action-del" onclick="openDeleteModal({{ $edu->id }}, '{{ addslashes($edu->degree) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-mortarboard"></i>
                                    <p>No education records found. Start by adding your educational background!</p>
                                    <button class="btn-primary-dash" onclick="openModal('createModal')">
                                        <i class="bi bi-plus-lg"></i> Add Education
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($education->hasPages())
            <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $education->firstItem() }}–{{ $education->lastItem() }} of {{ $education->total() }} education records
                </div>
                <div class="dash-pagination">
                    {{ $education->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ===== CREATE MODAL ===== --}}
    <div class="modal fade modal-dash" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle-fill me-2"></i>Add New Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.education.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Degree *</label>
                                <input type="text" name="degree" class="form-control-dash" placeholder="e.g. Bachelor of Computer Science" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">University *</label>
                                <input type="text" name="university" class="form-control-dash" placeholder="e.g. Arab Open University" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Start Date *</label>
                                <input type="date" name="start_date" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">End Date</label>
                                <input type="date" name="end_date" class="form-control-dash">
                                <small style="font-size:11px;color:#7a869a;">Leave empty if currently studying</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description</label>
                                <textarea name="description" class="form-control-dash" rows="3" placeholder="Additional details about your studies, achievements, etc..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control-dash" value="0" min="0">
                                <small style="font-size:11px;color:#7a869a;">Lower numbers appear first</small>
                            </div>
                            <div class="col-md-6">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;margin-top:32px;">
                                    <input type="checkbox" name="is_active" value="1" checked style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this education on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save Education</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-fill me-2"></i>Edit Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Degree *</label>
                                <input type="text" name="degree" id="edit_degree" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">University *</label>
                                <input type="text" name="university" id="edit_university" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Start Date *</label>
                                <input type="date" name="start_date" id="edit_start_date" class="form-control-dash" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">End Date</label>
                                <input type="date" name="end_date" id="edit_end_date" class="form-control-dash">
                                <small style="font-size:11px;color:#7a869a;">Leave empty if currently studying</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label-dash">Description</label>
                                <textarea name="description" id="edit_description" class="form-control-dash" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">Sort Order</label>
                                <input type="number" name="sort_order" id="edit_sort_order" class="form-control-dash" min="0">
                            </div>
                            <div class="col-md-6">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;margin-top:32px;">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this education on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update Education</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-eye-fill me-2"></i>Education Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Degree</label>
                                <h4 id="show_degree" style="font-weight:700;color:#1a2035;margin:0;"></h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">University</label>
                                <p id="show_university" style="color:#1a2035;font-size:15px;"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Period</label>
                                <p id="show_period" style="color:#1a2035;"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Description</label>
                                <p id="show_description" style="color:#1a2035;line-height:1.5;"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Sort Order</label>
                                <p id="show_sort_order" style="color:#1a2035;"></p>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong id="delete_education_name"></strong>?</p>
                    <p style="font-size:13px;color:#7a869a;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-dash"><i class="bi bi-trash-fill me-1"></i> Delete</button>
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

    function openEdit(id, degree, university, startDate, endDate, description, sortOrder) {
        document.getElementById('editForm').action = `/dashboard/education/${id}`;
        document.getElementById('edit_degree').value = degree;
        document.getElementById('edit_university').value = university;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate || '';
        document.getElementById('edit_description').value = description || '';
        document.getElementById('edit_sort_order').value = sortOrder || 0;

        // We need to fetch is_active status separately or pass it
        // Let's fetch it via AJAX to get the current status
        fetch(`/dashboard/education/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit_is_active').checked = data.education.is_active;
            }
        });

        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function openShow(id, degree, university, startDate, endDate, description, sortOrder) {
        document.getElementById('show_degree').innerText = degree;
        document.getElementById('show_university').innerText = university;

        // Format period
        let start = new Date(startDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
        let end = endDate ? new Date(endDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long' }) : 'Present';
        document.getElementById('show_period').innerHTML = `${start} - ${end}`;

        document.getElementById('show_description').innerHTML = description || '—';
        document.getElementById('show_sort_order').innerHTML = sortOrder || 0;

        // Fetch status via AJAX
        fetch(`/dashboard/education/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.education.is_active) {
                    document.getElementById('show_status').innerHTML = '<span class="badge-cat" style="background:#d1fae5;color:#065f46;">Active</span>';
                } else {
                    document.getElementById('show_status').innerHTML = '<span class="badge-cat" style="background:#fee2e2;color:#991b1b;">Inactive</span>';
                }
            }
        });

        new bootstrap.Modal(document.getElementById('showModal')).show();
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete_education_name').innerText = name;
        document.getElementById('deleteForm').action = `/dashboard/education/${id}`;
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

    function applyFilters() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const url = new URL(window.location.href);

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }

        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function clearFilters() {
        window.location.href = '{{ route("dashboard.education.index") }}';
    }

    // Auto-open create modal if validation failed
    @if ($errors->any())
        openModal('createModal');
    @endif

    // Allow Enter key to submit filter
    document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
</script>
@endpush
