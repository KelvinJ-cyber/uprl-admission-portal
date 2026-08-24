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

    public function index()
    {
        $candidate = auth('candidate')->user();
        $documents = $candidate->documents()->get()->keyBy('document_type');

        return view('candidate.documents.index', [
            'documents' => $documents,
            'documentTypes' => $this->documentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $candidate = auth('candidate')->user();

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

        return back()->with('success', ucfirst(str_replace('_', ' ', $validated['document_type'])) . ' uploaded successfully.');
    }
}
