<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     if (Auth::user()->role === 'admin') {
    //         // Admin sees all data
    //         $totalUsers = User::count();
    //         $totalProjects = Project::count();
    //         $totalSkills = Skill::count();
    //         $totalExperiences = Experience::count();
    //         $totalEducation = Education::count();
    //         $unreadMessages = Message::where('is_read', false)->count();
    //         $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
    //         $recentProjects = Project::orderBy('created_at', 'desc')->limit(5)->get();
    //     } else {
    //         // Regular user sees only their data
    //         $totalUsers = null;
    //         $totalProjects = Project::where('user_id', Auth::id())->count();
    //         $totalSkills = Skill::where('user_id', Auth::id())->count();
    //         $totalExperiences = Experience::where('user_id', Auth::id())->count();
    //         $totalEducation = Education::where('user_id', Auth::id())->count();
    //         $unreadMessages = null;
    //         $recentUsers = null;
    //         $recentProjects = Project::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
    //     }

    //     return view('dashboard.index', compact(
    //         'totalUsers', 'totalProjects', 'totalSkills',
    //         'totalExperiences', 'totalEducation', 'unreadMessages',
    //         'recentUsers', 'recentProjects'
    //     ));
    // }

    public function settings()
    {
        return view('dashboard.settings');
    }

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
