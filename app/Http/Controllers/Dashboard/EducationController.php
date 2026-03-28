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
        $query = Education::where('user_id', Auth::id());

        // Filter by search (university or degree)
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

        return view('dashboard.education.index', compact('education'));
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
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        Education::create($validated);

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

        if ($education->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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
        if ($education->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $education->update($validated);

        return redirect()
            ->route('dashboard.education.index')
            ->with('success', 'Education updated successfully!');
    }

    /**
     * Toggle education active status.
     */
    public function toggle(Education $education)
    {
        if ($education->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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
        if ($education->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $degree = $education->degree;
        $education->delete();

        return redirect()
            ->route('dashboard.education.index')
            ->with('success', 'Education "' . $degree . '" deleted successfully!');
    }
}
