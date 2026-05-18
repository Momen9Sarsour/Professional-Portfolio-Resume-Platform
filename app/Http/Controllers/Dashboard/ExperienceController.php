<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Experience::query();

        // Admin can see all experiences, user sees only their own
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by specific user (admin only)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('company', 'like', '%' . $request->search . '%')
                    ->orWhere('job_title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $experiences = $query->orderBy('sort_order')->orderBy('start_date', 'desc')->paginate(10)->withQueryString();

        // Get users for admin filter
        $users = Auth::user()->role === 'admin' ? \App\Models\User::all() : collect();

        return view('dashboard.experiences.index', compact('experiences', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Determine user_id (admin can assign to any user)
        // Determine user_id
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $validated['user_id'] = Auth::id();
            $redirectUserId = Auth::id();
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        Experience::create($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Experience added successfully for user!');
        }

        return redirect()
            ->route('dashboard.experiences.index')
            ->with('success', 'Experience added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $experience = Experience::findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $experience->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'experience' => $experience
            ]);
        }

        return view('dashboard.experiences.show', compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Experience $experience)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $experience->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Admin can reassign experience to another user
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $experience->user_id = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $redirectUserId = $experience->user_id;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $experience->update($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Experience updated successfully for user!');
        }

        return redirect()
            ->route('dashboard.experiences.index')
            ->with('success', 'Experience updated successfully!');
    }

    /**
     * Toggle experience active status.
     */
    public function toggle(Experience $experience)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $experience->user_id !== Auth::id()) {
            abort(403);
        }

        $experience->update(['is_active' => !$experience->is_active]);

        $status = $experience->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Experience "' . $experience->job_title . '" ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $experience->user_id !== Auth::id()) {
            abort(403);
        }

        $jobTitle = $experience->job_title;
        $userId = $experience->user_id;
        $experience->delete();

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.clients.show', $userId)
                ->with('success', 'Experience "' . $jobTitle . '" deleted successfully!');
        }

        return redirect()
            ->route('dashboard.experiences.index')
            ->with('success', 'Experience "' . $jobTitle . '" deleted successfully!');
    }
}
