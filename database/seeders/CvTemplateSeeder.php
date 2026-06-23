<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CvTemplate;

class CvTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Modern Template
        CvTemplate::updateOrCreate(
            ['slug' => 'modern'],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'description' => 'Clean and professional with a modern layout',
                'preview_image' => 'templates/modern-preview.jpg',
                'blade_file' => 'modern.blade.php',
                'original_path' => 'dashboard/resume/templates/modern.blade.php',
                'html_code'=> '',
                'is_system' => true,
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // 2️⃣ Minimal Template
        CvTemplate::updateOrCreate(
            ['slug' => 'minimal'],
            [
                'name' => 'Minimal',
                'slug' => 'minimal',
                'description' => 'Simple and elegant minimalist design',
                'preview_image' => 'templates/minimal-preview.jpg',
                'blade_file' => 'minimal.blade.php',
                'original_path' => 'dashboard/resume/templates/minimal.blade.php',
                'html_code'=> '',
                'is_system' => true,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // 3️⃣ Creative Template
        CvTemplate::updateOrCreate(
            ['slug' => 'creative'],
            [
                'name' => 'Creative',
                'slug' => 'creative',
                'description' => 'Bold and creative with accent colors',
                'preview_image' => 'templates/creative-preview.jpg',
                'blade_file' => 'creative.blade.php',
                'original_path' => 'dashboard/resume/templates/creative.blade.php',
                'html_code'=> '',
                'is_system' => true,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // 4️⃣ Professional Template
        CvTemplate::updateOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Traditional professional layout for corporate roles',
                'preview_image' => 'templates/professional-preview.jpg',
                'blade_file' => 'professional.blade.php',
                'original_path' => 'dashboard/resume/templates/professional.blade.php',
                'html_code'=> '',
                'is_system' => true,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        // 5️⃣ Sidebar Template
        CvTemplate::updateOrCreate(
            ['slug' => 'sidebar'],
            [
                'name' => 'Sidebar',
                'slug' => 'sidebar',
                'description' => 'Two-column layout with sidebar for personal info',
                'preview_image' => 'templates/sidebar-preview.jpg',
                'blade_file' => 'sidebar.blade.php',
                'original_path' => 'dashboard/resume/templates/sidebar.blade.php',
                'html_code'=> '',
                'is_system' => true,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 5,
            ]
        );
    }
}
