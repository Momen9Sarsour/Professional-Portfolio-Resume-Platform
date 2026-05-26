<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Admin sees all data
            $totalUsers = User::count();
            $totalProjects = Project::count();
            $totalSkills = Skill::count();
            $totalExperiences = Experience::count();
            $totalEducation = Education::count();
            $unreadMessages = Message::where('is_read', false)->count();
            $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
            $recentProjects = Project::orderBy('created_at', 'desc')->limit(5)->get();
        } else {
            // Regular user sees only their data
            $totalUsers = null;
            $totalProjects = Project::where('user_id', Auth::id())->count();
            $totalSkills = Skill::where('user_id', Auth::id())->count();
            $totalExperiences = Experience::where('user_id', Auth::id())->count();
            $totalEducation = Education::where('user_id', Auth::id())->count();
            $unreadMessages = null;
            $recentUsers = null;
            $recentProjects = Project::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
        }

        return view('dashboard.index', compact(
            'totalUsers', 'totalProjects', 'totalSkills',
            'totalExperiences', 'totalEducation', 'unreadMessages',
            'recentUsers', 'recentProjects'
        ));
    }

    /**
     * Display analytics dashboard with advanced statistics.
     */
    public function analytics(Request $request)
    {
        // ============================================================
        // TOTALS COUNTS
        // ============================================================
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalSkills = Skill::count();
        $totalExperiences = Experience::count();
        $totalEducation = Education::count();
        $totalMessages = Message::count();
        $unreadMessages = Message::where('is_read', false)->count();

        // ============================================================
        // PROJECTS STATUS
        // ============================================================
        $activeProjects = Project::where('is_active', true)->count();
        $inactiveProjects = Project::where('is_active', false)->count();

        // ============================================================
        // SKILLS STATISTICS
        // ============================================================
        $averageSkillLevel = Skill::avg('level') ?? 0;

        // ============================================================
        // USERS STATISTICS (Current Month)
        // ============================================================
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ============================================================
        // GROWTH RATES
        // ============================================================
        $growthRate = [
            'users' => round(($totalUsers > 0 ? ($newUsersThisMonth / max(1, $totalUsers - $newUsersThisMonth)) * 100 : 0), 1),
            'projects' => round(($totalProjects > 0 ? ($activeProjects / $totalProjects) * 100 : 0), 1),
            'skills' => round(($averageSkillLevel), 1),
        ];

        // ============================================================
        // MONTHLY DATA FOR CHARTS (Last 12 months)
        // ============================================================
        $monthlyLabels = [];
        $monthlyUsers = [];
        $previousMonthlyUsers = [];
        $monthlyMessages = [];
        $monthlyProjects = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');

            // Current year users
            $monthlyUsers[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // Previous year users (for comparison)
            $prevDate = now()->subYears(1)->subMonths($i);
            $previousMonthlyUsers[] = User::whereYear('created_at', $prevDate->year)
                ->whereMonth('created_at', $prevDate->month)
                ->count();

            // Monthly messages
            $monthlyMessages[] = Message::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // Monthly projects
            $monthlyProjects[] = Project::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // ============================================================
        // PROJECTS BY CATEGORY
        // ============================================================
        $categories = ['Laravel/PHP', 'Web', 'Java/Flutter', 'C++'];
        $categoryLabels = [];
        $categoryData = [];

        foreach ($categories as $cat) {
            $count = Project::where('category', $cat)->count();
            if ($count > 0) {
                $categoryLabels[] = $cat;
                $categoryData[] = $count;
            }
        }

        // ============================================================
        // SKILLS DISTRIBUTION (Top 10 by level)
        // ============================================================
        $topSkills = Skill::orderBy('level', 'desc')->limit(10)->get();
        $skillNames = $topSkills->pluck('name')->toArray();
        $skillLevels = $topSkills->pluck('level')->toArray();

        // ============================================================
        // PERFORMANCE SCORES
        // ============================================================
        $projectScore = min(100, round(($activeProjects / max(1, $totalProjects)) * 100));
        $skillScore = min(100, round($averageSkillLevel));
        $experienceScore = min(100, round(($totalExperiences / max(1, $totalUsers)) * 20));
        $educationScore = min(100, round(($totalEducation / max(1, $totalUsers)) * 20));
        $messageScore = min(100, round(($totalMessages > 0 ? ($totalMessages - $unreadMessages) / $totalMessages * 100 : 100)));
        $growthScore = min(100, round($growthRate['users'] + 50));

        // ============================================================
        // RESPONSE RATE
        // ============================================================
        $responseRate = $totalMessages > 0 ? round((($totalMessages - $unreadMessages) / $totalMessages) * 100) : 100;

        // ============================================================
        // COMPLETION & ENGAGEMENT RATES
        // ============================================================
        $completionRate = min(100, round(($totalProjects + $totalSkills + $totalExperiences + $totalEducation) / max(1, $totalUsers * 4)));
        $engagementRate = min(100, round(($activeProjects / max(1, $totalProjects)) * 30 + ($averageSkillLevel / 100) * 30 + ($responseRate / 100) * 40));

        // ============================================================
        // ANNUAL GROWTH & SUCCESS RATES
        // ============================================================
        $annualGrowth = round(($newUsersThisMonth / max(1, $totalUsers - $newUsersThisMonth)) * 100, 1);
        $projectSuccessRate = min(100, round(($activeProjects / max(1, $totalProjects)) * 100));

        // ============================================================
        // RECENT ACTIVITIES (Merge projects and users)
        // ============================================================
        $recentProjectsCollection = Project::latest()->limit(5)->get()->map(function($item) {
            $item->type = 'Project';
            $item->title = $item->title;
            return $item;
        });

        $recentUsersCollection = User::latest()->limit(5)->get()->map(function($item) {
            $item->type = 'User';
            $item->title = $item->name;
            return $item;
        });

        $recentActivities = $recentProjectsCollection->concat($recentUsersCollection)
            ->sortByDesc('created_at')
            ->take(10);

        // ============================================================
        // TOP PERFORMING SKILLS (for table)
        // ============================================================
        $topPerformingSkills = Skill::orderBy('level', 'desc')->limit(5)->get();

        return view('dashboard.analytics', compact(
            'totalUsers',
            'totalProjects',
            'totalSkills',
            'totalExperiences',
            'totalEducation',
            'totalMessages',
            'unreadMessages',
            'activeProjects',
            'inactiveProjects',
            'averageSkillLevel',
            'newUsersThisMonth',
            'growthRate',
            'monthlyLabels',
            'monthlyUsers',
            'previousMonthlyUsers',
            'monthlyMessages',
            'monthlyProjects',
            'categoryLabels',
            'categoryData',
            'skillNames',
            'skillLevels',
            'topSkills',
            'projectScore',
            'skillScore',
            'experienceScore',
            'educationScore',
            'messageScore',
            'growthScore',
            'responseRate',
            'completionRate',
            'engagementRate',
            'annualGrowth',
            'projectSuccessRate',
            'recentActivities',
            'topPerformingSkills'
        ));
    }

    /**
     * Get users data for AJAX chart updates.
     */
    public function analyticsUsersData(Request $request)
    {
        $period = $request->get('period', 12);
        $data = [];
        $labels = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $data[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    /**
     * Export analytics data to various formats.
     */
    public function analyticsExport(Request $request)
    {
        $format = $request->get('format', 'csv');

        $data = [
            'generated_at' => now()->toDateTimeString(),
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_skills' => Skill::count(),
            'total_messages' => Message::count(),
            'active_projects' => Project::where('is_active', true)->count(),
            'inactive_projects' => Project::where('is_active', false)->count(),
            'average_skill_level' => round(Skill::avg('level') ?? 0, 2),
            'users_by_role' => User::selectRaw('role, count(*) as count')->groupBy('role')->get(),
            'projects_by_category' => Project::selectRaw('category, count(*) as count')->groupBy('category')->get(),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // CSV Export
        $filename = 'analytics-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['Analytics Report']);
        fputcsv($handle, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);
        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Users', $data['total_users']]);
        fputcsv($handle, ['Total Projects', $data['total_projects']]);
        fputcsv($handle, ['Total Skills', $data['total_skills']]);
        fputcsv($handle, ['Total Messages', $data['total_messages']]);
        fputcsv($handle, ['Active Projects', $data['active_projects']]);
        fputcsv($handle, ['Inactive Projects', $data['inactive_projects']]);
        fputcsv($handle, ['Average Skill Level', $data['average_skill_level'] . '%']);
        fputcsv($handle, []);
        fputcsv($handle, ['Users by Role']);
        fputcsv($handle, ['Role', 'Count']);
        foreach ($data['users_by_role'] as $role) {
            fputcsv($handle, [$role->role, $role->count]);
        }
        fputcsv($handle, []);
        fputcsv($handle, ['Projects by Category']);
        fputcsv($handle, ['Category', 'Count']);
        foreach ($data['projects_by_category'] as $cat) {
            fputcsv($handle, [$cat->category ?? 'Uncategorized', $cat->count]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Settings page.
     */
    public function settings()
    {
        return view('dashboard.settings.index');
    }
}


// namespace App\Http\Controllers\Dashboard;

// use App\Http\Controllers\Controller;
// use App\Models\Education;
// use App\Models\Experience;
// use App\Models\Project;
// use App\Models\User;
// use Illuminate\Http\Request;

// class DashboardController extends Controller
// {
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

    // public function index()
    // {
    //     // ── Stats for the 4 stat cards ──────────────────────────────────────
    //     $stats = [
    //         'clients'    => User::count(),
    //         'projects'   => Project::count(),
    //         'courses'    => Education::count(),
    //         'experience' => Experience::count(),
    //     ];

    //     // ── 5 most recent projects for the dashboard table ──────────────────
    //     $recentProjects = Project::orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get();

    //     // ── Top skills (static — replace with DB model if you build one) ────
    //     $topSkills = [
    //         ['name' => 'PHP & Laravel',              'level' => 85],
    //         ['name' => 'HTML & CSS',                 'level' => 90],
    //         ['name' => 'JavaScript',                 'level' => 75],
    //         ['name' => 'MySQL',                      'level' => 80],
    //         ['name' => 'Back-End Development',       'level' => 85],
    //         ['name' => 'Version Control (Git)',      'level' => 80],
    //     ];

    //     return view('dashboard.index', compact('stats', 'recentProjects', 'topSkills'));
    // }

    // public function analytics(Request $request)
    // {
    //     $period = $request->get('period', 12);

    //     // Monthly user registrations
    //     $monthlyUsers = [];
    //     $monthlyLabels = [];
    //     for ($i = $period - 1; $i >= 0; $i--) {
    //         $date = now()->subMonths($i);
    //         $monthlyLabels[] = $date->format('M Y');
    //         $monthlyUsers[] = \App\Models\User::whereYear('created_at', $date->year)
    //             ->whereMonth('created_at', $date->month)
    //             ->count();
    //     }

    //     // Projects by category
    //     $categories = ['Laravel/PHP', 'Web', 'Java/Flutter', 'C++'];
    //     $categoryData = [];
    //     $categoryLabels = [];
    //     foreach ($categories as $cat) {
    //         $count = \App\Models\Project::where('category', $cat)->count();
    //         if ($count > 0) {
    //             $categoryLabels[] = $cat;
    //             $categoryData[] = $count;
    //         }
    //     }

    //     // Top skills by level
    //     $skills = \App\Models\Skill::orderBy('level', 'desc')->limit(10)->get();
    //     $skillNames = $skills->pluck('name')->toArray();
    //     $skillLevels = $skills->pluck('level')->toArray();

    //     // Project status counts
    //     $activeProjects = \App\Models\Project::where('is_active', true)->count();
    //     $inactiveProjects = \App\Models\Project::where('is_active', false)->count();

    //     // Totals
    //     $totalUsers = \App\Models\User::count();
    //     $totalProjects = \App\Models\Project::count();
    //     $totalSkills = \App\Models\Skill::count();
    //     $totalMessages = \App\Models\Message::count();
    //     $unreadMessages = \App\Models\Message::where('is_read', false)->count();
    //     $averageSkillLevel = \App\Models\Skill::avg('level') ?? 0;
    //     $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)->count();

    //     // Recent data
    //     $recentProjects = \App\Models\Project::orderBy('created_at', 'desc')->limit(5)->get();
    //     $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get();

    //     return view('dashboard.analytics', compact(
    //         'totalUsers',
    //         'totalProjects',
    //         'totalSkills',
    //         'totalMessages',
    //         'unreadMessages',
    //         'averageSkillLevel',
    //         'newUsersThisMonth',
    //         'activeProjects',
    //         'inactiveProjects',
    //         'monthlyUsers',
    //         'monthlyLabels',
    //         'categoryLabels',
    //         'categoryData',
    //         'skillNames',
    //         'skillLevels',
    //         'recentProjects',
    //         'recentUsers'
    //     ));
    // }

    // public function analyticsUsersData(Request $request)
    // {
    //     $period = $request->get('period', 12);
    //     $data = [];
    //     $labels = [];

    //     for ($i = $period - 1; $i >= 0; $i--) {
    //         $date = now()->subMonths($i);
    //         $labels[] = $date->format('M Y');
    //         $data[] = \App\Models\User::whereYear('created_at', $date->year)
    //             ->whereMonth('created_at', $date->month)
    //             ->count();
    //     }

    //     return response()->json(['labels' => $labels, 'data' => $data]);
    // }

    // public function analyticsExport()
    // {
    //     $users = \App\Models\User::all();
    //     $projects = \App\Models\Project::all();
    //     $skills = \App\Models\Skill::all();

    //     // Simple CSV export
    //     $filename = 'analytics-report-' . date('Y-m-d') . '.csv';
    //     $handle = fopen('php://output', 'w');

    //     header('Content-Type: text/csv');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');

    //     fputcsv($handle, ['Report Generated: ' . now()->format('Y-m-d H:i:s')]);
    //     fputcsv($handle, []);
    //     fputcsv($handle, ['Users Summary']);
    //     fputcsv($handle, ['Total Users', $users->count()]);
    //     fputcsv($handle, ['Users by Role', '']);

    //     $roles = $users->groupBy('role');
    //     foreach ($roles as $role => $roleUsers) {
    //         fputcsv($handle, [$role, $roleUsers->count()]);
    //     }

    //     fputcsv($handle, []);
    //     fputcsv($handle, ['Projects Summary']);
    //     fputcsv($handle, ['Total Projects', $projects->count()]);
    //     fputcsv($handle, ['Active Projects', $projects->where('is_active', true)->count()]);
    //     fputcsv($handle, ['Inactive Projects', $projects->where('is_active', false)->count()]);

    //     fputcsv($handle, []);
    //     fputcsv($handle, ['Skills Summary']);
    //     fputcsv($handle, ['Total Skills', $skills->count()]);
    //     fputcsv($handle, ['Average Skill Level', round($skills->avg('level'), 2) . '%']);

    //     fclose($handle);
    //     exit;
    // }
// }
