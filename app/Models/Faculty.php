<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
