<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CvTemplate;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvTemplateController extends Controller
{
    /**
     * عرض جميع القوالب مع فلتر حسب النوع والحالة
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = CvTemplate::query();

        // فلتر حسب النوع (system / custom)
        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->where('is_system', true);
            } elseif ($request->type === 'custom') {
                $query->where('is_system', false);
            }
        }

        // فلتر حسب الحالة (active / inactive)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // بحث بالاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $templates = $query->orderBy('is_system', 'desc')
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        // إحصائيات
        $systemCount = CvTemplate::where('is_system', true)->count();
        $customCount = CvTemplate::where('is_system', false)->count();
        $activeCount = CvTemplate::where('is_active', true)->count();

        return view('dashboard.cv-templates.index', compact(
            'templates',
            'systemCount',
            'customCount',
            'activeCount'
        ));
    }

    /**
     * عرض صفحة إنشاء قالب جديد
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $variables = CvTemplate::getAvailableVariables();
        $templates = CvTemplate::all();

        return view('dashboard.cv-templates.create', compact('variables', 'templates'));
    }

    /**
     * حفظ قالب جديد في قاعدة البيانات وإنشاء ملف Blade منفصل
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // ✅ التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cv_templates',
            'description' => 'nullable|string',
            'html_code' => 'required|string',
            'css_code' => 'nullable|string',
            'js_code' => 'nullable|string',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'is_system' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // ✅ القوالب المضافة يدوياً ليست نظامية (System)
        $validated['is_system'] = false;

        // ✅ رفع الصورة التوضيحية
        if ($request->hasFile('preview_image')) {
            $path = $request->file('preview_image')->store('cv-templates', 'public');
            $validated['preview_image'] = $path;
        }

        // ✅ تجهيز البيانات قبل الحفظ
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_default'] = $request->boolean('is_default');
        $validated['sort_order'] = $request->input('sort_order', 0);
        $validated['created_by'] = Auth::user()->name;
        $validated['slug'] = Str::slug($request->name);
        $validated['blade_file'] = $validated['slug'] . '.blade.php';

        // ✅ حفظ الكود كما هو
        $validated['html_code'] = $request->html_code;
        $validated['css_code'] = $request->css_code;
        $validated['js_code'] = $request->js_code;

        // ✅ حفظ في قاعدة البيانات
        $template = CvTemplate::create($validated);

        // ✅ إنشاء ملف Blade منفصل
        $this->createBladeFile($template);

        // ✅ إذا كان هذا القالب هو الافتراضي، إزالة الافتراضي من الآخرين
        if ($validated['is_default']) {
            CvTemplate::where('id', '!=', $template->id)->update(['is_default' => false]);
        }

        return redirect()
            ->route('dashboard.cv-templates.index')
            ->with('success', 'Template "' . $template->name . '" created successfully!');
    }

    /**
     * عرض تفاصيل القالب مع الكود
     */
    public function show(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $variables = CvTemplate::getAvailableVariables();

        return view('dashboard.cv-templates.show', compact('cvTemplate', 'variables'));
    }

    /**
     * عرض صفحة تعديل القالب
     */
    public function edit(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $variables = CvTemplate::getAvailableVariables();

        return view('dashboard.cv-templates.edit', compact('cvTemplate', 'variables'));
    }

    /**
     * تحديث القالب في قاعدة البيانات وإعادة إنشاء ملف Blade
     */
    public function update(Request $request, CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // ✅ التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cv_templates,name,' . $cvTemplate->id,
            'description' => 'nullable|string',
            'html_code' => 'required|string',
            'css_code' => 'nullable|string',
            'js_code' => 'nullable|string',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // ✅ تحديث الصورة إذا تم رفع جديدة
        if ($request->hasFile('preview_image')) {
            if ($cvTemplate->preview_image) {
                Storage::disk('public')->delete($cvTemplate->preview_image);
                if ($cvTemplate->thumbnail) {
                    Storage::disk('public')->delete($cvTemplate->thumbnail);
                }
            }
            $path = $request->file('preview_image')->store('cv-templates', 'public');
            $validated['preview_image'] = $path;
        }

        // ✅ تحديث البيانات
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_default'] = $request->boolean('is_default');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $cvTemplate->update($validated);

        // ✅ إعادة إنشاء ملف Blade مع الكود الجديد
        $this->createBladeFile($cvTemplate);

        // ✅ إذا كان هذا القالب هو الافتراضي، إزالة الافتراضي من الآخرين
        if ($validated['is_default']) {
            CvTemplate::where('id', '!=', $cvTemplate->id)->update(['is_default' => false]);
        }

        return redirect()
            ->route('dashboard.cv-templates.index')
            ->with('success', 'Template "' . $cvTemplate->name . '" updated successfully!');
    }

    /**
     * 🔥 إنشاء ملف Blade منفصل للقالب
     *
     * @param CvTemplate $template
     * @return void
     */
    protected function createBladeFile(CvTemplate $template)
    {
        // ✅ بناء محتوى الملف الكامل
        $content = $this->buildBladeContent($template);

        // ✅ تحديد مسار الملف داخل resources/views/dashboard/cv-templates/
        $path = resource_path('views/dashboard/cv-templates/' . $template->slug . '.blade.php');

        // ✅ إنشاء المجلد إذا لم يكن موجوداً
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // ✅ حفظ المحتوى في الملف
        file_put_contents($path, $content);

        // ✅ تحديث اسم ملف الـ Blade في قاعدة البيانات
        $template->blade_file = $template->slug . '.blade.php';
        $template->save();
    }

    /**
     * 🔥 بناء محتوى ملف Blade الكامل من بيانات القالب
     *
     * @param CvTemplate $template
     * @return string
     */
    protected function buildBladeContent(CvTemplate $template)
    {
        $html = $template->html_code;
        $css = $template->css_code ?? '';
        $js = $template->js_code ?? '';

        return <<<BLADE
{{-- ============================================================ --}}
{{-- قالب: {$template->name}                                    --}}
{{-- الملف: {$template->slug}.blade.php                         --}}
{{-- تم الإنشاء بواسطة: {$template->created_by}                --}}
{{-- التاريخ: {$template->created_at}                           --}}
{{-- ============================================================ --}}

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \$user->name ?? 'CV' }} - CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ============================================================
           المتغيرات المتاحة في هذا القالب
           ============================================================
           {{ \$user->name }}          : اسم المستخدم
           {{ \$user->email }}         : البريد الإلكتروني
           {{ \$profile->title }}      : المسمى الوظيفي
           {{ \$profile->bio }}        : السيرة الذاتية النصية
           {{ \$profile->location }}   : الموقع الجغرافي
           {{ \$profile->phone }}      : رقم الهاتف
           @foreach(\$projects as \$project) : المشاريع
           @foreach(\$skills as \$skill)     : المهارات
           @foreach(\$experiences as \$exp)  : الخبرات
           @foreach(\$education as \$edu)    : التعليم
           @foreach(\$socialLinks as \$link) : الروابط الاجتماعية
           ============================================================ */

        /* ============================================================
           CSS الأساسي للقالب (من الإدمن)
           ============================================================ */
        {$css}
    </style>
</head>
<body>
    <div class="cv-container">
        {$html}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ============================================================
           JavaScript مخصص (من الإدمن)
           ============================================================ */
        {$js}
    </script>
</body>
</html>
BLADE;
    }

    /**
     * 🔥 حذف ملف Blade الخاص بالقالب
     *
     * @param CvTemplate $template
     * @return void
     */
    protected function deleteBladeFile(CvTemplate $template)
    {
        $path = resource_path('views/dashboard/cv-templates/' . $template->slug . '.blade.php');
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * حذف قالب مع حذف ملف Blade الخاص به
     */
    public function destroy(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // ✅ لا يمكن حذف القالب الافتراضي
        if ($cvTemplate->is_default) {
            return redirect()
                ->route('dashboard.cv-templates.index')
                ->with('error', 'Cannot delete the default template!');
        }

        // ✅ منع حذف القالب النظامي الوحيد
        if ($cvTemplate->is_system) {
            $otherSystem = CvTemplate::where('is_system', true)
                ->where('id', '!=', $cvTemplate->id)
                ->count();

            if ($otherSystem === 0) {
                return redirect()
                    ->route('dashboard.cv-templates.index')
                    ->with('error', 'Cannot delete the only system template!');
            }

            // ✅ تحديث المستخدمين الذين يستخدمون هذا القالب
            $usersUsing = User::where('cv_template_id', $cvTemplate->id)->count();
            if ($usersUsing > 0) {
                $alternative = CvTemplate::where('is_system', true)
                    ->where('id', '!=', $cvTemplate->id)
                    ->first();
                if ($alternative) {
                    User::where('cv_template_id', $cvTemplate->id)
                        ->update(['cv_template_id' => $alternative->id]);
                }
            }
        }

        // ✅ حذف الصور
        if ($cvTemplate->preview_image) {
            Storage::disk('public')->delete($cvTemplate->preview_image);
            if ($cvTemplate->thumbnail) {
                Storage::disk('public')->delete($cvTemplate->thumbnail);
            }
        }

        // ✅ حذف ملف Blade
        $this->deleteBladeFile($cvTemplate);

        $name = $cvTemplate->name;
        $cvTemplate->delete();

        return redirect()
            ->route('dashboard.cv-templates.index')
            ->with('success', 'Template "' . $name . '" deleted successfully!');
    }

    /**
     * تفعيل/تعطيل القالب
     */
    public function toggle(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // ✅ منع تعطيل القالب النظامي الوحيد
        if ($cvTemplate->is_system && $cvTemplate->is_active) {
            $activeSystem = CvTemplate::where('is_system', true)
                ->where('is_active', true)
                ->count();

            if ($activeSystem <= 1) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot deactivate the only active system template!');
            }
        }

        $cvTemplate->update(['is_active' => !$cvTemplate->is_active]);

        $status = $cvTemplate->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', 'Template "' . $cvTemplate->name . '" ' . $status . '.');
    }

    /**
     * تعيين القالب كافتراضي
     */
    public function setDefault(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        CvTemplate::where('id', '!=', $cvTemplate->id)->update(['is_default' => false]);
        $cvTemplate->update(['is_default' => true]);

        return redirect()
            ->back()
            ->with('success', 'Template "' . $cvTemplate->name . '" set as default!');
    }

    /**
     * معاينة القالب مع بيانات تجريبية
     */
    public function preview(CvTemplate $cvTemplate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();
        $projects = $user->projects()->where('is_active', true)->orderBy('sort_order')->get();
        $skills = $user->skills()->where('is_active', true)->orderBy('name')->get();
        $experiences = $user->experiences()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $education = $user->education()->where('is_active', true)->orderBy('sort_order')->orderBy('start_date', 'desc')->get();
        $socialLinks = $user->socialLinks()->where('is_active', true)->get();
        $skillsByCategory = $skills->groupBy('category');

        // ✅ محاولة عرض الملف إذا كان موجوداً
        $viewPath = 'dashboard.cv-templates.' . $cvTemplate->slug;
        if (view()->exists($viewPath)) {
            return view($viewPath, compact(
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

        // ✅ إذا لم يكن الملف موجوداً، عرض الكود مباشرة
        $html = $cvTemplate->html_code;
        $css = $cvTemplate->css_code ?? '';
        $js = $cvTemplate->js_code ?? '';

        return view('dashboard.cv-templates.preview', compact(
            'cvTemplate',
            'html',
            'css',
            'js',
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

    /**
     * تصدير القالب كملف Blade للتحميل
     */
    public function export(CvTemplate $cvTemplate)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // ✅ بناء المحتوى
        $content = $this->buildBladeContent($cvTemplate);

        $filename = $cvTemplate->slug . '.blade.php';

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * استعادة قالب نظامي (إذا تم حذفه)
     */
    public function restoreSystem($slug)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $template = CvTemplate::where('slug', $slug)->first();
        if ($template) {
            return redirect()
                ->back()
                ->with('error', 'Template already exists!');
        }

        return redirect()
            ->back()
            ->with('success', 'Template restored successfully!');
    }
}
