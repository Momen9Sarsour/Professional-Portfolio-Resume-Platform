<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        SocialLink::create([

            'user_id' => $user->id,
            'platform' => 'GitHub',
            'url' => 'https://github.com'

        ]);
        SocialLink::create([

            'user_id' => $user->id,
            'platform' => 'LinkedIn',
            'url' => 'https://linkedin.com'

        ]);
    }
}
