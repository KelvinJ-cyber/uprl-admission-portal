<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Course;

class CourseEligibilityService
{

    // WAEC/NECO grade ranking — lower number = better grade
    protected array $gradeRank = [
        'A1' => 1, 'B2' => 2, 'B3' => 3,
        'C4' => 4, 'C5' => 5, 'C6' => 6,
        'D7' => 7, 'E8' => 8, 'F9' => 9,
    ];

    public function checkEligibility(Candidate $candidate, Course $course): array
    {
        $reasons = [];

        // 1. Check UTME score against course minimum
        if ($candidate->utme_score < $course->min_utme_score) {
            $reasons[] = "UTME score ({$candidate->utme_score}) is below the required minimum ({$course->min_utme_score}).";
        }

        // 2. Check O'Level result exists and is verified
        $olevelResult = $candidate->olevelResult;

        if (! $olevelResult) {
            $reasons[] = "O'Level result has not been submitted.";
        } elseif (! $olevelResult->is_verified) {
            $reasons[] = "O'Level result has not been verified.";
        } else {
            // 3. Check required subjects/grades
            $requiredSubjects = $course->requiredSubjects;
            $candidateGrades = $olevelResult->subjectGrades->keyBy(function ($item) {
                return strtolower(trim($item->subject_name));
            });

            foreach ($requiredSubjects as $required) {
                $key = strtolower(trim($required->subject_name));
                $candidateGrade = $candidateGrades->get($key);

                if (! $candidateGrade) {
                    if ($required->is_mandatory) {
                        $reasons[] = "Missing {$required->subject_name} in O'Level result.";
                    }
                    continue;
                }

                $candidateRank = $this->gradeRank[$candidateGrade->grade] ?? 9;
                $requiredRank = $this->gradeRank[$required->minimum_grade] ?? 9;

                if ($candidateRank > $requiredRank) {
                    $reasons[] = "{$required->subject_name} grade ({$candidateGrade->grade}) is below the required minimum ({$required->minimum_grade}).";
                }
            }

            // 4. Check minimum credit count
            $requirement = $course->requirement;
            if ($requirement) {
                $creditCount = $olevelResult->subjectGrades
                    ->filter(fn($g) => ($this->gradeRank[$g->grade] ?? 9) <= 6) // C6 or better = a credit
                    ->count();

                if ($creditCount < $requirement->min_olevel_credits) {
                    $reasons[] = "Only {$creditCount} credits found; minimum required is {$requirement->min_olevel_credits}.";
                }
            }
        }

        return [
            'eligible' => empty($reasons),
            'reasons' => $reasons,
        ];
    }
}
