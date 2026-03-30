<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        return view('dashboard.profile.index', compact('user', 'profile'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate user data
        $userValidated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // Validate profile data
        $profileValidated = $request->validate([
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user
        $user->name = $userValidated['name'];
        $user->username = $userValidated['username'];
        $user->email = $userValidated['email'];
        $user->save();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile && $user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profileValidated['avatar'] = $avatarPath;
        }

        // Update or create profile
        if ($user->profile) {
            $user->profile->update($profileValidated);
        } else {
            $profileValidated['user_id'] = $user->id;
            Profile::create($profileValidated);
        }

        return redirect()
            ->route('dashboard.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()
            ->route('dashboard.profile.edit')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Delete user account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        // Delete user's avatar if exists
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        // Delete user's profile
        if ($user->profile) {
            $user->profile->delete();
        }

        // Delete user's related data (projects, skills, etc) - will cascade if foreign keys set
        $user->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
