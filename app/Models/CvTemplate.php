<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'thumbnail',
        'html_code',
        'css_code',
        'js_code',
        'blade_file',
        'original_path',
        'is_system',
        'is_default',
        'is_active',
        'sort_order',
        'created_by'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function users()
    {
        return $this->hasMany(User::class, 'cv_template_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ============================================================
    // METHODS
    // ============================================================

    public static function getDefaultTemplate()
    {
        $default = self::where('is_default', true)->first();
        if ($default) {
            return $default;
        }

        // If no default, get first active system template
        return self::where('is_system', true)->where('is_active', true)->first();
    }

    public function getViewPath()
    {
        // إذا كان القالب نظامي، استخدم المسار الأصلي
        if ($this->is_system && $this->original_path && view()->exists($this->original_path)) {
            return $this->original_path;
        }

        // إذا كان القالب في مجلد cv-templates
        if (view()->exists('cv-templates.' . $this->slug)) {
            return 'cv-templates.' . $this->slug;
        }

        // Fallback: استخدم اسم القالب
        return 'cv-templates.' . $this->slug;
    }

    public function getPreviewUrl()
    {
        if ($this->preview_image && file_exists(storage_path('app/public/' . $this->preview_image))) {
            return asset('storage/' . $this->preview_image);
        }

        // صورة افتراضية حسب اسم القالب
        return asset('images/templates/' . $this->slug . '-preview.jpg');
    }

    public function isDeletable()
    {
        // لا يمكن حذف القوالب النظامية إلا إذا كان هناك قالب بديل
        if ($this->is_system) {
            return CvTemplate::where('is_system', true)->where('id', '!=', $this->id)->count() > 0;
        }
        return true;
    }

    public static function getSystemTemplates()
    {
        return self::where('is_system', true)->orderBy('sort_order')->get();
    }

    public static function getCustomTemplates()
    {
        return self::where('is_system', false)->orderBy('sort_order')->get();
    }

    public static function getAllActiveTemplates()
    {
        return self::where('is_active', true)->orderBy('sort_order')->get();
    }


    // ============================================================
    // 🔥 AVAILABLE VARIABLES FOR CV TEMPLATES
    // ============================================================

    public static function getAvailableVariables()
    {
        return [
            // ============================================================
            // USER VARIABLES
            // ============================================================
            'user' => [
                'name' => 'اسم المستخدم الكامل',
                'email' => 'البريد الإلكتروني',
                'username' => 'اسم المستخدم للرابط (slug)',
                'role' => 'صلاحية المستخدم (admin/user)',
                'created_at' => 'تاريخ التسجيل (Carbon object)',
                'updated_at' => 'تاريخ آخر تحديث (Carbon object)',
            ],

            // ============================================================
            // PROFILE VARIABLES
            // ============================================================
            'profile' => [
                'title' => 'المسمى الوظيفي',
                'bio' => 'السيرة الذاتية النصية (About me)',
                'location' => 'الموقع الجغرافي',
                'phone' => 'رقم الهاتف',
                'avatar' => 'رابط الصورة الشخصية (URL)',
                'email' => 'البريد الإلكتروني (من البروفايل)',
            ],

            // ============================================================
            // PROJECTS (Collection - متعددة)
            // ============================================================
            'projects' => [
                'type' => 'Collection (متعددة)',
                'fields' => [
                    'title' => 'عنوان المشروع',
                    'description' => 'وصف المشروع',
                    'category' => 'فئة المشروع (Laravel/PHP, Web, Java/Flutter, C++)',
                    'technologies' => 'التقنيات المستخدمة (مفصولة بفاصلة)',
                    'github_link' => 'رابط GitHub',
                    'demo_link' => 'رابط العرض الحي',
                    'image' => 'رابط الصورة',
                    'is_active' => 'هل المشروع مفعل؟ (true/false)',
                    'sort_order' => 'ترتيب العرض',
                    'created_at' => 'تاريخ الإنشاء',
                ],
                'example' => '@foreach($projects as $project)
    <h3>{{ $project->title }}</h3>
    <p>{{ $project->description }}</p>
    <div class="techs">
        @foreach(explode(",", $project->technologies) as $tech)
            <span class="tech-tag">{{ trim($tech) }}</span>
        @endforeach
    </div>
@endforeach'
            ],

            // ============================================================
            // SKILLS (Collection - متعددة)
            // ============================================================
            'skills' => [
                'type' => 'Collection (متعددة)',
                'fields' => [
                    'name' => 'اسم المهارة',
                    'level' => 'مستوى المهارة (0-100)',
                    'category' => 'فئة المهارة (Frontend, Backend, Database, DevOps, Mobile, Other)',
                    'is_active' => 'هل المهارة مفعلة؟ (true/false)',
                ],
                'example' => '@foreach($skills as $skill)
    <span class="skill-badge">{{ $skill->name }} ({{ $skill->level }}%)</span>
@endforeach'
            ],

            // ============================================================
            // EXPERIENCES (Collection - متعددة)
            // ============================================================
            'experiences' => [
                'type' => 'Collection (متعددة)',
                'fields' => [
                    'job_title' => 'المسمى الوظيفي',
                    'company' => 'اسم الشركة',
                    'start_date' => 'تاريخ البدء (Carbon object)',
                    'end_date' => 'تاريخ الانتهاء (Carbon object أو null)',
                    'description' => 'وصف الخبرة والمهام',
                    'is_active' => 'هل الخبرة مفعلة؟ (true/false)',
                    'sort_order' => 'ترتيب العرض',
                ],
                'example' => '@foreach($experiences as $exp)
    <div class="exp-item">
        <h4>{{ $exp->job_title }}</h4>
        <p>{{ $exp->company }} | {{ $exp->start_date->format("M Y") }} - {{ $exp->end_date ? $exp->end_date->format("M Y") : "Present" }}</p>
        <p>{{ $exp->description }}</p>
    </div>
@endforeach'
            ],

            // ============================================================
            // EDUCATION (Collection - متعددة)
            // ============================================================
            'education' => [
                'type' => 'Collection (متعددة)',
                'fields' => [
                    'degree' => 'الشهادة (مثال: B.Sc. Computer Science)',
                    'university' => 'اسم الجامعة',
                    'start_date' => 'تاريخ البدء (Carbon object)',
                    'end_date' => 'تاريخ الانتهاء (Carbon object أو null)',
                    'description' => 'وصف إضافي (تخصصات، معدل، إنجازات)',
                    'is_active' => 'هل التعليم مفعل؟ (true/false)',
                    'sort_order' => 'ترتيب العرض',
                ],
                'example' => '@foreach($education as $edu)
    <div class="edu-item">
        <h4>{{ $edu->degree }}</h4>
        <p>{{ $edu->university }} | {{ $edu->start_date->format("Y") }} - {{ $edu->end_date ? $edu->end_date->format("Y") : "Present" }}</p>
        <p>{{ $edu->description }}</p>
    </div>
@endforeach'
            ],

            // ============================================================
            // SOCIAL LINKS (Collection - متعددة)
            // ============================================================
            'socialLinks' => [
                'type' => 'Collection (متعددة)',
                'fields' => [
                    'platform' => 'اسم المنصة (github, linkedin, twitter, facebook, instagram, youtube, whatsapp, telegram, other)',
                    'url' => 'رابط المنصة',
                    'is_active' => 'هل الرابط مفعل؟ (true/false)',
                ],
                'example' => '@foreach($socialLinks as $link)
    <a href="{{ $link->url }}" target="_blank">{{ ucfirst($link->platform) }}</a>
@endforeach'
            ],

            // ============================================================
            // ADDITIONAL HELPER FUNCTIONS
            // ============================================================
            'helpers' => [
                'type' => 'Helper Functions',
                'fields' => [
                    'Carbon::parse($date)->format("M Y")' => 'تنسيق التاريخ: يناير 2024',
                    'Carbon::parse($date)->format("Y")' => 'تنسيق التاريخ: 2024',
                    'Carbon::parse($date)->format("F d, Y")' => 'تنسيق التاريخ: يناير 15, 2024',
                    'Carbon::parse($date)->diffForHumans()' => 'التاريخ النسبي: منذ 3 أيام',
                    'Str::limit($text, 100)' => 'اختصار النص إلى 100 حرف',
                    'Str::slug($text)' => 'تحويل النص إلى Slug (مثال: my-project)',
                    'url($path)' => 'إنشاء رابط كامل',
                    'asset($path)' => 'رابط للملفات العامة',
                ],
                'example' => '{{ $project->created_at->format("M Y") }}
{{ Str::limit($project->description, 100) }}
{{ asset("storage/" . $project->image) }}'
            ],
        ];
    }
}
