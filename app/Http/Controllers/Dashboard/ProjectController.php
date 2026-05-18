<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Admin can see all projects, user sees only their own
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by specific user (admin only)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $projects = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get users for admin filter
        $users = Auth::user()->role === 'admin' ? \App\Models\User::all() : collect();

        return view('dashboard.projects.index', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Laravel/PHP,Web,Java/Flutter,C++',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'github_link'  => 'nullable|url|max:500',
            'demo_link'    => 'nullable|url|max:500',
            'technologies' => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
            'user_id'      => 'nullable|exists:users,id',
        ]);

        // 🔥 IMPORTANT: Determine user_id (admin can assign to any user)
        // Determine user_id (admin can assign to any user)
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
        } else {
            $validated['user_id'] = Auth::id();
        }

        // Upload image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active']   = $request->boolean('is_active');
        $validated['sort_order']  = $request->input('sort_order', 0);

        Project::create($validated);

        // 🔥 IMPORTANT: Redirect back to the same client page if admin
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            return redirect()
                ->route('dashboard.clients.show', $request->user_id)
                ->with('success', 'Project added successfully for user!');
        }

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'project' => $project
            ]);
        }

        return view('dashboard.projects.show', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Laravel/PHP,Web,Java/Flutter,C++',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'github_link'  => 'nullable|url|max:500',
            'demo_link'    => 'nullable|url|max:500',
            'technologies' => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
            'user_id'      => 'nullable|exists:users,id',
        ]);

        // Admin can reassign project to another user
        // 🔥 IMPORTANT: Admin can reassign project to another user
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $project->user_id = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $redirectUserId = $project->user_id;
        }

        // Upload new image
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active']   = $request->boolean('is_active');
        $validated['sort_order']  = $request->input('sort_order', 0);

        $project->update($validated);

        // 🔥 IMPORTANT: Redirect back to the same client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Project updated successfully for user!');
        }

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Toggle project active status.
     */
    public function toggle(Project $project)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->update(['is_active' => !$project->is_active]);

        $status = $project->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Project "' . $project->title . '" ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id()) {
            abort(403);
        }

        $title = $project->title;
        $userId = $project->user_id;

        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        // 🔥 IMPORTANT: Redirect back to the same client page if admin
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.clients.show', $userId)
                ->with('success', 'Project "' . $title . '" deleted successfully!');
        }

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project "' . $title . '" deleted successfully.');
    }
}
