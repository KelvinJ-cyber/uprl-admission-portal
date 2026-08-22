<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
        'min_utme_score',
        'faculty_id',
        'department_id'
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function requirement(): HasOne
    {
        return $this->hasOne(CourseRequirement::class);
    }

    public function requiredSubjects(): HasMany
    {
        return $this->hasMany(CourseRequiredSubject::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

}
