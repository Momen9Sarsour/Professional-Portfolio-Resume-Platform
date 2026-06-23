<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cv_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');                           // اسم القالب
            $table->string('slug')->unique();                 // اسم الرابط
            $table->text('description')->nullable();          // وصف القالب
            $table->string('preview_image')->nullable();      // الصورة التوضيحية
            $table->string('thumbnail')->nullable();          // الصورة المصغرة
            $table->longText('html_code');                    // كود HTML الكامل
            $table->text('css_code')->nullable();             // كود CSS إضافي
            $table->text('js_code')->nullable();              // كود JS إضافي

            // إضافة عمود لتحديد إذا كان القالب نظامي (أساسي) أم لا
            $table->boolean('is_system')->default(false)->after('is_default');
            // إضافة عمود للمسار الأصلي للقالب (للرجوع إليه)
            $table->string('original_path')->nullable()->after('blade_file');
            
            $table->string('blade_file');                     // اسم ملف الـ Blade
            $table->boolean('is_default')->default(false);    // هل هو قالب افتراضي؟
            $table->boolean('is_active')->default(true);      // هل القالب مفعل؟
            $table->integer('sort_order')->default(0);        // ترتيب العرض
            $table->string('created_by')->nullable();         // من أضاف القالب

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_templates');
    }
};
