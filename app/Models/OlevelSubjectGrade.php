<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OlevelSubjectGrade extends Model
{
    protected $table = 'olevel_subjects_grades';

    protected $fillable = [
        'olevel_result_id',
        'subject_name',
        'grade',
    ];

    public function olevelResult(): BelongsTo
    {
        return $this->belongsTo(OlevelResult::class);
    }
}
