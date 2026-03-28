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

        // Admin vs User
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by name/search
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

        return view('dashboard.skills.index', compact('skills', 'categories'));
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
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');

        Skill::create($validated);

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
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $skill->update($validated);

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
        $skill->delete();

        return redirect()
            ->route('dashboard.skills.index')
            ->with('success', 'Skill "' . $name . '" deleted successfully.');
    }
}
