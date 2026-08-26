<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected array $documentTypes = [
        'passport_photo',
        'jamb_slip',
        'olevel_result',
        'birth_certificate',
        'lga_id',
    ];

    protected array $allowedStatuses = [
        'screening_passed',
        'screening_pending',
        'successfully_screened',
        'recommended_for_admission',
    ];

    public function index()
    {
        $candidate = auth('candidate')->user();

        if (! in_array($candidate->status, $this->allowedStatuses)) {
            return redirect()->route('candidate.dashboard')
                ->with('error', "You must complete screening before uploading documents. Submit O'level results first.");
        }

        $documents = $candidate->documents()->get()->keyBy('document_type');

        return view('candidate.documents.index', [
            'documents' => $documents,
            'documentTypes' => $this->documentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $candidate = auth('candidate')->user();

        if (! in_array($candidate->status, $this->allowedStatuses)) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'You must complete screening before uploading documents.');
        }

        $validated = $request->validate([
            'document_type' => ['required', 'in:' . implode(',', $this->documentTypes)],
            'file' => ['required', 'file', 'mimes:pdf,jpeg,jpg', 'max:2048'],
        ]);

        // Delete old document of this type if one exists (replace, not duplicate)
        $existing = $candidate->documents()->where('document_type', $validated['document_type'])->first();
        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        $path = $request->file('file')->store('documents/' . $candidate->id, 'public');

        Document::create([
            'candidate_id' => $candidate->id,
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $request->file('file')->getClientOriginalName(),
            'mime_type' => $request->file('file')->getClientMimeType(),
        ]);
        $this->maybeMarkSuccessfullyScreened($candidate);

        return back()->with('success', ucfirst(str_replace('_', ' ', $validated['document_type'])) . ' uploaded successfully.');
    }

    protected function maybeMarkSuccessfullyScreened($candidate) : void
    {

        if ($candidate->status !== 'screening_passed') {
            return;
        }
        $uploadedTypes = $candidate->documents()->pluck('document_type')->toArray();
        $allUploaded = empty(array_diff($this->documentTypes, $uploadedTypes));

        if ($allUploaded) {
            $candidate->update(['status' => 'successfully_screened']);
        }

    }
}
