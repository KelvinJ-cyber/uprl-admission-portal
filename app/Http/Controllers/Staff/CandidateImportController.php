<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Imports\CandidatesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CandidateImportController extends Controller
{
    public function create()
    {
        return view('staff.candidates.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:5120'],
        ]);

        $import = new CandidatesImport();

        Excel::import($import, $request->file('file'));

        $failures = $import->failures()->map(function ($failure) {
            return [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
            ];
        });

        if ($failures->count() > 0) {
            return back()->with([
                'status' => 'Import completed with some rows skipped.',
                'failures_count' => $failures->count(),
                'failures' => $failures,
            ]);
        }

        return back()->with('status', 'All candidates imported successfully.');
    }

}
