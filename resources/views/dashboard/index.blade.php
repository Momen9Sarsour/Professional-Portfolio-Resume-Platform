@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Overview')
@section('page-subtitle', 'Welcome back, {{ Auth::user()->name }}!')

@push('styles')
<style>
    /* ============================================================
       STAT CARDS
    ============================================================ */
    .stat-card-premium {
        background: white;
        border-radius: 20px;
        padding: 20px 18px;
        transition: all 0.3s;
        border: 1px solid rgba(47, 123, 255, 0.08);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #2f7bff, #63b3ed);
        transform: scaleX(0);
        transition: transform 0.4s;
    }

    .stat-card-premium:hover::before {
        transform: scaleX(1);
    }

    .stat-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(47, 123, 255, 0.1);
    }

    .stat-icon-premium {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .stat-number-premium {
        font-size: 28px;
        font-weight: 800;
        color: #1a2035;
        line-height: 1.1;
    }

    .stat-label-premium {
        font-size: 12px;
        color: #7a869a;
        margin-top: 4px;
        font-weight: 500;
    }

    .stat-trend-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 700;
    }

    .trend-up { background: #d1fae5; color: #065f46; }
    .trend-down { background: #fee2e2; color: #991b1b; }

    .stat-icon-blue { background: rgba(47, 123, 255, 0.12); color: #2f7bff; }
    .stat-icon-green { background: rgba(17, 153, 142, 0.12); color: #11998e; }
    .stat-icon-purple { background: rgba(118, 75, 162, 0.12); color: #764ba2; }
    .stat-icon-orange { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .stat-icon-red { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
    .stat-icon-pink { background: rgba(236, 72, 153, 0.12); color: #ec4899; }
    .stat-icon-teal { background: rgba(20, 184, 166, 0.12); color: #14b8a6; }

    /* ============================================================
       TABLE CARD - Same height
    ============================================================ */
    .table-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(47, 123, 255, 0.08);
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .table-card:hover {
        border-color: rgba(47, 123, 255, 0.15);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .table-card .table-responsive-dash {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
    }

    .table-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a2035;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-title i {
        font-size: 16px;
        color: #2f7bff;
    }

    .table-count-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 1px 8px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 600;
    }

    .view-all-btn {
        font-size: 11px;
        color: #2f7bff;
        font-weight: 600;
        text-decoration: none;
        padding: 3px 12px;
        border-radius: 16px;
        border: 1.5px solid #2f7bff;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .view-all-btn:hover {
        background: #2f7bff;
        color: white;
    }

    .table-responsive-dash {
        overflow-x: auto;
        border-radius: 12px;
        flex: 1;
    }

    .table-dash {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        height: 100%;
    }

    .table-dash thead th {
        background: #f8fafc;
        padding: 8px 12px;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e8edf5;
        white-space: nowrap;
    }

    .table-dash tbody td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
        font-size: 12px;
    }

    .table-dash tbody tr:last-child td {
        border-bottom: none;
    }

    .table-dash tbody tr:hover {
        background: #f8fafc;
    }

    .table-dash tbody tr.empty-row td {
        text-align: center;
        padding: 20px !important;
        color: #94a3b8;
        font-size: 12px;
    }

    .table-dash tbody tr.empty-row td i {
        font-size: 24px;
        margin-bottom: 4px;
        opacity: 0.3;
        display: block;
    }

    .status-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    .status-unread { background: #fef3c7; color: #92400e; }
    .status-read { background: #e8edf5; color: #64748b; }

    /* ============================================================
       CHART CARDS
    ============================================================ */
    .chart-card-premium {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(47, 123, 255, 0.08);
        transition: all 0.3s;
        height: 100%;
    }

    .chart-card-premium:hover {
        border-color: rgba(47, 123, 255, 0.15);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chart-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a2035;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-title i {
        color: #2f7bff;
    }

    .chart-container {
        position: relative;
        height: 220px;
        width: 100%;
    }

    .chart-container-sm {
        height: 180px;
    }

    /* ============================================================
       KPI PROGRESS
    ============================================================ */
    .kpi-progress-wrap { margin-top: 10px; }
    .kpi-progress-bar { height: 5px; background: #e8edf5; border-radius: 4px; overflow: hidden; }
    .kpi-progress-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #2f7bff, #63b3ed); transition: width 1s ease; }
    .kpi-progress-label { display: flex; justify-content: space-between; font-size: 10px; color: #94a3b8; margin-top: 3px; }

    /* ============================================================
       WELCOME SECTION
    ============================================================ */
    .welcome-section {
        background: linear-gradient(135deg, #0f172a, #1e3a5f);
        border-radius: 24px;
        padding: 24px 28px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(47, 123, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-content { position: relative; z-index: 1; }
    .welcome-title { font-size: 22px; font-weight: 800; margin-bottom: 2px; }
    .welcome-subtitle { font-size: 12px; opacity: 0.8; margin-bottom: 10px; }
    .welcome-stat { display: inline-flex; align-items: center; gap: 6px; margin-right: 18px; }
    .welcome-stat-number { font-size: 18px; font-weight: 800; }
    .welcome-stat-label { font-size: 11px; opacity: 0.7; }

    /* ============================================================
       QUICK ACTIONS
    ============================================================ */
    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
    }

    .quick-action-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        transition: all 0.3s;
        text-decoration: none;
        color: #1a2035;
        border: 1px solid transparent;
    }

    .quick-action-item:hover {
        background: #2f7bff;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(47, 123, 255, 0.2);
    }

    .quick-action-item i { font-size: 20px; display: block; margin-bottom: 3px; }
    .quick-action-item span { font-size: 10px; font-weight: 600; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-card { animation: fadeUp 0.5s ease forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
    .delay-5 { animation-delay: 0.25s; }
    .delay-6 { animation-delay: 0.3s; }
    .delay-7 { animation-delay: 0.35s; }
    .delay-8 { animation-delay: 0.4s; }

    /* 🔒 User only - restricted access indicator */
    .user-only-badge {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 6px;
    }

    .admin-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 6px;
    }
</style>
@endpush

@section('content')

{{-- Welcome Section --}}
<div class="welcome-section animate-card delay-1">
    <div class="welcome-content">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="welcome-title">
                    👋 Welcome back, {{ Auth::user()->name }}!
                    @if($isAdmin)
                        <span class="admin-badge"><i class="bi bi-shield-fill-check"></i> Admin</span>
                    @else
                        <span class="user-only-badge"><i class="bi bi-person-fill"></i> User</span>
                    @endif
                </h1>
                <p class="welcome-subtitle">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                <div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $totalProjects ?? 0 }}</span>
                        <span class="welcome-stat-label">Projects</span>
                    </div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $totalSkills ?? 0 }}</span>
                        <span class="welcome-stat-label">Skills</span>
                    </div>
                    @if($isAdmin)
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $totalUsers ?? 0 }}</span>
                        <span class="welcome-stat-label">Users</span>
                    </div>
                    @endif
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $unreadMessages ?? 0 }}</span>
                        <span class="welcome-stat-label">Unread</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div style="display:inline-block; background:rgba(255,255,255,0.08); padding:10px 18px; border-radius:14px; backdrop-filter:blur(8px);">
                    <div style="font-size:10px; opacity:0.7;">Completion</div>
                    <div style="font-size:22px; font-weight:800;">{{ $completionRate ?? 0 }}%</div>
                    <div style="height:4px; background:rgba(255,255,255,0.2); border-radius:2px; margin-top:4px;">
                        <div style="height:100%; width:{{ $completionRate ?? 0 }}%; background:#63b3ed; border-radius:2px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-2">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-blue"><i class="bi bi-folder2-open"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $totalProjects ?? 0 }}">0</div>
            <div class="stat-label-premium">Projects</div>
            <div style="font-size:10px; color:#94a3b8; margin-top:4px;">
                <span class="stat-trend-badge {{ $growthRates['trend'] == 'up' ? 'trend-up' : 'trend-down' }}">
                    <i class="bi bi-arrow-{{ $growthRates['trend'] == 'up' ? 'up' : 'down' }}-short"></i>
                    {{ abs($growthRates['projects'] ?? 0) }}%
                </span>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-3">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-green"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $totalSkills ?? 0 }}">0</div>
            <div class="stat-label-premium">Skills</div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-4">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-purple"><i class="bi bi-briefcase-fill"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $totalExperiences ?? 0 }}">0</div>
            <div class="stat-label-premium">Experience</div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-5">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-orange"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $totalEducation ?? 0 }}">0</div>
            <div class="stat-label-premium">Education</div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-6">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-red"><i class="bi bi-envelope-fill"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $unreadMessages ?? 0 }}">0</div>
            <div class="stat-label-premium">Unread</div>
        </div>
    </div>
    @if($isAdmin)
    <div class="col-xl-2 col-lg-4 col-md-4 col-6 animate-card delay-7">
        <div class="stat-card-premium">
            <div class="stat-icon-premium stat-icon-pink"><i class="bi bi-people-fill"></i></div>
            <div class="stat-number-premium counter" data-target="{{ $totalUsers ?? 0 }}">0</div>
            <div class="stat-label-premium">Users</div>
        </div>
    </div>
    @endif
</div>

{{-- KPI & Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6 animate-card delay-1">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-bar-chart-fill"></i> Completion</div>
                <span style="font-size:18px; font-weight:800; color:#2f7bff;">{{ $completionRate ?? 0 }}%</span>
            </div>
            <div class="kpi-progress-wrap">
                <div class="kpi-progress-bar">
                    <div class="kpi-progress-fill" style="width:{{ $completionRate ?? 0 }}%;"></div>
                </div>
                <div class="kpi-progress-label">
                    <span>Profile & Data</span>
                    <span>{{ $completionRate ?? 0 }}%</span>
                </div>
            </div>
            <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap; font-size:10px; color:#94a3b8;">
                <span><i class="bi bi-check-circle-fill" style="color:#22c55e;"></i> Bio: {{ Auth::user()->profile && Auth::user()->profile->bio ? '✅' : '❌' }}</span>
                <span><i class="bi bi-check-circle-fill" style="color:#22c55e;"></i> Avatar: {{ Auth::user()->profile && Auth::user()->profile->avatar ? '✅' : '❌' }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 animate-card delay-2">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-graph-up-arrow"></i> Engagement</div>
                <span style="font-size:18px; font-weight:800; color:#11998e;">{{ $engagementRate ?? 0 }}%</span>
            </div>
            <div class="kpi-progress-wrap">
                <div class="kpi-progress-bar">
                    <div class="kpi-progress-fill" style="width:{{ $engagementRate ?? 0 }}%; background:linear-gradient(90deg,#11998e,#38ef7d);"></div>
                </div>
                <div class="kpi-progress-label">
                    <span>Activity Level</span>
                    <span>{{ $engagementRate ?? 0 }}%</span>
                </div>
            </div>
            <div style="margin-top:10px; display:flex; gap:12px; flex-wrap:wrap; font-size:10px; color:#94a3b8;">
                <span>🔥 Projects: {{ $totalProjects ?? 0 }}</span>
                <span>⚡ Skills: {{ $totalSkills ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 animate-card delay-3">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-check-circle-fill"></i> Success Rate</div>
                <span style="font-size:18px; font-weight:800; color:#764ba2;">{{ $projectSuccessRate ?? 0 }}%</span>
            </div>
            <div class="kpi-progress-wrap">
                <div class="kpi-progress-bar">
                    <div class="kpi-progress-fill" style="width:{{ $projectSuccessRate ?? 0 }}%; background:linear-gradient(90deg,#764ba2,#667eea);"></div>
                </div>
                <div class="kpi-progress-label">
                    <span>Active Projects</span>
                    <span>{{ $projectSuccessRate ?? 0 }}%</span>
                </div>
            </div>
            <div style="margin-top:10px; display:flex; gap:12px; flex-wrap:wrap; font-size:10px; color:#94a3b8;">
                <span>✅ Active: {{ $totalProjects > 0 ? round(($projectSuccessRate / 100) * $totalProjects) : 0 }}</span>
                <span>📦 Total: {{ $totalProjects ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7 animate-card delay-1">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-graph-up"></i> Monthly Projects</div>
            </div>
            <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5 animate-card delay-2">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-pie-chart-fill"></i> Projects by Category</div>
            </div>
            <div class="chart-container"><canvas id="categoryChart"></canvas></div>
        </div>
    </div>
</div>

{{-- More Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-card delay-3">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-bar-chart-steps"></i> Skills Distribution</div>
            </div>
            <div class="chart-container"><canvas id="skillsChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6 animate-card delay-4">
        <div class="chart-card-premium">
            <div class="chart-header">
                <div class="chart-title"><i class="bi bi-activity"></i> Weekly Activity</div>
            </div>
            <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 📊 RECENT DATA TABLES - Side by Side --}}
{{-- ============================================================ --}}

{{-- Row 1: Projects & Skills --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-card delay-1">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-folder2-open"></i>
                    <span>Recent Projects</span>
                    <span class="table-count-badge">{{ $totalProjects ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.projects.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:35%">Title</th><th style="width:25%">Category</th><th style="width:30%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentProjects ?? [] as $project)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($project->title, 20) }}</td>
                            <td>{{ Str::limit($project->category ?? '—', 15) }}</td>
                            <td><span class="status-badge {{ $project->is_active ? 'status-active' : 'status-inactive' }}">{{ $project->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="4"><i class="bi bi-folder2-open"></i>No projects yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentProjects ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="4" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 animate-card delay-2">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Recent Skills</span>
                    <span class="table-count-badge">{{ $totalSkills ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.skills.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:30%">Name</th><th style="width:25%">Category</th><th style="width:20%">Level</th><th style="width:15%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentSkills ?? [] as $skill)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($skill->name, 18) }}</td>
                            <td>{{ Str::limit($skill->category ?? '—', 12) }}</td>
                            <td>{{ $skill->level ?? 0 }}%</td>
                            <td><span class="status-badge {{ $skill->is_active ? 'status-active' : 'status-inactive' }}">{{ $skill->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5"><i class="bi bi-lightning-charge"></i>No skills yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentSkills ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="5" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Experiences & Education --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-card delay-3">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>Recent Experiences</span>
                    <span class="table-count-badge">{{ $totalExperiences ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.experiences.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:30%">Job Title</th><th style="width:25%">Company</th><th style="width:20%">Period</th><th style="width:15%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentExperiences ?? [] as $exp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($exp->job_title, 18) }}</td>
                            <td>{{ Str::limit($exp->company, 15) }}</td>
                            <td>{{ $exp->start_date->format('M Y') }}<br><small style="font-size:9px;color:#94a3b8;">{{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}</small></td>
                            <td><span class="status-badge {{ $exp->is_active ? 'status-active' : 'status-inactive' }}">{{ $exp->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5"><i class="bi bi-briefcase"></i>No experiences yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentExperiences ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="5" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 animate-card delay-4">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Recent Education</span>
                    <span class="table-count-badge">{{ $totalEducation ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.education.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:30%">Degree</th><th style="width:25%">University</th><th style="width:20%">Period</th><th style="width:15%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentEducation ?? [] as $edu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($edu->degree, 18) }}</td>
                            <td>{{ Str::limit($edu->university, 15) }}</td>
                            <td>{{ $edu->start_date->format('M Y') }}<br><small style="font-size:9px;color:#94a3b8;">{{ $edu->end_date ? $edu->end_date->format('M Y') : 'Present' }}</small></td>
                            <td><span class="status-badge {{ $edu->is_active ? 'status-active' : 'status-inactive' }}">{{ $edu->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5"><i class="bi bi-mortarboard"></i>No education yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentEducation ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="5" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Social Links & Users/Messages --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-card delay-5">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-share-fill"></i>
                    <span>Recent Social Links</span>
                    <span class="table-count-badge">{{ $totalSocialLinks ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.social-links.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:30%">Platform</th><th style="width:45%">URL</th><th style="width:15%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentSocialLinks ?? [] as $link)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($link->platform) }}</td>
                            <td>{{ Str::limit($link->url, 25) }}</td>
                            <td><span class="status-badge {{ $link->is_active ? 'status-active' : 'status-inactive' }}">{{ $link->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="4"><i class="bi bi-share"></i>No social links yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentSocialLinks ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="4" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔒 صلاحيات: المستخدم العادي يشوف رسائله فقط، الإدمن يشوف المستخدمين --}}
    <div class="col-lg-6 animate-card delay-6">
        @if($isAdmin)
        {{-- Admin: Users Table --}}
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-people-fill"></i>
                    <span>Recent Users</span>
                    <span class="table-count-badge">{{ $totalUsers ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.clients.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:30%">Name</th><th style="width:30%">Email</th><th style="width:15%">Role</th><th style="width:15%">Joined</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($user->name, 15) }}</td>
                            <td>{{ Str::limit($user->email, 18) }}</td>
                            <td><span class="status-badge {{ $user->role == 'admin' ? 'status-active' : 'status-read' }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5"><i class="bi bi-people"></i>No users yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentUsers ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="5" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- User: Messages Table (only if they have messages) --}}
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Your Messages</span>
                    <span class="table-count-badge">{{ $totalMessages ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.messages.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:35%">From</th><th style="width:30%">Message</th><th style="width:25%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentMessages ?? [] as $msg)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($msg->name, 18) }}</td>
                            <td>{{ Str::limit($msg->message, 20) }}</td>
                            <td><span class="status-badge {{ $msg->is_read ? 'status-read' : 'status-unread' }}">{{ $msg->is_read ? 'Read' : 'Unread' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="4"><i class="bi bi-envelope"></i>No messages yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentMessages ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="4" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Row 4: Messages (Admin only) or Quick Actions (User) --}}
@if($isAdmin)
<div class="row g-3 mb-4">
    <div class="col-lg-6 animate-card delay-7">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Recent Messages</span>
                    <span class="table-count-badge">{{ $totalMessages ?? 0 }}</span>
                </div>
                <a href="{{ route('dashboard.messages.index') }}" class="view-all-btn">View All →</a>
            </div>
            <div class="table-responsive-dash">
                <table class="table-dash">
                    <thead>
                        <tr><th style="width:10%">#</th><th style="width:25%">From</th><th style="width:25%">Email</th><th style="width:25%">Message</th><th style="width:15%">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentMessages ?? [] as $msg)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($msg->name, 15) }}</td>
                            <td>{{ Str::limit($msg->email, 18) }}</td>
                            <td>{{ Str::limit($msg->message, 18) }}</td>
                            <td><span class="status-badge {{ $msg->is_read ? 'status-read' : 'status-unread' }}">{{ $msg->is_read ? 'Read' : 'Unread' }}</span></td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="5"><i class="bi bi-envelope"></i>No messages yet</td></tr>
                        @endforelse
                        @php
                            $emptyRows = 5 - ($recentMessages ?? collect())->count();
                        @endphp
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr><td colspan="5" style="height:38px; background:transparent; border:none;"></td></tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 animate-card delay-8">
        <div class="table-card" style="justify-content:center; align-items:center; min-height:250px;">
            <div style="text-align:center; padding:20px;">
                <i class="bi bi-grid-3x3-gap-fill" style="font-size:48px; color:#2f7bff; opacity:0.3; display:block; margin-bottom:12px;"></i>
                <h5 style="font-weight:700; color:#1a2035;">Quick Actions</h5>
                <p style="font-size:13px; color:#7a869a; margin-bottom:16px;">Manage your content from one place</p>
                <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                    <a href="{{ route('dashboard.projects.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-folder-plus"></i> Project
                    </a>
                    <a href="{{ route('dashboard.skills.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-lightning-charge"></i> Skill
                    </a>
                    <a href="{{ route('dashboard.experiences.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-briefcase"></i> Experience
                    </a>
                    <a href="{{ route('dashboard.education.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-mortarboard"></i> Education
                    </a>
                    <a href="{{ route('dashboard.clients.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-person-plus"></i> User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
{{-- User: Quick Actions (smaller version) --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 mx-auto animate-card delay-7">
        <div class="table-card" style="justify-content:center; align-items:center; min-height:200px;">
            <div style="text-align:center; padding:20px;">
                <i class="bi bi-grid-3x3-gap-fill" style="font-size:40px; color:#2f7bff; opacity:0.3; display:block; margin-bottom:12px;"></i>
                <h5 style="font-weight:700; color:#1a2035;">Quick Actions</h5>
                <p style="font-size:13px; color:#7a869a; margin-bottom:16px;">Manage your content</p>
                <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                    <a href="{{ route('dashboard.projects.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-folder-plus"></i> Project
                    </a>
                    <a href="{{ route('dashboard.skills.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-lightning-charge"></i> Skill
                    </a>
                    <a href="{{ route('dashboard.experiences.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-briefcase"></i> Experience
                    </a>
                    <a href="{{ route('dashboard.education.index') }}" class="view-all-btn" style="background:#2f7bff; color:white; border-color:#2f7bff;">
                        <i class="bi bi-mortarboard"></i> Education
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    document.querySelectorAll('.counter').forEach(counter => {
        const target = parseInt(counter.dataset.target);
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;
        const update = () => {
            current += step;
            if (current < target) {
                counter.innerText = Math.floor(current);
                requestAnimationFrame(update);
            } else {
                counter.innerText = target;
            }
        };
        update();
    });

    const monthlyLabels = @json($monthlyStats['labels'] ?? []);
    const monthlyData = @json($monthlyStats['projects'] ?? []);
    const catLabels = @json(array_keys($projectsByCategory ?? []));
    const catData = @json(array_values($projectsByCategory ?? []));
    const skillNames = @json(array_keys($skillsByLevel ?? []));
    const skillLevels = @json(array_values($skillsByLevel ?? []));
    const weekLabels = @json(array_keys($weeklyActivity ?? []));
    const weekData = @json(array_values($weeklyActivity ?? []));
    const colors = ['#2f7bff','#11998e','#f59e0b','#ef4444','#8b5cf6','#f97316'];

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: { labels: monthlyLabels.length ? monthlyLabels : ['No Data'], datasets: [{ label: 'Projects', data: monthlyData.length ? monthlyData : [0], backgroundColor: 'rgba(47,123,255,0.7)', borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#e8edf5' }, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: { labels: catLabels.length ? catLabels : ['No Data'], datasets: [{ data: catData.length ? catData : [1], backgroundColor: catLabels.length ? colors.slice(0, catLabels.length) : ['#e8edf5'], borderWidth: 0 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } } }
    });

    new Chart(document.getElementById('skillsChart'), {
        type: 'bar',
        data: { labels: skillNames.length ? skillNames : ['No Skills'], datasets: [{ label: 'Level (%)', data: skillLevels.length ? skillLevels : [0], backgroundColor: 'rgba(47,123,255,0.7)', borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100, grid: { color: '#e8edf5' } } } }
    });

    new Chart(document.getElementById('weeklyChart'), {
        type: 'line',
        data: { labels: weekLabels.length ? weekLabels : ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], datasets: [{ label: 'Activities', data: weekData.length ? weekData : [0,0,0,0,0,0,0], borderColor: '#2f7bff', backgroundColor: 'rgba(47,123,255,0.1)', borderWidth: 2, fill: true, tension: 0.4, pointBackgroundColor: '#2f7bff' }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#e8edf5' }, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
    });
});
</script>
@endpush
