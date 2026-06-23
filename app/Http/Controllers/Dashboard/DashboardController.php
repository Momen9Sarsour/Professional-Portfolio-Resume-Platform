<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CvTemplate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // ============================================================
        // 📊 TOTALS
        // ============================================================
        if ($isAdmin) {
            $totalUsers = User::count();
            $totalProjects = Project::count();
            $totalSkills = Skill::count();
            $totalExperiences = Experience::count();
            $totalEducation = Education::count();
            $totalMessages = Message::count();
            $unreadMessages = Message::where('is_read', false)->count();
            $totalTemplates = CvTemplate::count();
            $totalSocialLinks = SocialLink::count();
        } else {
            $totalUsers = null;
            $totalProjects = Project::where('user_id', Auth::id())->count();
            $totalSkills = Skill::where('user_id', Auth::id())->count();
            $totalExperiences = Experience::where('user_id', Auth::id())->count();
            $totalEducation = Education::where('user_id', Auth::id())->count();
            $totalMessages = null;
            $unreadMessages = null;
            $totalTemplates = null;
            $totalSocialLinks = SocialLink::where('user_id', Auth::id())->count();
        }

        // ============================================================
        // 📊 RECENT DATA (Latest 5 from each table)
        // ============================================================
        if ($isAdmin) {
            $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
            $recentProjects = Project::orderBy('created_at', 'desc')->limit(5)->get();
            $recentSkills = Skill::orderBy('created_at', 'desc')->limit(5)->get();
            $recentExperiences = Experience::orderBy('created_at', 'desc')->limit(5)->get();
            $recentEducation = Education::orderBy('created_at', 'desc')->limit(5)->get();
            $recentMessages = Message::orderBy('created_at', 'desc')->limit(5)->get();
            $recentTemplates = CvTemplate::orderBy('created_at', 'desc')->limit(5)->get();
            $recentSocialLinks = SocialLink::orderBy('created_at', 'desc')->limit(5)->get();
        } else {
            $recentUsers = null;
            $recentProjects = Project::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
            $recentSkills = Skill::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
            $recentExperiences = Experience::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
            $recentEducation = Education::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
            $recentMessages = null;
            $recentTemplates = null;
            $recentSocialLinks = SocialLink::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get();
        }

        // ============================================================
        // 📈 CHART DATA
        // ============================================================
        $monthlyStats = $this->getMonthlyStats($isAdmin ? null : Auth::id());
        $projectsByCategory = $this->getProjectsByCategory($isAdmin ? null : Auth::id());
        $skillsByLevel = $this->getSkillsByLevel($isAdmin ? null : Auth::id());
        $weeklyActivity = $this->getWeeklyActivity($isAdmin ? null : Auth::id());

        // ============================================================
        // 📈 KPI METRICS
        // ============================================================
        $completionRate = $this->calculateCompletionRate($user);
        $engagementRate = $this->calculateEngagementRate($user, $totalProjects, $totalSkills);
        $projectSuccessRate = $this->calculateProjectSuccessRate($user);
        $averageSkillLevel = Skill::avg('level') ?? 0;
        $growthRates = $this->getGrowthRates($isAdmin ? null : Auth::id());

        return view('dashboard.index', compact(
            'totalUsers',
            'totalProjects',
            'totalSkills',
            'totalExperiences',
            'totalEducation',
            'totalMessages',
            'unreadMessages',
            'totalTemplates',
            'totalSocialLinks',
            'recentUsers',
            'recentProjects',
            'recentSkills',
            'recentExperiences',
            'recentEducation',
            'recentMessages',
            'recentTemplates',
            'recentSocialLinks',
            'monthlyStats',
            'projectsByCategory',
            'skillsByLevel',
            'weeklyActivity',
            'completionRate',
            'engagementRate',
            'projectSuccessRate',
            'averageSkillLevel',
            'growthRates',
            'isAdmin'
        ));
    }

    protected function getMonthlyStats($userId = null)
    {
        $months = 6;
        $data = ['labels' => [], 'projects' => []];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $query = Project::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
            if ($userId) $query->where('user_id', $userId);
            $data['labels'][] = $date->format('M Y');
            $data['projects'][] = $query->count();
        }
        return $data;
    }

    protected function getProjectsByCategory($userId = null)
    {
        $query = Project::selectRaw('category, count(*) as count')->groupBy('category');
        if ($userId) $query->where('user_id', $userId);
        return $query->get()->pluck('count', 'category')->toArray();
    }

    protected function getSkillsByLevel($userId = null)
    {
        $query = Skill::orderBy('level', 'desc')->limit(10);
        if ($userId) $query->where('user_id', $userId);
        return $query->get()->pluck('level', 'name')->toArray();
    }

    protected function getWeeklyActivity($userId = null)
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $query = Project::whereDate('created_at', $date->toDateString());
            if ($userId) $query->where('user_id', $userId);
            $days[$date->format('D')] = $query->count();
        }
        return $days;
    }

    protected function getGrowthRates($userId = null)
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        $query = Project::query();
        if ($userId) $query->where('user_id', $userId);
        $currentCount = (clone $query)->whereMonth('created_at', $currentMonth->month)->whereYear('created_at', $currentMonth->year)->count();
        $lastCount = (clone $query)->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $growth = $lastCount > 0 ? round((($currentCount - $lastCount) / $lastCount) * 100, 1) : 0;
        return ['projects' => $growth, 'trend' => $growth >= 0 ? 'up' : 'down'];
    }

    protected function calculateCompletionRate($user)
    {
        $profile = $user->profile;
        $score = 0;
        if ($profile) {
            if ($profile->bio) $score += 20;
            if ($profile->avatar) $score += 10;
            if ($profile->title) $score += 10;
            if ($profile->location) $score += 10;
            if ($profile->phone) $score += 10;
        }
        $score += min(20, Project::where('user_id', $user->id)->count() * 5);
        $score += min(20, Skill::where('user_id', $user->id)->count() * 5);
        return min(100, $score);
    }

    protected function calculateEngagementRate($user, $totalProjects, $totalSkills)
    {
        $projectScore = $totalProjects > 0 ? min(50, $totalProjects * 5) : 0;
        $skillScore = $totalSkills > 0 ? min(30, $totalSkills * 3) : 0;
        $profileScore = $user->profile && $user->profile->bio ? 20 : 0;
        return min(100, $projectScore + $skillScore + $profileScore);
    }

    protected function calculateProjectSuccessRate($user)
    {
        $total = Project::where('user_id', $user->id)->count();
        if ($total === 0) return 0;
        $active = Project::where('user_id', $user->id)->where('is_active', true)->count();
        return round(($active / $total) * 100);
    }

    /**
     * Display analytics dashboard with advanced statistics.
     */
 /**
 * Display analytics dashboard with advanced statistics.
 */
public function analytics(Request $request)
{
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';

    // ============================================================
    // TOTALS COUNTS (Admin sees all, User sees only their data)
    // ============================================================
    if ($isAdmin) {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalSkills = Skill::count();
        $totalExperiences = Experience::count();
        $totalEducation = Education::count();
        $totalMessages = Message::count();
        $unreadMessages = Message::where('is_read', false)->count();

        // Projects status
        $activeProjects = Project::where('is_active', true)->count();
        $inactiveProjects = Project::where('is_active', false)->count();

        // Skills statistics
        $averageSkillLevel = Skill::avg('level') ?? 0;

        // Users statistics
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Monthly data for charts
        $monthlyLabels = [];
        $monthlyUsers = [];
        $previousMonthlyUsers = [];
        $monthlyMessages = [];
        $monthlyProjects = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');

            $monthlyUsers[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $prevDate = now()->subYears(1)->subMonths($i);
            $previousMonthlyUsers[] = User::whereYear('created_at', $prevDate->year)
                ->whereMonth('created_at', $prevDate->month)
                ->count();

            $monthlyMessages[] = Message::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyProjects[] = Project::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Projects by category
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

        // Skills distribution
        $topSkills = Skill::orderBy('level', 'desc')->limit(10)->get();
        $skillNames = $topSkills->pluck('name')->toArray();
        $skillLevels = $topSkills->pluck('level')->toArray();

        // Recent activities
        $recentProjectsCollection = Project::latest()->limit(5)->get()->map(function ($item) {
            $item->type = 'Project';
            $item->title = $item->title;
            return $item;
        });

        $recentUsersCollection = User::latest()->limit(5)->get()->map(function ($item) {
            $item->type = 'User';
            $item->title = $item->name;
            return $item;
        });

        $recentActivities = $recentProjectsCollection->concat($recentUsersCollection)
            ->sortByDesc('created_at')
            ->take(10);

        // Top performing skills
        $topPerformingSkills = Skill::orderBy('level', 'desc')->limit(5)->get();

    } else {
        // 🔒 User sees only their own data
        $userId = $user->id;

        $totalUsers = null;
        $totalProjects = Project::where('user_id', $userId)->count();
        $totalSkills = Skill::where('user_id', $userId)->count();
        $totalExperiences = Experience::where('user_id', $userId)->count();
        $totalEducation = Education::where('user_id', $userId)->count();
        $totalMessages = Message::where('user_id', $userId)->count();
        $unreadMessages = Message::where('user_id', $userId)->where('is_read', false)->count();

        $activeProjects = Project::where('user_id', $userId)->where('is_active', true)->count();
        $inactiveProjects = Project::where('user_id', $userId)->where('is_active', false)->count();

        $averageSkillLevel = Skill::where('user_id', $userId)->avg('level') ?? 0;

        $newUsersThisMonth = null;

        // Monthly data for user's charts
        $monthlyLabels = [];
        $monthlyUsers = [];
        $previousMonthlyUsers = [];
        $monthlyMessages = [];
        $monthlyProjects = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');

            // User's projects per month
            $monthlyProjects[] = Project::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // User's messages per month
            $monthlyMessages[] = Message::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // User's skills (no monthly tracking, just total)
            $monthlyUsers[] = 0; // Not applicable for regular users
            $previousMonthlyUsers[] = 0; // Not applicable for regular users
        }

        // Projects by category for user
        $categoryLabels = [];
        $categoryData = [];
        $categories = ['Laravel/PHP', 'Web', 'Java/Flutter', 'C++'];
        foreach ($categories as $cat) {
            $count = Project::where('user_id', $userId)->where('category', $cat)->count();
            if ($count > 0) {
                $categoryLabels[] = $cat;
                $categoryData[] = $count;
            }
        }

        // Skills distribution for user
        $topSkills = Skill::where('user_id', $userId)->orderBy('level', 'desc')->limit(10)->get();
        $skillNames = $topSkills->pluck('name')->toArray();
        $skillLevels = $topSkills->pluck('level')->toArray();

        // Recent activities for user
        $recentProjectsCollection = Project::where('user_id', $userId)->latest()->limit(5)->get()->map(function ($item) {
            $item->type = 'Project';
            $item->title = $item->title;
            return $item;
        });

        $recentActivities = $recentProjectsCollection->sortByDesc('created_at')->take(5);

        // Top performing skills for user
        $topPerformingSkills = Skill::where('user_id', $userId)->orderBy('level', 'desc')->limit(5)->get();
    }

    // ============================================================
    // GROWTH RATES (Calculated for both)
    // ============================================================
    $growthRate = [
        'users' => $isAdmin ? round(($totalUsers > 0 ? ($newUsersThisMonth / max(1, $totalUsers - $newUsersThisMonth)) * 100 : 0), 1) : 0,
        'projects' => round(($totalProjects > 0 ? ($activeProjects / $totalProjects) * 100 : 0), 1),
        'skills' => round(($averageSkillLevel), 1),
    ];

    // ============================================================
    // PERFORMANCE SCORES
    // ============================================================
    if ($isAdmin) {
        $projectScore = min(100, round(($activeProjects / max(1, $totalProjects)) * 100));
        $skillScore = min(100, round($averageSkillLevel));
        $experienceScore = min(100, round(($totalExperiences / max(1, $totalUsers)) * 20));
        $educationScore = min(100, round(($totalEducation / max(1, $totalUsers)) * 20));
        $messageScore = min(100, round(($totalMessages > 0 ? ($totalMessages - $unreadMessages) / $totalMessages * 100 : 100)));
        $growthScore = min(100, round($growthRate['users'] + 50));
    } else {
        $projectScore = min(100, round(($activeProjects / max(1, $totalProjects)) * 100));
        $skillScore = min(100, round($averageSkillLevel));
        $experienceScore = min(100, round(($totalExperiences / max(1, $totalProjects)) * 20));
        $educationScore = min(100, round(($totalEducation / max(1, $totalProjects)) * 20));
        $messageScore = min(100, round(($totalMessages > 0 ? ($totalMessages - $unreadMessages) / $totalMessages * 100 : 100)));
        $growthScore = min(100, round($growthRate['projects'] + 50));
    }

    // ============================================================
    // RESPONSE RATE
    // ============================================================
    $responseRate = $totalMessages > 0 ? round((($totalMessages - $unreadMessages) / $totalMessages) * 100) : 100;

    // ============================================================
    // COMPLETION & ENGAGEMENT RATES
    // ============================================================
    if ($isAdmin) {
        $completionRate = min(100, round(($totalProjects + $totalSkills + $totalExperiences + $totalEducation) / max(1, $totalUsers * 4)));
        $engagementRate = min(100, round(($activeProjects / max(1, $totalProjects)) * 30 + ($averageSkillLevel / 100) * 30 + ($responseRate / 100) * 40));
        $annualGrowth = round(($newUsersThisMonth / max(1, $totalUsers - $newUsersThisMonth)) * 100, 1);
    } else {
        $totalItems = max(1, ($totalProjects + $totalSkills + $totalExperiences + $totalEducation));
        $completionRate = min(100, round(($totalProjects + $totalSkills + $totalExperiences + $totalEducation) / 4));
        $engagementRate = min(100, round(($activeProjects / max(1, $totalProjects)) * 30 + ($averageSkillLevel / 100) * 30 + ($responseRate / 100) * 40));
        $annualGrowth = round(($totalProjects / max(1, $totalProjects - $activeProjects)) * 10, 1);
    }

    $projectSuccessRate = min(100, round(($activeProjects / max(1, $totalProjects)) * 100));

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
        'topPerformingSkills',
        'isAdmin' // ✅ إضافة هذا المتغير
    ));
}
    /**
     * Settings page.
     */
    public function settings()
    {
        return view('dashboard.settings.index');
    }
}

