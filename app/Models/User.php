<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('sort_order');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('name');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class)->orderBy('start_date', 'desc');
    }

    public function education()
    {
        return $this->hasMany(Education::class)->orderBy('start_date', 'desc');
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class)->orderBy('platform');
    }

    public function cvTemplate()
    {
        return $this->belongsTo(CvTemplate::class, 'cv_template_id');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getCvTemplateSlugAttribute()
    {
        if ($this->cvTemplate) {
            return $this->cvTemplate->slug;
        }

        $default = CvTemplate::getDefaultTemplate();
        return $default ? $default->slug : 'modern';
    }

    public function getCvTemplateNameAttribute()
    {
        if ($this->cvTemplate) {
            return $this->cvTemplate->name;
        }

        $default = CvTemplate::getDefaultTemplate();
        return $default ? $default->name : 'Modern';
    }

    // ============================================================
    // METHODS
    // ============================================================

    public function setDefaultTemplate()
    {
        $default = CvTemplate::getDefaultTemplate();
        if ($default && !$this->cvTemplate) {
            $this->cv_template_id = $default->id;
            $this->save();
        }
    }
}
