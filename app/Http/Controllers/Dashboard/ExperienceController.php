<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $query = Experience::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('company', 'like', '%' . $request->search . '%')
                    ->orWhere('job_title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $experiences = $query->orderBy('sort_order')->orderBy('start_date', 'desc')->paginate(10);

        return view('dashboard.experiences.index', compact('experiences'));
    }

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
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        Experience::create($validated);

        return redirect()->route('dashboard.experiences.index')
            ->with('success', 'Experience added successfully!');
    }

    public function update(Request $request, Experience $experience)
    {
        if ($experience->user_id !== Auth::id()) {
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
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $experience->update($validated);

        return redirect()->route('dashboard.experiences.index')
            ->with('success', 'Experience updated successfully!');
    }

    public function toggle(Experience $experience)
    {
        if ($experience->user_id !== Auth::id()) {
            abort(403);
        }

        $experience->update(['is_active' => !$experience->is_active]);

        return redirect()->back()->with('success', 'Experience status updated!');
    }

    public function destroy(Experience $experience)
    {
        if ($experience->user_id !== Auth::id()) {
            abort(403);
        }

        $experience->delete();

        return redirect()->route('dashboard.experiences.index')
            ->with('success', 'Experience deleted successfully!');
    }
}
