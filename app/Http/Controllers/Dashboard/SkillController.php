<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Skill::query();

        // Admin can see all skills, user sees only their own
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by specific user (admin only)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $skills = $query->orderBy('name')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get unique categories for filter
        $categories = Skill::distinct()->pluck('category')->filter()->values();

        // Get users for admin filter
        $users = Auth::user()->role === 'admin' ? \App\Models\User::all() : collect();

        return view('dashboard.skills.index', compact('skills', 'categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Determine user_id (admin can assign to any user)
        // 🔥 IMPORTANT: Determine user_id
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $validated['user_id'] = Auth::id();
            $redirectUserId = Auth::id();
        }

        $validated['is_active'] = $request->boolean('is_active');

        Skill::create($validated);

        // 🔥 IMPORTANT: Redirect back to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Skill added successfully for user!');
        }

        return redirect()
            ->route('dashboard.skills.index')
            ->with('success', 'Skill "' . $validated['name'] . '" added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $skill = Skill::findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $skill->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'skill' => $skill
            ]);
        }

        return view('dashboard.skills.show', compact('skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $skill->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Admin can reassign skill to another user
        // Admin can reassign
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $skill->user_id = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $redirectUserId = $skill->user_id;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $skill->update($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Skill updated successfully for user!');
        }

        return redirect()
            ->route('dashboard.skills.index')
            ->with('success', 'Skill "' . $skill->name . '" updated successfully!');
    }

    /**
     * Toggle skill active status.
     */
    public function toggle(Skill $skill)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $skill->user_id !== Auth::id()) {
            abort(403);
        }

        $skill->update(['is_active' => !$skill->is_active]);

        $status = $skill->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Skill "' . $skill->name . '" ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $skill->user_id !== Auth::id()) {
            abort(403);
        }

        $name = $skill->name;
        $userId = $skill->user_id;
        $skill->delete();

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.clients.show', $userId)
                ->with('success', 'Skill "' . $name . '" deleted successfully!');
        }

        return redirect()
            ->route('dashboard.skills.index')
            ->with('success', 'Skill "' . $name . '" deleted successfully!');
    }
}
