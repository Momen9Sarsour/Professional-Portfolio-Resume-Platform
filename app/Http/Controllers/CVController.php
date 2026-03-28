<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\SocialLink;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CVController extends Controller
{
    public function show($username = null)
    {
        // If no username, get the first active user or auth user
        if (!$username) {
            $user = auth()->check() ? auth()->user() : User::first();
        } else {
            $user = User::where('username', $username)->firstOrFail();
        }

        $profile = $user->profile;
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');

        return view('cv.index', compact(
            'user',
            'profile',
            'projects',
            'skills',
            'skillsByCategory',
            'experiences',
            'education',
            'socialLinks'
        ));
    }

    public function download($username = null)
    {
        if (!$username) {
            $user = auth()->check() ? auth()->user() : User::first();
        } else {
            $user = User::where('username', $username)->firstOrFail();
        }

        $profile = $user->profile;
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('start_date', 'desc')->get();

        $pdf = Pdf::loadView('cv.pdf', compact('user', 'profile', 'projects', 'skills', 'experiences', 'education'));

        return $pdf->download($user->name . '-CV.pdf');
    }
}
