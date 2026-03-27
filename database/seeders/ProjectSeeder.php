<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        Project::create([

            'user_id' => $user->id,
            'title' => 'Multi Vendor E-commerce Platform',
            'description' => 'Advanced e-commerce platform built using Laravel.',
            'github_link' => 'https://github.com',
            'demo_link' => null,
            'technologies' => 'Laravel, MySQL, Bootstrap'

        ]);
    }
}
