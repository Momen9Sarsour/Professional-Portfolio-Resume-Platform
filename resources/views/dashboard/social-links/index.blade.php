@extends('layouts.dashboard')

@section('title', 'Social Links')
@section('page-title', 'Social Links')
@section('page-subtitle', 'Manage your social media profiles')

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

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all .2s;
        }

        .social-icon.github {
            background: rgba(51, 51, 51, 0.1);
            color: #333;
        }

        .social-icon.linkedin {
            background: rgba(0, 119, 181, 0.1);
            color: #0077b5;
        }

        .social-icon.twitter {
            background: rgba(29, 161, 242, 0.1);
            color: #1da1f2;
        }

        .social-icon.facebook {
            background: rgba(66, 103, 178, 0.1);
            color: #4267b2;
        }

        .social-icon.instagram {
            background: rgba(225, 48, 108, 0.1);
            color: #e1306c;
        }

        .social-icon.youtube {
            background: rgba(255, 0, 0, 0.1);
            color: #ff0000;
        }

        .social-icon.whatsapp {
            background: rgba(37, 211, 102, 0.1);
            color: #25d366;
        }

        .social-icon.telegram {
            background: rgba(36, 161, 222, 0.1);
            color: #24a1de;
        }

        .social-icon.other {
            background: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }

        .social-icon:hover {
            transform: scale(1.05);
        }

        .platform-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #f4f6fb;
            color: #7a869a;
            text-transform: capitalize;
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

        .url-link {
            color: #2f7bff;
            text-decoration: none;
            font-size: 12px;
            word-break: break-all;
        }

        .url-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')

    {{-- ===== TOP ROW ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-4 fade-up">
        <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
            Social Links <small
                style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $socialLinks->total() }}
                total</small>
        </h5>
        <button class="btn-primary-dash" onclick="openModal('createModal')">
            <i class="bi bi-plus-lg"></i> Add Social Link
        </button>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar fade-up d1">
        <input type="text" id="platformInput" class="search-input" placeholder="Search platform..."
            value="{{ request('platform') }}">
        <select id="statusFilter" class="form-control-dash" style="width: auto; min-width: 120px;">
            <option value="">All Status</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button class="filter-btn" onclick="applyFilters()" style="background: #2f7bff; color: #fff;">Filter</button>
        @if (request('platform') || request('status'))
            <button class="filter-btn" onclick="clearFilters()"
                style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</button>
        @endif
        <button class="filter-btn {{ !request('platform') && !request('status') ? 'active' : '' }}"
            onclick="filterBy('')">All</button>
    </div>

    {{-- ===== SOCIAL LINKS TABLE ===== --}}
    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <th>#</th>
                    <th>Platform</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @forelse($socialLinks as $link)
                        <tr>
                            <td class="muted">{{ $socialLinks->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="social-icon {{ $link->platform }}">
                                        @php
                                            $iconMap = [
                                                'github' => 'github',
                                                'linkedin' => 'linkedin',
                                                'twitter' => 'twitter-x',
                                                'facebook' => 'facebook',
                                                'instagram' => 'instagram',
                                                'youtube' => 'youtube',
                                                'whatsapp' => 'whatsapp',
                                                'telegram' => 'telegram',
                                                'other' => 'share-fill',
                                            ];
                                            $icon = $iconMap[$link->platform] ?? 'share-fill';
                                        @endphp
                                        <i class="bi bi-{{ $icon }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:#1a2035;text-transform:capitalize;">
                                            {{ $link->platform }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ $link->url }}" target="_blank" class="url-link">
                                    {{ Str::limit($link->url, 50) }}
                                    <i class="bi bi-box-arrow-up-right" style="font-size: 10px; margin-left: 4px;"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('dashboard.social-links.toggle', $link->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                        @if ($link->is_active)
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
                            <td class="muted">{{ $link->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <button class="action-btn action-view"
                                        onclick="openShow({{ $link->id }}, '{{ addslashes($link->platform) }}', '{{ addslashes($link->url) }}')">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button class="action-btn action-edit"
                                        onclick="openEdit({{ $link->id }}, '{{ addslashes($link->platform) }}', '{{ addslashes($link->url) }}')">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="action-btn action-del"
                                        onclick="openDeleteModal({{ $link->id }}, '{{ addslashes($link->platform) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-share"></i>
                                    <p>No social links found. Add your social media profiles!</p>
                                    <button class="btn-primary-dash" onclick="openModal('createModal')">
                                        <i class="bi bi-plus-lg"></i> Add Social Link
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($socialLinks->hasPages())
            <div
                style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $socialLinks->firstItem() }}–{{ $socialLinks->lastItem() }} of {{ $socialLinks->total() }}
                    social links
                </div>
                <div class="dash-pagination">
                    {{ $socialLinks->links() }}
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
                        Social Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.social-links.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Platform *</label>
                                <select name="platform" class="form-control-dash" required>
                                    <option value="">-- Select Platform --</option>
                                    <option value="github">GitHub</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="twitter">Twitter/X</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="other">Other</option>
                                </select>
                                <small style="font-size:11px;color:#7a869a;">Select the social media platform</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">URL *</label>
                                <input type="url" name="url" class="form-control-dash" placeholder="https://..."
                                    required>
                                <small style="font-size:11px;color:#7a869a;">Full URL to your profile</small>
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this social link on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Save Social
                            Link</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-pencil-fill me-2"></i>Edit Social
                        Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-dash">Platform *</label>
                                <select name="platform" id="edit_platform" class="form-control-dash" required>
                                    <option value="github">GitHub</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="twitter">Twitter/X</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-dash">URL *</label>
                                <input type="url" name="url" id="edit_url" class="form-control-dash" required>
                            </div>
                            <div class="col-12">
                                <label
                                    style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                        style="width:16px;height:16px;accent-color:#2f7bff;">
                                    Show this social link on the public portfolio
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-dash"><i class="bi bi-check-lg me-1"></i> Update Social
                            Link</button>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-eye-fill me-2"></i>Social Link
                        Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="mb-3 text-center">
                                <div id="show_social_icon" class="social-icon"
                                    style="width: 80px; height: 80px; font-size: 40px; margin: 0 auto 20px;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Platform</label>
                                <h4 id="show_platform"
                                    style="font-weight:700;color:#1a2035;margin:0;text-transform:capitalize;"></h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">URL</label>
                                <p><a id="show_url" href="#" target="_blank"
                                        style="color:#2f7bff;text-decoration:none;word-break:break-all;"></a></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Status</label>
                                <div id="show_status"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-dash" style="color:#7a869a;">Created At</label>
                                <p id="show_created" style="color:#1a2035;"></p>
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
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete Social
                        Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong
                            id="delete_social_name"></strong>?</p>
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

        function openEdit(id, platform, url) {
            document.getElementById('editForm').action = `/dashboard/social-links/${id}`;
            document.getElementById('edit_platform').value = platform;
            document.getElementById('edit_url').value = url;

            // Fetch is_active status via AJAX
            fetch(`/dashboard/social-links/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('edit_is_active').checked = data.socialLink.is_active;
                    }
                });

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function openShow(id, platform, url) {
            document.getElementById('show_platform').innerText = platform;
            document.getElementById('show_url').innerHTML = url;
            document.getElementById('show_url').href = url;

            // Set icon
            let iconClass = 'bi bi-';
            if (platform === 'github') iconClass += 'github';
            else if (platform === 'linkedin') iconClass += 'linkedin';
            else if (platform === 'twitter') iconClass += 'twitter-x';
            else if (platform === 'facebook') iconClass += 'facebook';
            else if (platform === 'instagram') iconClass += 'instagram';
            else if (platform === 'youtube') iconClass += 'youtube';
            else if (platform === 'whatsapp') iconClass += 'whatsapp';
            else if (platform === 'telegram') iconClass += 'telegram';
            else iconClass += 'share-fill';

            document.getElementById('show_social_icon').innerHTML = `<i class="${iconClass}"></i>`;
            document.getElementById('show_social_icon').className = `social-icon ${platform}`;

            // Fetch status and created date via AJAX
            fetch(`/dashboard/social-links/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.socialLink.is_active) {
                            document.getElementById('show_status').innerHTML =
                                '<span class="badge-cat" style="background:#d1fae5;color:#065f46;">Active</span>';
                        } else {
                            document.getElementById('show_status').innerHTML =
                                '<span class="badge-cat" style="background:#fee2e2;color:#991b1b;">Inactive</span>';
                        }
                        document.getElementById('show_created').innerHTML = new Date(data.socialLink.created_at)
                            .toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                    }
                });

            new bootstrap.Modal(document.getElementById('showModal')).show();
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete_social_name').innerText = name.charAt(0).toUpperCase() + name.slice(1);
            document.getElementById('deleteForm').action = `/dashboard/social-links/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function applyFilters() {
            const platform = document.getElementById('platformInput').value;
            const status = document.getElementById('statusFilter').value;
            const url = new URL(window.location.href);

            if (platform) {
                url.searchParams.set('platform', platform);
            } else {
                url.searchParams.delete('platform');
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
            window.location.href = '{{ route('dashboard.social-links.index') }}';
        }

        // Auto-open create modal if validation failed
        @if ($errors->any())
            openModal('createModal');
        @endif

        // Allow Enter key to submit filter
        document.getElementById('platformInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    </script>
@endpush
