<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Indikator;
use App\Models\SubKriteria;
use App\Models\IndikatorScore;

class IndikatorScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data indikator score terbaru dengan pagination
        $indikatorScores = IndikatorScore::with([
            'indikator.subKriteria.kriteria'
        ])
            ->latest()
            ->paginate(10);

        $kriterias = Kriteria::all();

        return view(
            'admin.indikator-scores.index',
            compact('indikatorScores', 'kriterias')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.indikator-scores.index');
    }

    public function getIndikators($subId)
    {
        $indikators = Indikator::where(
            'sub_kriteria_id',
            $subId
        )->get();

        return response()->json($indikators);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'indikator_id' => 'required',
            'score' => 'required',
            'description' => 'required',
        ]);

        IndikatorScore::create([
            'indikator_id' => $request->indikator_id,
            'score' => $request->score,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.indikator-scores.index')
            ->with('success', 'Rubrik score berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.indikator-scores.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'indikator_id' => 'required',
            'score' => 'required',
            'description' => 'required',
        ]);

        $indikatorScore = IndikatorScore::findOrFail($id);

        $indikatorScore->update([
            'indikator_id' => $request->indikator_id,
            'score' => $request->score,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.indikator-scores.index')
            ->with('success', 'Rubrik score berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $indikatorScore = IndikatorScore::findOrFail($id);
        $indikatorScore->delete();

        return redirect()
            ->route('admin.indikator-scores.index')
            ->with('success', 'Rubrik score berhasil dihapus');
    }
}
