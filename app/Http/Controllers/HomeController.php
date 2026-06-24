<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get first active user for demo (or the admin)
        $user = User::where('role', 'admin')->first() ?? User::first();

        if (!$user) {
            return view('welcome');
        }

        $profile = $user->profile ?? new \App\Models\Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();

        $skillsByCategory = $skills->groupBy('category');

        return view('home', compact(
            'user', 'profile', 'projects', 'skills', 'skillsByCategory',
            'experiences', 'education', 'socialLinks'
        ));
    }
}
