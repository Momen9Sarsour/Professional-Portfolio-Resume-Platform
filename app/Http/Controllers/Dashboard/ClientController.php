<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.clients.index', compact('users'));
    }

    /**
     * Show form to create new user.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('dashboard.clients.create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Handle avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Create profile
        Profile::create([
            'user_id' => $user->id,
            'title' => $validated['title'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'location' => $validated['location'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'avatar' => $avatarPath,
        ]);

        return redirect()
            ->route('dashboard.clients.index')
            ->with('success', 'User "' . $user->name . '" created successfully!');
    }

    /**
     * Display user details with all their data.
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::with(['profile', 'projects', 'skills', 'experiences', 'education', 'socialLinks'])->findOrFail($id);

        // Get all data
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->orderBy('sort_order')->get();
        $skills = $user->skills()->orderBy('name')->get();
        $experiences = $user->experiences()->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->get();

        $skillsByCategory = $skills->groupBy('category');

        return view('dashboard.clients.show', compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks'
        ));
    }

    /**
     * Show form to edit user.
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::with('profile')->findOrFail($id);
        $profile = $user->profile ?? new Profile();

        return view('dashboard.clients.edit', compact('user', 'profile'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv_template' => 'nullable|string|in:modern,minimal,creative,professional,sidebar',
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (isset($validated['cv_template'])) {
            $user->cv_template = $validated['cv_template'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Handle avatar
        $profileData = [
            'title' => $validated['title'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'location' => $validated['location'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->profile && $user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }
            $profileData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            Profile::create($profileData);
        }

        return redirect()
            ->route('dashboard.clients.index')
            ->with('success', 'User "' . $user->name . '" updated successfully!');
    }

    /**
     * Delete user.
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // Don't allow admin to delete themselves
        // if ($user->id === auth()->id()) {
        if ($user->id === Auth::user()->id) {
            return redirect()
                ->route('dashboard.clients.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $name = $user->name;

        // Delete avatar if exists
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        $user->delete();

        return redirect()
            ->route('dashboard.clients.index')
            ->with('success', 'User "' . $name . '" deleted successfully!');
    }

    /**
     * Download user's CV as PDF.
     */
    public function downloadCV($id, $template = null)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $skillsByCategory = $skills->groupBy('category');

        $template = $template ?? ($user->cv_template ?? 'modern');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.resume.templates.' . $template, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($user->name . '-CV.pdf');
    }

    /**
     * Preview user's CV.
     */
    public function previewCV($id, $template = null)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $skillsByCategory = $skills->groupBy('category');

        $template = $template ?? ($user->cv_template ?? 'modern');

        return view('dashboard.resume.templates.' . $template, compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks',
            'template'
        ));
    }
}
