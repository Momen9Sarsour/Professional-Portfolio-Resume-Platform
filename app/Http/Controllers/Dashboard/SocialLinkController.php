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
        $query = SocialLink::query();

        // Admin can see all social links, user sees only their own
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter by specific user (admin only)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by platform
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $socialLinks = $query->orderBy('platform')->paginate(10)->withQueryString();

        // Get users for admin filter
        $users = Auth::user()->role === 'admin' ? \App\Models\User::all() : collect();

        return view('dashboard.social-links.index', compact('socialLinks', 'users'));
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

        SocialLink::create($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Social link added successfully for user!');
        }

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

        // Check authorization
        if (Auth::user()->role !== 'admin' && $socialLink->user_id !== Auth::id()) {
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
        // Check authorization
        if (Auth::user()->role !== 'admin' && $socialLink->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'platform' => 'required|string|max:255|in:github,linkedin,twitter,facebook,instagram,youtube,whatsapp,telegram,other',
            'url' => 'required|url|max:500',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Admin can reassign social link to another user
        // Admin can reassign
        if (Auth::user()->role === 'admin' && $request->filled('user_id')) {
            $socialLink->user_id = $request->user_id;
            $redirectUserId = $request->user_id;
        } else {
            $redirectUserId = $socialLink->user_id;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $socialLink->update($validated);

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin' && isset($redirectUserId)) {
            return redirect()
                ->route('dashboard.clients.show', $redirectUserId)
                ->with('success', 'Social Link updated successfully for user!');
        }

        return redirect()
            ->route('dashboard.social-links.index')
            ->with('success', 'Social link updated successfully!');
    }

    /**
     * Toggle social link active status.
     */
    public function toggle(SocialLink $socialLink)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $socialLink->user_id !== Auth::id()) {
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
        // Check authorization
        if (Auth::user()->role !== 'admin' && $socialLink->user_id !== Auth::id()) {
            abort(403);
        }

        $platform = $socialLink->platform;
        $userId = $socialLink->user_id;
        $socialLink->delete();

        // Redirect to client page if admin
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('dashboard.clients.show', $userId)
                ->with('success', 'Social Link "' . $platform . '" deleted successfully!');
        }

        return redirect()
            ->route('dashboard.social-links.index')
            ->with('success', 'Social link "' . $platform . '" deleted successfully!');
    }
}
