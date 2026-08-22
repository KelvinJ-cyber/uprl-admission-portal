<?php

namespace App\Imports;

use App\Models\Candidate;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

// This class is responsible for importing candidates from an Excel file into the database.
class CandidatesImport implements ToModel,WithHeadingRow, WithValidation, SkipsOnFailure
{

    use SkipsFailures;
    public function model(array $row): Model|null
    {
        // Skip if this reg number already exists — avoid duplicate imports
        if (Candidate::where('jamb_reg_number', $row['jamb_reg_number'])->exists()) {
            return null;
        }

        $course = Course::where('code', $row['course_code'])->first();

        return new Candidate([
            'jamb_reg_number'      => $row['jamb_reg_number'],
            'surname'              => $row['surname'],
            'first_name'           => $row['first_name'],
            'other_names'          => $row['other_names'] ?? null,
            'gender'               => $row['gender'],
            'date_of_birth'        => $row['date_of_birth'],
            'state_of_origin'      => $row['state_of_origin'],
            'local_government'     => $row['local_government'],
            'email'                => $row['email'] ?? null,
            'phone_number'         => $row['phone_number'] ?? null,
            'utme_score'           => $row['utme_score'],
            'subject_combination'  => $row['subject_combination'] ?? null,
            'course_id'            => $course?->id,
            'status'               => 'eligible_for_screening',
        ]);
    }

    public function rules(): array
    {
        return [
            'jamb_reg_number'  => ['required', 'string', 'max:20'],
            'surname'          => ['required', 'string', 'max:255'],
            'first_name'       => ['required', 'string', 'max:255'],
            'gender'           => ['required', 'in:Male,Female'],
            'date_of_birth'    => ['required', 'date'],
            'state_of_origin'  => ['required', 'string', 'max:255'],
            'local_government' => ['required', 'string', 'max:255'],
            'email'            => ['nullable', 'email'],
            'phone_number'     => ['nullable', 'string', 'max:20'],
            'utme_score'       => ['required', 'integer', 'min:0', 'max:400'],
            'course_code'      => ['nullable', 'string', 'max:20'],
        ];
    }
}
