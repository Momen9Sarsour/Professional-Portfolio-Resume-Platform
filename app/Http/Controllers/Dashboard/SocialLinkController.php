<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SocialLink::where('user_id', Auth::id());

        // Filter by platform
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $socialLinks = $query->orderBy('platform')->paginate(10)->withQueryString();

        return view('dashboard.social-links.index', compact('socialLinks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255|in:github,linkedin,twitter,facebook,instagram,youtube,whatsapp,telegram,other',
            'url' => 'required|url|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active');

        SocialLink::create($validated);

        return redirect()
            ->route('dashboard.social-links.index')
            ->with('success', 'Social link added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $socialLink = SocialLink::findOrFail($id);

        if ($socialLink->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'socialLink' => $socialLink
            ]);
        }

        return view('dashboard.social-links.show', compact('socialLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialLink $socialLink)
    {
        if ($socialLink->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'platform' => 'required|string|max:255|in:github,linkedin,twitter,facebook,instagram,youtube,whatsapp,telegram,other',
            'url' => 'required|url|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $socialLink->update($validated);

        return redirect()
            ->route('dashboard.social-links.index')
            ->with('success', 'Social link updated successfully!');
    }

    /**
     * Toggle social link active status.
     */
    public function toggle(SocialLink $socialLink)
    {
        if ($socialLink->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $socialLink->update(['is_active' => !$socialLink->is_active]);

        $status = $socialLink->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Social link "' . $socialLink->platform . '" ' . $status . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialLink $socialLink)
    {
        if ($socialLink->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $platform = $socialLink->platform;
        $socialLink->delete();

        return redirect()
            ->route('dashboard.social-links.index')
            ->with('success', 'Social link "' . $platform . '" deleted successfully!');
    }
}
