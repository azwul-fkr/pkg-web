<?php

namespace App\Http\Controllers\Api\Guru;

use App\Models\Evidence;
use App\Models\Guru;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends BaseGuruApiController
{
    public function index()
    {
        $guru = $this->currentGuru();

        $evidences = Evidence::with([
            'kriteria',
            'subKriteria',
            'indikator',
        ])
            ->where('guru_id', $guru->id)
            ->latest()
            ->get()
            ->map(fn ($evidence) => $this->formatEvidence($evidence))
            ->values();

        $kriterias = Kriteria::with(['subKriterias.indikators'])->get();

        return $this->success([
            'evidences' => $evidences,
            'kriterias' => $kriterias,
        ]);
    }

    public function show($id)
    {
        $guru = $this->currentGuru();

        $evidence = Evidence::with([
            'kriteria',
            'subKriteria',
            'indikator',
            'guru.user',
        ])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);

        return $this->success([
            'evidence' => $this->formatEvidence($evidence),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'description' => 'required|string',
            'subject' => 'required|string',
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
            'kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria_id' => 'required|exists:sub_kriterias,id',
            'indikator_id' => 'required|exists:indikators,id',
        ]);

        $guru = $this->currentGuru();

        $filePath = $request->file('file')->store('evidences', 'public');

        $evidence = Evidence::create([
            'guru_id' => $guru->id,
            'kriteria_id' => $validated['kriteria_id'],
            'sub_kriteria_id' => $validated['sub_kriteria_id'],
            'indikator_id' => $validated['indikator_id'],
            'file' => $filePath,
            'description' => $validated['description'],
            'subject' => $validated['subject'],
            'kelas' => $validated['kelas'],
            'tanggal' => $validated['tanggal'],
            'status' => 'pending',
        ]);

        return $this->success([
            'evidence' => $this->formatEvidence($evidence->load(['kriteria', 'subKriteria', 'indikator'])),
        ], 'Evidence berhasil diupload.', 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'description' => 'required|string',
            'subject' => 'required|string',
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
            'kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria_id' => 'required|exists:sub_kriterias,id',
            'indikator_id' => 'required|exists:indikators,id',
        ]);

        $guru = $this->currentGuru();

        $evidence = Evidence::where('guru_id', $guru->id)->findOrFail($id);

        if ($request->hasFile('file')) {
            if ($evidence->file) {
                Storage::disk('public')->delete($evidence->file);
            }

            $evidence->file = $request->file('file')->store('evidences', 'public');
        }

        $evidence->fill([
            'kriteria_id' => $validated['kriteria_id'],
            'sub_kriteria_id' => $validated['sub_kriteria_id'],
            'indikator_id' => $validated['indikator_id'],
            'description' => $validated['description'],
            'subject' => $validated['subject'],
            'kelas' => $validated['kelas'],
            'tanggal' => $validated['tanggal'],
            'status' => 'pending',
        ]);

        $evidence->save();

        return $this->success([
            'evidence' => $this->formatEvidence($evidence->load(['kriteria', 'subKriteria', 'indikator'])),
        ], 'Evidence berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = $this->currentGuru();
        $evidence = Evidence::where('guru_id', $guru->id)->findOrFail($id);

        if ($evidence->file) {
            Storage::disk('public')->delete($evidence->file);
        }

        $evidence->delete();

        return $this->success([], 'Evidence berhasil dihapus.');
    }

    private function formatEvidence(Evidence $evidence): array
    {
        return [
            'id' => $evidence->id,
            'guru_id' => $evidence->guru_id,
            'kriteria' => $evidence->kriteria?->name,
            'sub_kriteria' => $evidence->subKriteria?->name,
            'indikator' => $evidence->indikator?->name,
            'file' => $evidence->file,
            'file_url' => $this->fileUrl($evidence->file),
            'description' => $evidence->description,
            'subject' => $evidence->subject,
            'kelas' => $evidence->kelas,
            'tanggal' => $evidence->tanggal,
            'status' => $evidence->status,
            'approved_at' => $evidence->approved_at ?? null,
            'created_at' => $evidence->created_at,
            'updated_at' => $evidence->updated_at,
        ];
    }
}
