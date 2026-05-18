<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Education::query();

        // Admin can see all education, user sees only their own
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
                $q->where('university', 'like', '%' . $request->search . '%')
                    ->orWhere('degree', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $education = $query->orderBy('sort_order')->orderBy('start_date', 'desc')->paginate(10)->withQueryString();

        // Get users for admin filter
        $users = Auth::user()->role === 'admin' ? \App\Models\User::all() : collect();

        return view('dashboard.education.index', compact('education', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Determine user_id (admin can assign to any user)
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $validated['user_id'] = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $validated['user_id'] = Auth::id();
            $redirectUserId = Auth::id();
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        Education::create($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Education added successfully for user!');
        }

        return redirect()
            ->route('dashboard.education.index')
            ->with('success', 'Education added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $education = Education::findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'admin' && $education->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'education' => $education
            ]);
        }

        return view('dashboard.education.show', compact('education'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Education $education)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $education->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'university' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Admin can reassign education to another user
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $education->user_id = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $redirectUserId = $education->user_id;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $education->update($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Skill updated successfully for user!');
        }

        return redirect()
            ->route('dashboard.education.index')
            ->with('success', 'Education updated successfully!');
    }

    /**
     * Toggle education active status.
     */
    public function toggle(Education $education)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $education->user_id !== Auth::id()) {
            abort(403);
        }

        $education->update(['is_active' => !$education->is_active]);

        $status = $education->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Education "' . $education->degree . '" ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Education $education)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $education->user_id !== Auth::id()) {
            abort(403);
        }

        $degree = $education->degree;
        $userId = $education->user_id;
        $education->delete();

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.clients.show', $userId)
                ->with('success', 'Education "' . $degree . '" deleted successfully!');
        }

        return redirect()
            ->route('dashboard.education.index')
            ->with('success', 'Education "' . $degree . '" deleted successfully!');
    }
}
