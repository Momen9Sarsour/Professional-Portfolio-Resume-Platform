@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Overview')
@section('page-subtitle', now()->format('l, F j, Y'))

@push('styles')
<style>
    /* ===== STAT CARDS ===== */
    .stat-card {
        border-radius: 14px; padding: 22px 24px; color: #fff;
        position: relative; overflow: hidden; height: 130px;
        display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 8px 32px rgba(0,0,0,0.10); transition: transform .25s;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-card.g1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.g2 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .stat-card.g3 { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }
    .stat-card.g4 { background: linear-gradient(135deg, #f953c6 0%, #b91d73 100%); }
    .stat-card .sc-icon { position: absolute; right: 18px; top: 18px; font-size: 40px; opacity: .18; }
    .stat-card .sc-label { font-size: 11px; font-weight: 700; opacity: .85; letter-spacing: .8px; text-transform: uppercase; }
    .stat-card .sc-value { font-size: 30px; font-weight: 800; line-height: 1; }
    .stat-card .sc-trend { font-size: 11.5px; opacity: .85; display: flex; align-items: center; gap: 4px; }
    .stat-card canvas { position: absolute; bottom: 0; left: 0; width: 100% !important; height: 50px !important; opacity: .3; pointer-events: none; }

    /* ===== TABLE ===== */
    .dash-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .dash-table thead tr { background: #1a2035; color: #fff; }
    .dash-table thead th { padding: 11px 14px; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
    .dash-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .dash-table thead th:last-child { border-radius: 0 8px 8px 0; }
    .dash-table tbody tr { border-bottom: 1px solid #e8edf5; transition: background .15s; }
    .dash-table tbody tr:hover { background: #f4f6fb; }
    .dash-table tbody td { padding: 11px 14px; }
    .dash-table tbody td.muted { color: #7a869a; font-size: 12px; }

    /* ===== COUNTRY / CLIENT ITEMS ===== */
    .country-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 14px; border-radius: 10px; margin-bottom: 6px;
        color: #fff; font-size: 13px; font-weight: 500;
    }
    .country-item .flag { font-size: 20px; }
    .country-item .amount { margin-left: auto; font-weight: 700; }

    /* Section header */
    .section-hdr { font-size: 18px; font-weight: 700; color: #1a2035; }
    .section-hdr small { font-size: 13px; font-weight: 400; color: #7a869a; margin-left: 8px; }

    /* Badge */
    .tech-badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600; margin: 2px;
    }
</style>
@endpush

@section('content')

{{-- ===== TOP ROW ===== --}}
<div class="d-flex align-items-center justify-content-between mb-4 fade-up">
    <div>
        <h5 class="section-hdr mb-0">Overview <small>Portfolio Statistics</small></h5>
    </div>
    <a href="{{ route('dashboard.projects.create') }}" class="btn-primary-dash">
        <i class="bi bi-plus-lg"></i> Add Project
    </a>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6 fade-up d1">
        <div class="stat-card g1">
            <i class="bi bi-people-fill sc-icon"></i>
            <div class="sc-label">Happy Clients</div>
            <div class="sc-value" data-target="{{ $stats['clients'] }}">0</div>
            <div class="sc-trend"><i class="bi bi-arrow-up-short"></i> +5 new clients</div>
            <canvas id="spark1"></canvas>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 fade-up d2">
        <div class="stat-card g2">
            <i class="bi bi-folder-fill sc-icon"></i>
            <div class="sc-label">Total Projects</div>
            <div class="sc-value" data-target="{{ $stats['projects'] }}">0</div>
            <div class="sc-trend"><i class="bi bi-arrow-up-short"></i> +2 this month</div>
            <canvas id="spark2"></canvas>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 fade-up d3">
        <div class="stat-card g3">
            <i class="bi bi-award-fill sc-icon"></i>
            <div class="sc-label">Courses Completed</div>
            <div class="sc-value" data-target="{{ $stats['courses'] }}">0</div>
            <div class="sc-trend"><i class="bi bi-arrow-up-short"></i> +1 this year</div>
            <canvas id="spark3"></canvas>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 fade-up d4">
        <div class="stat-card g4">
            <i class="bi bi-calendar-check-fill sc-icon"></i>
            <div class="sc-label">Years Experience</div>
            <div class="sc-value" data-target="{{ $stats['experience'] }}">0</div>
            <div class="sc-trend"><i class="bi bi-arrow-up-short"></i> Growing steadily</div>
            <canvas id="spark4"></canvas>
        </div>
    </div>
</div>

{{-- ===== CHARTS ROW ===== --}}
<div class="row g-3 mb-4">
    {{-- Area Chart --}}
    <div class="col-xl-8 fade-up d5">
        <div class="card-box" style="height:320px;">
            <div class="card-title-row">
                <h6>Projects By Category</h6>
                <div style="display:flex; align-items:center; gap:16px; font-size:12px; color:#7a869a;">
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:10px;border-radius:50%;background:#667eea;display:inline-block;"></span> Laravel/PHP</span>
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:10px;border-radius:50%;background:#11998e;display:inline-block;"></span> Web</span>
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:10px;border-radius:50%;background:#f7971e;display:inline-block;"></span> Java</span>
                </div>
            </div>
            <canvas id="areaChart" style="height:230px;"></canvas>
        </div>
    </div>

    {{-- Donut Chart --}}
    <div class="col-xl-4 fade-up d6">
        <div class="card-box" style="height:320px;">
            <div class="card-title-row">
                <h6>Chart By %</h6>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;height:200px;">
                <canvas id="donutChart"></canvas>
            </div>
            <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;margin-top:10px;font-size:12px;color:#7a869a;">
                <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#667eea;display:inline-block;"></span> Laravel</span>
                <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#11998e;display:inline-block;"></span> Web</span>
                <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#f953c6;display:inline-block;"></span> Java/Flutter</span>
                <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#f7971e;display:inline-block;"></span> C++</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== PROJECTS TABLE + CLIENTS ===== --}}
<div class="row g-3">
    {{-- Recent Projects Table --}}
    <div class="col-xl-8 fade-up d5">
        <div class="card-box">
            <div class="card-title-row">
                <h6>Recent Projects</h6>
                <a href="{{ route('dashboard.projects.index') }}" class="btn-primary-dash" style="font-size:12px;padding:6px 14px;">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProjects as $project)
                        <tr>
                            <td class="muted">{{ $loop->iteration }}</td>
                            <td style="font-weight:600; color:#1a2035;">{{ $project->title }}</td>
                            <td>
                                <span style="background:rgba(47,123,255,0.1);color:#2f7bff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                                    {{ $project->category }}
                                </span>
                            </td>
                            <td>
                                @foreach(explode(',', $project->technologies) as $tech)
                                    <span class="tech-badge" style="background:#f4f6fb;color:#7a869a;">{{ trim($tech) }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($project->is_active)
                                    <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Active</span>
                                @else
                                    <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.projects.edit', $project->id) }}" style="color:#2f7bff;font-size:15px;margin-right:8px;"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#ef4444;font-size:15px;cursor:pointer;"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:#7a869a;padding:30px;">
                                <i class="bi bi-folder2-open" style="font-size:30px;display:block;margin-bottom:8px;"></i>
                                No projects yet. <a href="{{ route('dashboard.projects.create') }}">Add your first project</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Skills --}}
    <div class="col-xl-4 fade-up d6">
        <div class="card-box">
            <div class="card-title-row mb-3">
                <h6>Top Skills</h6>
            </div>
            @foreach($topSkills as $index => $skill)
            @php
                $colors = ['#667eea','#11998e','#f7971e','#f953c6','#2f7bff','#764ba2'];
                $bg = $colors[$index % count($colors)];
            @endphp
            <div class="country-item" style="background:{{ $bg }};">
                <i class="bi bi-code-slash flag" style="font-size:18px;"></i>
                <span>{{ $skill['name'] }}</span>
                <span class="amount">{{ $skill['level'] }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ===== COUNTER ANIMATION =====
document.querySelectorAll('.sc-value[data-target]').forEach(el => {
    const target = parseInt(el.dataset.target);
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 60));
    const timer = setInterval(() => {
        current += step;
        if (current >= target) { el.textContent = target.toLocaleString(); clearInterval(timer); }
        else { el.textContent = current.toLocaleString(); }
    }, 20);
});

// ===== SPARKLINES =====
function sparkline(id, color, data) {
    const ctx = document.getElementById(id);
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: data.map((_,i) => i),
            datasets: [{ data, borderColor: '#fff', borderWidth: 2, fill: true,
                backgroundColor: 'rgba(255,255,255,0.2)', tension: 0.4, pointRadius: 0 }]
        },
        options: { responsive: false, plugins: { legend:{display:false}, tooltip:{enabled:false} },
            scales: { x:{display:false}, y:{display:false} } }
    });
}
sparkline('spark1','#fff',[10,25,20,45,40,60,55,70]);
sparkline('spark2','#fff',[5,8,7,10,9,13,12,15]);
sparkline('spark3','#fff',[2,3,3,4,4,5,5,6]);
sparkline('spark4','#fff',[1,1,2,2,2,3,3,3]);

// ===== AREA CHART =====
const areaCtx = document.getElementById('areaChart').getContext('2d');
const g1 = areaCtx.createLinearGradient(0,0,0,220);
g1.addColorStop(0,'rgba(102,126,234,0.5)'); g1.addColorStop(1,'rgba(102,126,234,0)');
const g2 = areaCtx.createLinearGradient(0,0,0,220);
g2.addColorStop(0,'rgba(17,153,142,0.45)'); g2.addColorStop(1,'rgba(17,153,142,0)');
const g3 = areaCtx.createLinearGradient(0,0,0,220);
g3.addColorStop(0,'rgba(247,151,30,0.4)'); g3.addColorStop(1,'rgba(247,151,30,0)');

new Chart(areaCtx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'],
        datasets: [
            { label:'Laravel/PHP', data:[2,3,3,4,5,5,6], borderColor:'#667eea', backgroundColor:g1, fill:true, tension:0.4, borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#667eea' },
            { label:'Web',         data:[1,2,2,3,4,4,5], borderColor:'#11998e', backgroundColor:g2, fill:true, tension:0.4, borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#11998e' },
            { label:'Java',        data:[0,1,1,1,2,2,2], borderColor:'#f7971e', backgroundColor:g3, fill:true, tension:0.4, borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#f7971e' }
        ]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false} },
        scales:{
            x:{ grid:{display:false}, ticks:{font:{size:12},color:'#7a869a'} },
            y:{ grid:{color:'#f0f2f8'}, ticks:{font:{size:12},color:'#7a869a'} }
        }
    }
});

// ===== DONUT CHART =====
new Chart(document.getElementById('donutChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Laravel/PHP','Web','Java/Flutter','C++'],
        datasets: [{ data: [30, 40, 20, 10],
            backgroundColor:['#667eea','#11998e','#f953c6','#f7971e'],
            borderWidth:3, borderColor:'#fff', hoverOffset:6 }]
    },
    options: {
        responsive:true, cutout:'68%',
        plugins:{ legend:{display:false} }
    }
});
</script>
@endpush
