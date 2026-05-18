@extends('layouts.dashboard')

@section('title', 'Clients')
@section('page-title', 'Clients')
@section('page-subtitle', 'Manage all users and their portfolios')

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

        .user-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .role-admin {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .role-user {
            background: rgba(47, 123, 255, 0.1);
            color: #2563eb;
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

    <div class="d-flex align-items-center justify-content-between mb-4 fade-up">
        <h5 style="font-size:18px;font-weight:700;color:#1a2035;margin:0;">
            Users <small style="font-size:13px;font-weight:400;color:#7a869a;margin-left:8px;">{{ $users->total() }}
                total</small>
        </h5>
        <a href="{{ route('dashboard.clients.create') }}" class="btn-primary-dash">
            <i class="bi bi-plus-lg"></i> Add User
        </a>
    </div>

    <div class="filter-bar fade-up d1">
        <form action="{{ route('dashboard.clients.index') }}" method="GET"
            style="display: flex; gap: 8px; flex-wrap: wrap; width: 100%;">
            <input type="text" name="search" class="search-input" placeholder="Search by name, email..."
                value="{{ request('search') }}">
            <select name="role" class="form-control-dash" style="width: auto; min-width: 120px;">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit" class="filter-btn" style="background: #2f7bff; color: #fff;">Filter</button>
            @if (request('search') || request('role'))
                <a href="{{ route('dashboard.clients.index') }}" class="filter-btn"
                    style="background: #e8edf5; color: #7a869a; border-color: #e8edf5;">Clear</a>
            @endif
        </form>
    </div>

    <div class="card-box fade-up d2">
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email / Username</th>
                        <th>Role</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="muted">{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @php
                                        $avatarUrl =
                                            $user->profile && $user->profile->avatar
                                                ? asset('storage/' . $user->profile->avatar)
                                                : 'https://ui-avatars.com/api/?background=2f7bff&color=fff&size=40&name=' .
                                                    urlencode($user->name);
                                    @endphp
                                    <img src="{{ $avatarUrl }}" class="user-avatar-sm" alt="{{ $user->name }}">
                                    <div style="font-weight:700;color:#1a2035;">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="color:#1a2035; font-size:12px;">{{ $user->email }}</div>
                                @if ($user->username)
                                    <div style="color:#7a869a; font-size:11px;">@ {{ $user->username }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($user->role == 'admin')
                                    <span class="role-badge role-admin">Admin</span>
                                @else
                                    <span class="role-badge role-user">User</span>
                                @endif
                            </td>
                            <td>
                                <div style="color:#7a869a; font-size:12px;">{{ $user->profile->title ?? '—' }}</div>
                            </td>
                            <td>
                                <div style="color:#7a869a; font-size:12px;">{{ $user->profile->location ?? '—' }}</div>
                            </td>
                            <td class="muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <a href="{{ route('dashboard.clients.show', $user->id) }}"
                                        class="action-btn action-view" title="View Full Profile">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('dashboard.clients.edit', $user->id) }}"
                                        class="action-btn action-edit" title="Edit User">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <button class="action-btn action-del"
                                            onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            title="Delete User">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-people-fill"></i>
                                    <p>No users found.</p>
                                    <a href="{{ route('dashboard.clients.create') }}" class="btn-primary-dash">
                                        <i class="bi bi-plus-lg"></i> Add User
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div
                style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="font-size:13px;color:#7a869a;">
                    Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div class="dash-pagination">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade modal-dash" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong
                            id="delete_user_name"></strong>?</p>
                    <p style="font-size:13px;color:#7a869a;">This action cannot be undone. All user data will be permanently
                        removed.</p>
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
        function openDeleteModal(id, name) {
            document.getElementById('delete_user_name').innerText = name;
            document.getElementById('deleteForm').action = `/dashboard/clients/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
