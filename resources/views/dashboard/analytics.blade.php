@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics Dashboard')
@section('page-subtitle', 'Real-time insights and advanced statistics')

@push('styles')
<style>
    /* ============================================================
       PREMIUM STAT CARDS
    ============================================================ */
    .premium-stat-card {
        background: white;
        border-radius: 28px;
        padding: 24px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(47, 123, 255, 0.1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .premium-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(47, 123, 255, 0.05) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.4s;
    }

    .premium-stat-card:hover::before {
        opacity: 1;
    }

    .premium-stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(47, 123, 255, 0.3);
    }

    .stat-gradient-icon {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        background: linear-gradient(135deg, #2f7bff, #11998e);
        color: white;
        box-shadow: 0 10px 20px rgba(47, 123, 255, 0.3);
    }

    .stat-number-premium {
        font-size: 38px;
        font-weight: 800;
        color: #1a2035;
        line-height: 1.2;
        font-family: 'Plus Jakarta Sans', monospace;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .trend-up {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .trend-down {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .trend-neutral {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    /* ============================================================
       PREMIUM CHART CARDS
    ============================================================ */
    .premium-chart-card {
        background: white;
        border-radius: 28px;
        padding: 24px;
        border: 1px solid rgba(47, 123, 255, 0.1);
        transition: all 0.3s;
        height: 100%;
    }

    .premium-chart-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        border-color: rgba(47, 123, 255, 0.2);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .chart-title-premium {
        font-size: 18px;
        font-weight: 800;
        color: #1a2035;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-title-premium i {
        font-size: 22px;
        color: #2f7bff;
        background: rgba(47, 123, 255, 0.1);
        padding: 8px;
        border-radius: 14px;
    }

    .chart-actions {
        display: flex;
        gap: 8px;
    }

    .chart-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f4f6fb;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: #7a869a;
    }

    .chart-action-btn:hover {
        background: #2f7bff;
        color: white;
        transform: scale(1.05);
    }

    .chart-container-premium {
        position: relative;
        height: 320px;
        width: 100%;
    }

    /* ============================================================
       KPI CARDS
    ============================================================ */
    .kpi-card {
        background: linear-gradient(135deg, #1a2035, #2d3a5e);
        border-radius: 24px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .kpi-label {
        font-size: 13px;
        opacity: 0.7;
        margin-bottom: 8px;
    }

    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .kpi-progress {
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 12px;
    }

    .kpi-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #2f7bff, #11998e);
        border-radius: 2px;
        width: 0%;
        transition: width 1s ease;
    }

    /* ============================================================
       DATA TABLE
    ============================================================ */
    .data-table-wrapper {
        overflow-x: auto;
        border-radius: 20px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .data-table th {
        background: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: #1a2035;
        border-bottom: 2px solid #e8edf5;
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #e8edf5;
        color: #475569;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    /* ============================================================
       FILTER BAR
    ============================================================ */
    .filter-bar-premium {
        background: white;
        border-radius: 20px;
        padding: 16px 20px;
        margin-bottom: 28px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
        border: 1px solid #e8edf5;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        padding: 6px 16px;
        border-radius: 50px;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #7a869a;
    }

    .filter-select {
        border: none;
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: #1a2035;
        padding: 6px 0;
        outline: none;
        cursor: pointer;
    }

    /* ============================================================
       ANIMATIONS
    ============================================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }

    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
    .delay-5 { animation-delay: 0.25s; }

    /* Fullscreen mode */
    .chart-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        background: white;
        padding: 40px;
        border-radius: 0;
    }

    .chart-fullscreen .chart-container-premium {
        height: calc(100vh - 120px);
    }
</style>
@endpush

@section('content')

{{-- Filter Bar --}}
<div class="filter-bar-premium animate-card delay-1">
    <div class="filter-group">
        <i class="bi bi-calendar3 text-muted"></i>
        <label>Date Range</label>
        <select id="dateRange" class="filter-select" onchange="updateDashboard()">
            <option value="7">Last 7 days</option>
            <option value="30" selected>Last 30 days</option>
            <option value="90">Last 3 months</option>
            <option value="365">Last year</option>
        </select>
    </div>
    <div class="filter-group">
        <i class="bi bi-diagram-3 text-muted"></i>
        <label>Chart Type</label>
        <select id="chartType" class="filter-select" onchange="updateChartType()">
            <option value="line">Line Chart</option>
            <option value="bar">Bar Chart</option>
            <option value="area">Area Chart</option>
        </select>
    </div>
    <div class="filter-group">
        <i class="bi bi-download text-muted"></i>
        <label>Export</label>
        <select id="exportFormat" class="filter-select" onchange="exportData()">
            <option value="">Select format</option>
            <option value="pdf">PDF Report</option>
            <option value="excel">Excel</option>
            <option value="csv">CSV</option>
            <option value="json">JSON</option>
            <option value="png">PNG Image</option>
        </select>
    </div>
    <div class="filter-group">
        <button class="btn-export" onclick="shareReport()" style="background: none; border: none;">
            <i class="bi bi-share-fill" style="color: #2f7bff;"></i> Share
        </button>
    </div>
    <div class="ms-auto">
        <span class="text-muted" style="font-size: 12px;">
            <i class="bi bi-arrow-repeat me-1"></i> Real-time updates
        </span>
    </div>
</div>

{{-- Premium Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6 animate-card delay-2">
        <div class="premium-stat-card" onclick="scrollToChart('usersChart')">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-gradient-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $growthRate['users'] ?? 12 }}%</div>
            </div>
            <div class="stat-number-premium counter" data-target="{{ $totalUsers ?? 0 }}">0</div>
            <div class="stat-label-analytics mt-2">Total Users</div>
            <div class="mt-2">
                <small class="text-muted">+{{ $newUsersThisMonth ?? 0 }} this month</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 animate-card delay-3">
        <div class="premium-stat-card" onclick="scrollToChart('projectsChart')">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-gradient-icon"><i class="bi bi-folder2-open"></i></div>
                <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $growthRate['projects'] ?? 8 }}%</div>
            </div>
            <div class="stat-number-premium counter" data-target="{{ $totalProjects ?? 0 }}">0</div>
            <div class="stat-label-analytics mt-2">Total Projects</div>
            <div class="mt-2">
                <small class="text-muted">{{ $activeProjects ?? 0 }} active • {{ $inactiveProjects ?? 0 }} inactive</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 animate-card delay-4">
        <div class="premium-stat-card" onclick="scrollToChart('skillsChart')">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-gradient-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div class="stat-trend trend-up"><i class="bi bi-arrow-up-short"></i> +{{ $growthRate['skills'] ?? 15 }}%</div>
            </div>
            <div class="stat-number-premium counter" data-target="{{ $totalSkills ?? 0 }}">0</div>
            <div class="stat-label-analytics mt-2">Total Skills</div>
            <div class="mt-2">
                <small class="text-muted">{{ number_format($averageSkillLevel ?? 0, 1) }}% avg level</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 animate-card delay-5">
        <div class="premium-stat-card" onclick="scrollToChart('messagesChart')">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-gradient-icon"><i class="bi bi-envelope-fill"></i></div>
                <div class="stat-trend {{ ($unreadMessages ?? 0) > 0 ? 'trend-up' : 'trend-neutral' }}">
                    <i class="bi bi-{{ ($unreadMessages ?? 0) > 0 ? 'arrow-up-short' : 'dot' }}"></i>
                    {{ $unreadMessages ?? 0 }} unread
                </div>
            </div>
            <div class="stat-number-premium counter" data-target="{{ $totalMessages ?? 0 }}">0</div>
            <div class="stat-label-analytics mt-2">Total Messages</div>
            <div class="mt-2">
                <small class="text-muted">Response rate: {{ $responseRate ?? 0 }}%</small>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6 animate-card delay-1">
        <div class="kpi-card">
            <div class="kpi-label">Completion Rate</div>
            <div class="kpi-value">{{ $completionRate ?? 85 }}%</div>
            <div class="kpi-progress">
                <div class="kpi-progress-bar" style="width: {{ $completionRate ?? 85 }}%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-card delay-2">
        <div class="kpi-card" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="kpi-label">User Engagement</div>
            <div class="kpi-value">{{ $engagementRate ?? 72 }}%</div>
            <div class="kpi-progress">
                <div class="kpi-progress-bar" style="width: {{ $engagementRate ?? 72 }}%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-card delay-3">
        <div class="kpi-card" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
            <div class="kpi-label">Project Success</div>
            <div class="kpi-value">{{ $projectSuccessRate ?? 92 }}%</div>
            <div class="kpi-progress">
                <div class="kpi-progress-bar" style="width: {{ $projectSuccessRate ?? 92 }}%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-card delay-4">
        <div class="kpi-card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <div class="kpi-label">Growth Rate</div>
            <div class="kpi-value">+{{ $annualGrowth ?? 24 }}%</div>
            <div class="kpi-progress">
                <div class="kpi-progress-bar" style="width: {{ min($annualGrowth ?? 24, 100) }}%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Main Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8 animate-card delay-1">
        <div class="premium-chart-card" id="usersChartCard">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-graph-up"></i>
                    <span>User Acquisition & Growth</span>
                </div>
                <div class="chart-actions">
                    <button class="chart-action-btn" onclick="fullscreenChart('usersChart')" title="Fullscreen">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                    <button class="chart-action-btn" onclick="downloadChart('usersChart', 'users-chart')" title="Download">
                        <i class="bi bi-download"></i>
                    </button>
                    <button class="chart-action-btn" onclick="toggleChartLegend('usersChart')" title="Toggle Legend">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 animate-card delay-2">
        <div class="premium-chart-card" id="projectsChartCard">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Projects by Category</span>
                </div>
                <div class="chart-actions">
                    <button class="chart-action-btn" onclick="fullscreenChart('projectsCategoryChart')">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                    <button class="chart-action-btn" onclick="downloadChart('projectsCategoryChart', 'category-chart')">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="projectsCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Second Row Charts --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6 animate-card delay-3">
        <div class="premium-chart-card" id="skillsChartCard">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-bar-chart-steps"></i>
                    <span>Skills Distribution by Level</span>
                </div>
                <div class="chart-actions">
                    <button class="chart-action-btn" onclick="fullscreenChart('skillsLevelChart')">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                    <button class="chart-action-btn" onclick="downloadChart('skillsLevelChart', 'skills-chart')">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="skillsLevelChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 animate-card delay-4">
        <div class="premium-chart-card" id="messagesChartCard">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-envelope-paper"></i>
                    <span>Messages & Response Rate</span>
                </div>
                <div class="chart-actions">
                    <button class="chart-action-btn" onclick="fullscreenChart('messagesTrendChart')">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                    <button class="chart-action-btn" onclick="downloadChart('messagesTrendChart', 'messages-chart')">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="messagesTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Third Row - Advanced Charts --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5 animate-card delay-1">
        <div class="premium-chart-card">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Project Status Distribution</span>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="projectsStatusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-7 animate-card delay-2">
        <div class="premium-chart-card">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-radar"></i>
                    <span>Performance Radar</span>
                </div>
            </div>
            <div class="chart-container-premium">
                <canvas id="performanceRadarChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity Section --}}
<div class="row g-4">
    <div class="col-lg-6 animate-card delay-3">
        <div class="premium-chart-card">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-clock-history"></i>
                    <span>Recent Activity</span>
                </div>
                <a href="{{ route('dashboard.projects.index') }}" style="font-size: 12px; color: #2f7bff;">View all →</a>
            </div>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr><th>Type</th><th>Title/Name</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody id="recentActivityTable">
                        @foreach($recentActivities ?? [] as $activity)
                        <tr>
                            <td><span class="activity-badge" style="background: rgba(47,123,255,0.1); color:#2f7bff;">{{ $activity->type }}</span></td>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                            <td><span class="stat-trend trend-up" style="padding:2px 8px;">New</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 animate-card delay-4">
        <div class="premium-chart-card">
            <div class="chart-header">
                <div class="chart-title-premium">
                    <i class="bi bi-trophy-fill"></i>
                    <span>Top Performing Skills</span>
                </div>
            </div>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead><tr><th>Skill</th><th>Level</th><th>Progress</th></tr></thead>
                    <tbody>
                        @foreach($topSkills ?? [] as $skill)
                        <tr>
                            <td>{{ $skill->name }}</td>
                            <td>{{ $skill->level ?? 0 }}%</td>
                            <td>
                                <div style="width:100px; background:#e8edf5; border-radius:10px; height:6px;">
                                    <div style="width:{{ $skill->level ?? 0 }}%; background:#2f7bff; border-radius:10px; height:6px;"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script>
    // ============================================================
    // COUNTER ANIMATION
    // ============================================================
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.innerText = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });
    }

    // ============================================================
    // CHART INSTANCES
    // ============================================================
    let usersChart, categoryChart, skillsChart, statusChart, messagesChart, radarChart;
    let currentChartType = 'line';

    // Users Chart Data
    const usersLabels = @json($monthlyLabels ?? []);
    const usersData = @json($monthlyUsers ?? []);
    const usersDataPrevious = @json($previousMonthlyUsers ?? []);

    function initUsersChart() {
        const ctx = document.getElementById('usersChart').getContext('2d');
        if (usersChart) usersChart.destroy();
        let chartConfig = {
            type: currentChartType,
            data: {
                labels: usersLabels,
                datasets: [
                    {
                        label: 'Current Year',
                        data: usersData,
                        borderColor: '#2f7bff',
                        backgroundColor: currentChartType === 'line' ? 'rgba(47, 123, 255, 0.1)' : '#2f7bff',
                        borderWidth: 3,
                        fill: currentChartType === 'area' || currentChartType === 'line',
                        tension: 0.4,
                        pointBackgroundColor: '#2f7bff',
                        pointBorderColor: 'white',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    },
                    {
                        label: 'Previous Year',
                        data: usersDataPrevious,
                        borderColor: '#11998e',
                        backgroundColor: currentChartType === 'line' ? 'rgba(17, 153, 142, 0.1)' : '#11998e',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#11998e',
                        pointBorderColor: 'white',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { size: 11 } } },
                    tooltip: { backgroundColor: '#1a2035', titleColor: 'white', bodyColor: '#94a3b8', mode: 'index', intersect: false }
                },
                scales: { y: { beginAtZero: true, grid: { color: '#e8edf5' }, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
            }
        };
        usersChart = new Chart(ctx, chartConfig);
    }

    function initCategoryChart() {
        const ctx = document.getElementById('projectsCategoryChart').getContext('2d');
        if (categoryChart) categoryChart.destroy();
        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels ?? []),
                datasets: [{ data: @json($categoryData ?? []), backgroundColor: ['#2f7bff', '#11998e', '#f59e0b', '#ef4444', '#8b5cf6'], borderWidth: 0, hoverOffset: 10 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / @json(array_sum($categoryData ?? [0]))) * 100).toFixed(1)}%)` } } } }
        });
    }

    function initSkillsChart() {
        const ctx = document.getElementById('skillsLevelChart').getContext('2d');
        if (skillsChart) skillsChart.destroy();
        skillsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($skillNames ?? []),
                datasets: [{ label: 'Skill Level (%)', data: @json($skillLevels ?? []), backgroundColor: 'rgba(47, 123, 255, 0.7)', borderRadius: 8, borderSkipped: false }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => `${ctx.raw}%` } } }, scales: { y: { beginAtZero: true, max: 100, grid: { color: '#e8edf5' }, title: { display: true, text: 'Level (%)' } } } }
        });
    }

    function initStatusChart() {
        const ctx = document.getElementById('projectsStatusChart').getContext('2d');
        if (statusChart) statusChart.destroy();
        statusChart = new Chart(ctx, {
            type: 'pie',
            data: { labels: ['Active Projects', 'Inactive Projects'], datasets: [{ data: [{{ $activeProjects ?? 0 }}, {{ $inactiveProjects ?? 0 }}], backgroundColor: ['#22c55e', '#ef4444'], borderWidth: 0, hoverOffset: 10 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${((ctx.raw / ({{ $activeProjects ?? 0 }} + {{ $inactiveProjects ?? 0 }})) * 100).toFixed(1)}%)` } } } }
        });
    }

    function initMessagesChart() {
        const ctx = document.getElementById('messagesTrendChart').getContext('2d');
        if (messagesChart) messagesChart.destroy();
        messagesChart = new Chart(ctx, {
            type: 'line',
            data: { labels: @json($monthlyLabels ?? []), datasets: [{ label: 'Messages Received', data: @json($monthlyMessages ?? []), borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.1)', borderWidth: 3, fill: true, tension: 0.4 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, grid: { color: '#e8edf5' } } } }
        });
    }

    function initRadarChart() {
        const ctx = document.getElementById('performanceRadarChart').getContext('2d');
        if (radarChart) radarChart.destroy();
        radarChart = new Chart(ctx, {
            type: 'radar',
            data: { labels: ['Projects', 'Skills', 'Experience', 'Education', 'Messages', 'Growth'], datasets: [{ label: 'Performance Score', data: [{{ $projectScore ?? 85 }}, {{ $skillScore ?? 78 }}, {{ $experienceScore ?? 82 }}, {{ $educationScore ?? 75 }}, {{ $messageScore ?? 70 }}, {{ $growthScore ?? 88 }}], backgroundColor: 'rgba(47, 123, 255, 0.2)', borderColor: '#2f7bff', borderWidth: 2, pointBackgroundColor: '#2f7bff' }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { r: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } } } }
        });
    }

    function updateChartType() {
        currentChartType = document.getElementById('chartType').value;
        initUsersChart();
    }

    function updateDashboard() {
        const days = document.getElementById('dateRange').value;
        fetch(`/dashboard/analytics/users-data?period=${days}`)
            .then(response => response.json())
            .then(data => {
                if (usersChart) {
                    usersChart.data.labels = data.labels;
                    usersChart.data.datasets[0].data = data.data;
                    usersChart.update();
                }
            });
    }

    // ============================================================
    // UTILITY FUNCTIONS
    // ============================================================
    function fullscreenChart(chartId) {
        const card = document.getElementById(chartId + 'Card') || document.querySelector(`#${chartId}`)?.closest('.premium-chart-card');
        if (card) {
            if (!document.fullscreenElement) {
                card.classList.add('chart-fullscreen');
                card.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen();
                card.classList.remove('chart-fullscreen');
            }
        }
    }

    async function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        if (canvas) {
            const link = document.createElement('a');
            link.download = `${filename}.png`;
            link.href = canvas.toDataURL();
            link.click();
        }
    }

    function toggleChartLegend(chartId) {
        const chart = window[chartId];
        if (chart) {
            const legend = chart.options.plugins.legend;
            legend.display = !legend.display;
            chart.update();
        }
    }

    function scrollToChart(chartId) {
        const element = document.getElementById(chartId + 'Card') || document.getElementById(chartId);
        if (element) element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function exportData() {
        const format = document.getElementById('exportFormat').value;
        if (!format) return;
        if (format === 'pdf') exportToPDF();
        else if (format === 'excel' || format === 'csv') window.location.href = `{{ route('dashboard.analytics.export') }}?format=${format}`;
        else if (format === 'json') exportToJSON();
        else if (format === 'png') exportToPNG();
        document.getElementById('exportFormat').value = '';
    }

    async function exportToPDF() {
        const element = document.querySelector('#main .content-area');
        const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' });
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 210;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
        pdf.save('analytics-report.pdf');
    }

    function exportToJSON() {
        const data = { users: @json($monthlyUsers), projects: @json($categoryData), skills: @json($skillLevels), generated: new Date().toISOString() };
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'analytics-data.json';
        link.click();
        URL.revokeObjectURL(link.href);
    }

    async function exportToPNG() {
        const element = document.querySelector('#main .content-area');
        const canvas = await html2canvas(element, { scale: 2 });
        const link = document.createElement('a');
        link.download = 'analytics-screenshot.png';
        link.href = canvas.toDataURL();
        link.click();
    }

    function shareReport() {
        if (navigator.share) {
            navigator.share({ title: 'Analytics Report', text: 'Check out my analytics dashboard!', url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    }

    // ============================================================
    // INITIALIZE EVERYTHING
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        animateCounters();
        initUsersChart();
        initCategoryChart();
        initSkillsChart();
        initStatusChart();
        initMessagesChart();
        initRadarChart();

        // Animate progress bars
        setTimeout(() => {
            document.querySelectorAll('.kpi-progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = width; }, 100);
            });
        }, 500);
    });
</script>
@endpush
