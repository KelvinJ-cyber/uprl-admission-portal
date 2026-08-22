<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OlevelResult extends Model
{
    protected $fillable = [
        'candidate_id',
        'exam_type',
        'exam_year',
        'exam_number',
        'scratch_card_or_token',
        'is_verified',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function subjectGrades(): HasMany
    {
        return $this->hasMany(OlevelSubjectGrade::class);
    }
}
