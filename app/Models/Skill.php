<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
