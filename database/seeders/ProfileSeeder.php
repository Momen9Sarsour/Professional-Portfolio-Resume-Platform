<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        Profile::create([
            'user_id' => $user->id,
            'title' => 'Full Stack Developer',
            'bio' => 'Professional web developer specialized in Laravel and modern web technologies.',
            'location' => 'Palestine',
            'avatar' => null,
            'cv_file' => null
        ]);
    }
}
