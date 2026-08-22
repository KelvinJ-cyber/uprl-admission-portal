<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseRequirement extends Model
{
    protected $fillable = [
        'course_id',
        'utme_subject_1',
        'utme_subject_2',
        'utme_subject_3',
        'utme_subject_4',
        'min_olevel_credits',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);

    }
}
