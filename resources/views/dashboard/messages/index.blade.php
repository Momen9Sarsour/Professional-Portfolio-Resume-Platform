@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Manage incoming messages from your portfolio')

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

        .unread-row {
            background: rgba(47, 123, 255, 0.02);
            position: relative;
        }

        .unread-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #2f7bff;
        }

        .unread-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            background: #2f7bff;
            color: white;
        }

        .read-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            background: #e8edf5;
            color: #7a869a;
        }

        .message-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #7a869a;
            font-size: 12px;
        }

        .sender-name {
            font-weight: 700;
            color: #1a2035;
            font-size: 13.5px;
        }

        .sender-email {
            font-size: 11px;
            color: #2f7bff;
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

        .action-mark {
            background: rgba(47, 123, 255, 0.1);
            color: #2f7bff;
        }

        .action-mark:hover {
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

        .message-content {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            font-size: 14px;
            color: #1a2035;
        }

        .info-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e8edf5;
        }

        .info-item {
            flex: 1;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #7a869a;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1a2035;
        }
    </style>
@endpush

@section('content')

    {{-- ===== TOP ROW ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-4 fade-up">
        <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
            Messages <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $messages->total() }}
                total</small>
        </h5>
        <div>
            @php
                $unreadCount = \App\Models\Message::where('is_read', false)->count();
            @endphp
            @if ($unreadCount > 0)
                <span class="unread-badge" style="background: #ef4444; margin-right: 12px;">{{ $unreadCount }} unread</span>
            @endif
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar fade-up d1">
        <button class="filter-btn {{ !request('search') && !request('status') ? 'active' : '' }}"
            onclick="filterBy('')">All</button>
        <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email or message..."
            value="{{ request('search') }}">
        <select id="statusFilter" class="form-control-dash" style="width: auto; min-width: 120px;">
            <option value="">All Status</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Unread</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Read</option>
        </select>
        <button class="filter-btn" onclick="applyFilters()" style="background: #2f7bff; color: #fff;">Filter</button>
        @if (request('search') || request('status'))
            <button class="filter-btn" onclick="clearFilters()"
                style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</button>
        @endif
    </div>

    {{-- ===== MESSAGES TABLE ===== --}}
    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sender</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ !$message->is_read ? 'unread-row' : '' }}">
                            <td class="muted">{{ $messages->firstItem() + $loop->index }} </td>
                            <td>
                                <div class="sender-name">{{ $message->name }}</div>
                                <div class="sender-email">{{ $message->email }}</div>
                            </td>
                            <td>
                                <div class="message-preview">{{ Str::limit($message->message, 80) }}</div>
                            </td>
                            <td>
                                @if (!$message->is_read)
                                    <span class="unread-badge">Unread</span>
                                @else
                                    <span class="read-badge">Read</span>
                                @endif
                            </td>
                            <td class="muted">{{ $message->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <button class="action-btn action-view" onclick="openShow({{ $message->id }})">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    @if (!$message->is_read)
                                        <form action="{{ route('dashboard.messages.read', $message->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="action-btn action-mark" title="Mark as read">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button class="action-btn action-del"
                                        onclick="openDeleteModal({{ $message->id }}, '{{ addslashes($message->name) }}')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-envelope-paper"></i>
                                    <p>No messages yet. Messages from your portfolio contact form will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($messages->hasPages())
            <div
                style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }} messages
                </div>
                <div class="dash-pagination">
                    {{ $messages->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ===== SHOW MODAL ===== --}}
    <div class="modal fade modal-dash" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-envelope-paper-fill me-2"></i>Message
                        Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">From</div>
                            <div class="info-value" id="show_name"></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value" id="show_email"></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Received</div>
                            <div class="info-value" id="show_date"></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value" id="show_status"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">Message</div>
                        <div class="message-content" id="show_message"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light-dash" data-bs-dismiss="modal">Close</button>
                    <form id="markReadForm" method="POST" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-primary-dash" id="markReadBtn">
                            <i class="bi bi-check-lg me-1"></i> Mark as Read
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DELETE CONFIRMATION MODAL ===== --}}
    <div class="modal fade modal-dash" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete Message
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete message from <strong
                            id="delete_sender_name"></strong>?</p>
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

        function openShow(id) {
            fetch(`/dashboard/messages/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const msg = data.message;
                        document.getElementById('show_name').innerText = msg.name;
                        document.getElementById('show_email').innerHTML =
                            `<a href="mailto:${msg.email}" style="color:#2f7bff;">${msg.email}</a>`;
                        document.getElementById('show_date').innerText = new Date(msg.created_at).toLocaleString();
                        document.getElementById('show_message').innerText = msg.message;

                        if (msg.is_read) {
                            document.getElementById('show_status').innerHTML = '<span class="read-badge">Read</span>';
                            document.getElementById('markReadBtn').style.display = 'none';
                        } else {
                            document.getElementById('show_status').innerHTML =
                                '<span class="unread-badge">Unread</span>';
                            document.getElementById('markReadBtn').style.display = 'inline-flex';
                            document.getElementById('markReadForm').action = `/dashboard/messages/${id}/read`;
                        }

                        new bootstrap.Modal(document.getElementById('showModal')).show();
                    }
                });
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete_sender_name').innerText = name;
            document.getElementById('deleteForm').action = `/dashboard/messages/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
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

            if (status !== '') {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function clearFilters() {
            window.location.href = '{{ route('dashboard.messages.index') }}';
        }

        // Allow Enter key to submit filter
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    </script>
@endpush
