<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Skills;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $skills = [
            ['Laravel', 90, 'Backend'],
            ['PHP', 85, 'Backend'],
            ['MySQL', 80, 'Database'],
            ['JavaScript', 75, 'Frontend'],
            ['Bootstrap', 80, 'Frontend']
        ];

        foreach ($skills as $skill) {
            Skills::create([
                'user_id' => $user->id,
                'name' => $skill[0],
                'level' => $skill[1],
                'category' => $skill[2]
            ]);
        }
    }
}
