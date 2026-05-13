<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use App\Models\Guru;
use App\Models\Indikator;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;

class EvidenceController extends Controller
{
    public function index()
    {
        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        /*
    =====================================
    EVIDENCES
    =====================================
    */

        $evidences = Evidence::with([

            'kriteria',
            'subKriteria',
            'indikator'

        ])

            ->where(
                'guru_id',
                $guru->id
            )

            ->latest()

            ->get();

        /*
    =====================================
    KRITERIA
    =====================================
    */

        $kriterias = Kriteria::with([
            'subKriterias.indikators'
        ])->get();

        return view(
            'guru.evidence.index',
            compact(
                'evidences',
                'kriterias'
            )
        );
    }

    public function show($id)
    {
        $evidence = Evidence::with([
            'kriteria',
            'subKriteria',
            'indikator',
            'guru.user'
        ])->findOrFail($id);

        return view(
            'guru.evidence.show',
            compact('evidence')
        );
    }


    public function store(Request $request)
    {
        /*
    =====================================================
    VALIDATION
    =====================================================
    */
        $request->validate([

            'file' =>
            'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',

            'description' =>
            'required|string',

            'subject' =>
            'required|string',

            'kelas' =>
            'required|string',

            'tanggal' =>
            'required|date',

            'kriteria_id' =>
            'required|exists:kriterias,id',

            'sub_kriteria_id' =>
            'required|exists:sub_kriterias,id',

            'indikator_id' =>
            'required|exists:indikators,id',

        ]);

        /*
    =====================================================
    GURU LOGIN
    =====================================================
    */

        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->first();

        if (!$guru) {

            return back()->with(
                'error',
                'Data guru tidak ditemukan'
            );
        }

        /*
    =====================================================
    FILE
    =====================================================
    */

        $filePath = null;

        if ($request->hasFile('file')) {

            $filePath = $request
                ->file('file')
                ->store(
                    'evidences',
                    'public'
                );
        }

        /*
    =====================================================
    SAVE MANUAL
    =====================================================
    */

        $evidence = new Evidence();

        $evidence->guru_id =
            $guru->id;

        $evidence->kriteria_id =
            $request->kriteria_id;

        $evidence->sub_kriteria_id =
            $request->sub_kriteria_id;

        $evidence->indikator_id =
            $request->indikator_id;

        $evidence->file =
            $filePath;

        $evidence->description =
            $request->description;

        $evidence->subject =
            $request->subject;

        $evidence->kelas =
            $request->kelas;

        $evidence->tanggal =
            $request->tanggal;

        $evidence->status =
            'pending';

        /*
    =====================================================
    TRY SAVE
    =====================================================
    */

        $result = $evidence->save();

        dd(

            $result,

            $evidence->toArray()

        );

        /*
    =====================================================
    REDIRECT
    =====================================================
    */

        return redirect()

            ->route('guru.evidence.index')

            ->with(
                'success',
                'Evidence berhasil diupload'
            );
    }



    public function adminIndex()
    {
        $evidences = Evidence::with('guru.user')
            ->latest()
            ->get();

        return view('admin.evidence.index', compact('evidences'));
    }

    public function approve($id)
    {
        $evidence = Evidence::findOrFail($id);

        $evidence->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Evidence approved');
    }

    public function reject($id)
    {
        $evidence = Evidence::findOrFail($id);

        $evidence->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Evidence rejected');
    }
    public function edit($id)
    {
        return redirect()
            ->route('guru.evidence.index');
    }

    public function update(
        Request $request,
        $id
    ) {
        $evidence = Evidence::findOrFail($id);

        /*
    =====================================
    FILE
    =====================================
    */

        $path = $evidence->file;

        if ($request->hasFile('file')) {

            $path = $request
                ->file('file')
                ->store(
                    'evidences',
                    'public'
                );
        }

        /*
    =====================================
    UPDATE
    =====================================
    */

        $evidence->update([

            'kriteria_id' =>
            $request->kriteria_id,

            'sub_kriteria_id' =>
            $request->sub_kriteria_id,

            'indikator_id' =>
            $request->indikator_id,

            'file' =>
            $path,

            'description' =>
            $request->description,

            'subject' =>
            $request->subject,

            'class' =>
            $request->class,

            'tanggal' =>
            $request->tanggal,

            // kembali pending setelah revisi
            'status' => 'pending',
        ]);

        return redirect()
            ->route(
                'guru.evidence.index'
            )
            ->with(
                'success',
                'Evidence berhasil direvisi'
            );
    }
}
