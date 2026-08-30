<?php

namespace App\Exports;

use App\Models\Candidate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class RecommendedCandidatesExport implements FromCollection
{
    public function collection(): Collection
    {
        return Candidate::with('course')
            ->where('status', 'recommended_for_admission')
            ->get();
    }

    public function headings(): array
    {
        return [
            'JAMB Registration Number',
            'Surname',
            'First Name',
            'Course',
            'UTME Score',
            'O\'Level Status',
        ];
    }

    public function map($candidate): array
    {
        return [
            $candidate->jamb_reg_number,
            $candidate->surname,
            $candidate->first_name,
            $candidate->course->name ?? 'N/A',
            $candidate->utme_score,
            $candidate->olevelResult?->is_verified ? 'Verified' : 'Not Verified',
        ];
    }
}
