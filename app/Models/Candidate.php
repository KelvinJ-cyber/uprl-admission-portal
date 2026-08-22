<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Candidate extends Authenticatable
{
    protected $fillable = [
        'jamb_reg_number',
        'surname',
        'first_name',
        'other_names',
        'gender',
        'date_of_birth',
        'state_of_origin',
        'local_government',
        'email',
        'phone_number',
        'utme_score',
        'subject_combination',
        'status',
        'course_id',
        'recommended_by',
        'recommended_at',
    ];

    public function course() :BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function olevelResult(): HasOne
    {
        return $this->hasOne(OlevelResult::class);
    }
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function screeningReports(): HasMany
    {
        return $this->hasMany(ScreeningReport::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
