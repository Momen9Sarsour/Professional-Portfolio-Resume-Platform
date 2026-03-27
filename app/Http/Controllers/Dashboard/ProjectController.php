<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query()->orderBy('sort_order')->orderBy('created_at', 'desc');

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $projects = $query->paginate(8)->withQueryString();

        return view('dashboard.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Laravel/PHP,Web,Java/Flutter,C++',
            'image'     => 'nullable',
            'github_link'   => 'nullable|url|max:500',
            'demo_link'   => 'nullable|url|max:500',
            'technologies' => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        // ✅ رفع الصورة
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path; // مثال: projects/abc.jpg
        }

        $validated['is_active']   = $request->boolean('is_active');
        $validated['user_id']   = 1;
        $validated['sort_order']  = $request->input('sort_order', 0);

        Project::create($validated);

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project "' . $validated['title'] . '" added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|in:Laravel/PHP,Web,Java/Flutter,C++',
            'image'     => 'nullable',
            'github_link'   => 'nullable|url|max:500',
            'demo_link'   => 'nullable|url|max:500',
            'technologies' => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        // ✅ رفع الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }
            // رفع الصورة الجديدة
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path; // مثال: projects/abc.jpg
        }


        $validated['is_active']   = $request->boolean('is_active');
        $validated['user_id']   = 1;
        $validated['sort_order']  = $request->input('sort_order', 0);

        $project->update($validated);

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project "' . $project->title . '" updated successfully!');
    }

    public function toggle(Project $project)
    {
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
        $title = $project->title;

        // ✅ حذف الصورة من المجلد قبل حذف المشروع
        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        return redirect()
            ->route('dashboard.projects.index')
            ->with('success', 'Project "' . $title . '" deleted successfully.');
    }
}
