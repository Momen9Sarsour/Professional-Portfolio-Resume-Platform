@extends('layouts.dashboard')

@section('title', $user->name . ' - Profile')
@section('page-title', $user->name)
@section('page-subtitle', 'Complete user profile and portfolio')

@push('styles')
    <style>
        .profile-cover {
            background: linear-gradient(135deg, #1a2035 0%, #2d3a5e 100%);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            margin-bottom: 80px;
        }

        .profile-avatar-lg {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: absolute;
            bottom: -50px;
            left: 30px;
            background: white;
        }

        .profile-info {
            margin-left: 190px;
            color: white;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .profile-title {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 12px;
        }

        .profile-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            opacity: 0.8;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e8edf5;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #2f7bff;
        }

        .stat-label {
            font-size: 12px;
            color: #7a869a;
            margin-top: 5px;
        }

        .section-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e8edf5;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a2035;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 20px;
            color: #2f7bff;
        }

        .skill-badge {
            display: inline-block;
            padding: 6px 14px;
            background: #f4f6fb;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 4px;
        }

        .tech-tag {
            display: inline-block;
            padding: 2px 10px;
            background: #f4f6fb;
            border-radius: 15px;
            font-size: 11px;
            margin: 3px;
        }

        .action-btn-sm {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1.5px solid #2f7bff;
            color: #2f7bff;
        }

        .btn-outline-primary:hover {
            background: #2f7bff;
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            border: 1.5px solid #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: white;
        }

        .btn-outline-success {
            background: transparent;
            border: 1.5px solid #22c55e;
            color: #22c55e;
        }

        .btn-outline-success:hover {
            background: #22c55e;
            color: white;
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
        }
    </style>
@endpush

@section('content')

    {{-- Profile Cover --}}
    <div class="profile-cover">
        @php
            $avatarUrl =
                $profile && $profile->avatar
                    ? asset('storage/' . $profile->avatar)
                    : 'https://ui-avatars.com/api/?background=fff&color=2f7bff&size=150&name=' . urlencode($user->name);
        @endphp
        <img src="{{ $avatarUrl }}" class="profile-avatar-lg" alt="{{ $user->name }}">

        <div class="profile-info">
            <h1 class="profile-name">{{ $user->name }}</h1>
            @if ($profile && $profile->title)
                <div class="profile-title">{{ $profile->title }}</div>
            @endif
            <div class="profile-meta">
                @if ($profile && $profile->location)
                    <span><i class="bi bi-geo-alt-fill"></i> {{ $profile->location }}</span>
                @endif
                @if ($profile && $profile->email)
                    <span><i class="bi bi-envelope-fill"></i> {{ $profile->email }}</span>
                @endif
                @if ($profile && $profile->phone)
                    <span><i class="bi bi-telephone-fill"></i> {{ $profile->phone }}</span>
                @endif
                <span><i class="bi bi-calendar-fill"></i> Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('dashboard.clients.edit', $user->id) }}" class="action-btn-sm btn-outline-primary">
            <i class="bi bi-pencil-fill"></i> Edit User
        </a>
        <a href="{{ route('dashboard.clients.preview-cv', $user->id) }}" target="_blank"
            class="action-btn-sm btn-outline-success">
            <i class="bi bi-eye-fill"></i> View CV
        </a>
        <a href="{{ route('dashboard.clients.download-cv', $user->id) }}" class="action-btn-sm btn-outline-primary">
            <i class="bi bi-download"></i> Download CV
        </a>
        @if ($user->id !== auth()->id())
            <button class="action-btn-sm btn-outline-danger"
                onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                <i class="bi bi-trash-fill"></i> Delete User
            </button>
        @endif
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-number">{{ $projects->count() }}</div>
                <div class="stat-label">Projects</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-number">{{ $skills->count() }}</div>
                <div class="stat-label">Skills</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-number">{{ $experiences->count() }}</div>
                <div class="stat-label">Experiences</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-number">{{ $education->count() }}</div>
                <div class="stat-label">Education</div>
            </div>
        </div>
    </div>

    {{-- Bio Section --}}
    @if ($profile && $profile->bio)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-chat-quote-fill"></i>
                    <span>About</span>
                </div>
            </div>
            <p style="color: #475569; line-height: 1.6;">{{ $profile->bio }}</p>
        </div>
    @endif

    {{-- Skills Section --}}
    @if ($skills->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Skills</span>
                </div>
                <a href="{{ route('dashboard.skills.index') }}?user_id={{ $user->id }}"
                    class="action-btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Manage Skills
                </a>
            </div>
            <div>
                @foreach ($skills as $skill)
                    <div class="skill-badge">
                        {{ $skill->name }}
                        @if ($skill->level)
                            <span style="color: #2f7bff;">({{ $skill->level }}%)</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Experience Section --}}
    @if ($experiences->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>Work Experience</span>
                </div>
                <a href="{{ route('dashboard.experiences.index') }}?user_id={{ $user->id }}"
                    class="action-btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Manage Experiences
                </a>
            </div>
            @foreach ($experiences as $exp)
                <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e8edf5;">
                    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 8px;">
                        <h4 style="font-size: 16px; font-weight: 700; color: #1a2035;">{{ $exp->job_title }}</h4>
                        <span style="font-size: 12px; color: #7a869a;">
                            {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} -
                            {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    <div style="color: #2f7bff; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ $exp->company }}
                    </div>
                    @if ($exp->description)
                        <p style="font-size: 13px; color: #475569; line-height: 1.5;">{{ $exp->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Education Section --}}
    @if ($education->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Education</span>
                </div>
                <a href="{{ route('dashboard.education.index') }}?user_id={{ $user->id }}"
                    class="action-btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Manage Education
                </a>
            </div>
            @foreach ($education as $edu)
                <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e8edf5;">
                    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 8px;">
                        <h4 style="font-size: 16px; font-weight: 700; color: #1a2035;">{{ $edu->degree }}</h4>
                        <span style="font-size: 12px; color: #7a869a;">
                            {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} -
                            {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    <div style="color: #2f7bff; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                        {{ $edu->university }}</div>
                    @if ($edu->description)
                        <p style="font-size: 13px; color: #475569; line-height: 1.5;">{{ $edu->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Projects Section --}}
    @if ($projects->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-folder2-open"></i>
                    <span>Projects</span>
                </div>
                <a href="{{ route('dashboard.projects.index') }}?user_id={{ $user->id }}"
                    class="action-btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Manage Projects
                </a>
            </div>
            <div class="row g-3">
                @foreach ($projects as $project)
                    <div class="col-md-6">
                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #1a2035; margin-bottom: 8px;">
                                {{ $project->title }}</h4>
                            <p style="font-size: 12px; color: #7a869a; margin-bottom: 8px;">
                                {{ Str::limit($project->description, 80) }}</p>
                            @if ($project->technologies)
                                <div>
                                    @foreach (explode(',', $project->technologies) as $tech)
                                        <span class="tech-tag">{{ trim($tech) }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-2">
                                @if ($project->github_link)
                                    <a href="{{ $project->github_link }}" target="_blank"
                                        style="font-size: 12px; margin-right: 12px;">GitHub</a>
                                @endif
                                @if ($project->demo_link)
                                    <a href="{{ $project->demo_link }}" target="_blank" style="font-size: 12px;">Live
                                        Demo</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Social Links Section --}}
    @if ($socialLinks->count() > 0)
        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <i class="bi bi-share-fill"></i>
                    <span>Social Links</span>
                </div>
                <a href="{{ route('dashboard.social-links.index') }}?user_id={{ $user->id }}"
                    class="action-btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Manage Social Links
                </a>
            </div>
            <div class="d-flex flex-wrap gap-3">
                @foreach ($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #f4f6fb; border-radius: 30px; text-decoration: none; color: #1a2035;">
                        <i
                            class="bi bi-{{ strtolower($link->platform) == 'github' ? 'github' : (strtolower($link->platform) == 'linkedin' ? 'linkedin' : 'share-fill') }}"></i>
                        <span style="text-transform: capitalize;">{{ $link->platform }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    <div class="modal fade modal-dash" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-trash-fill me-2"></i>Delete User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#1a2035;">Are you sure you want to delete <strong
                            id="delete_user_name"></strong>?</p>
                    <p style="font-size:13px;color:#7a869a;">This action cannot be undone. All user data will be
                        permanently removed.</p>
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
        function openDeleteModal(id, name) {
            document.getElementById('delete_user_name').innerText = name;
            document.getElementById('deleteForm').action = `/dashboard/clients/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
