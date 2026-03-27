<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
        public function index()
    {
        // ── Stats for the 4 stat cards ──────────────────────────────────────
        $stats = [
            'clients'    => User::count(),
            'projects'   => Project::count(),
            'courses'    => Education::count(),
            'experience' => Experience::count(),
        ];

        // ── 5 most recent projects for the dashboard table ──────────────────
        $recentProjects = Project::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ── Top skills (static — replace with DB model if you build one) ────
        $topSkills = [
            ['name' => 'PHP & Laravel',              'level' => 85],
            ['name' => 'HTML & CSS',                 'level' => 90],
            ['name' => 'JavaScript',                 'level' => 75],
            ['name' => 'MySQL',                      'level' => 80],
            ['name' => 'Back-End Development',       'level' => 85],
            ['name' => 'Version Control (Git)',      'level' => 80],
        ];

        return view('dashboard.index', compact('stats', 'recentProjects', 'topSkills'));
    }
}
