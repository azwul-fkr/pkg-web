<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Indikator;
use App\Models\SubKriteria;

class IndikatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data indikator terbaru dengan pagination
        $indikators = Indikator::with([
            'subKriteria.kriteria'
        ])
            ->latest()
            ->paginate(10);

        $kriterias = Kriteria::all();

        return view(
            'admin.indikators.index',
            compact('indikators', 'kriterias')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.indikators.index');
    }

    public function getSubKriterias($kriteriaId)
    {
        $subKriterias = SubKriteria::where(
            'kriteria_id',
            $kriteriaId
        )->get();

        return response()->json($subKriterias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'sub_kriteria_id' => 'required',
            'name' => 'required'
        ]);

        Indikator::create([
            'sub_kriteria_id' => $request->sub_kriteria_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.indikators.index')
            ->with('success', 'Indikator berhasil ditambahkan');
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
        return redirect()->route('admin.indikators.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validasi input
        $request->validate([
            'sub_kriteria_id' => 'required',
            'name' => 'required'
        ]);

        $indikator = Indikator::findOrFail($id);

        $indikator->update([
            'sub_kriteria_id' => $request->sub_kriteria_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.indikators.index')
            ->with('success', 'Indikator berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $indikator = Indikator::findOrFail($id);
        $indikator->delete();

        return redirect()
            ->route('admin.indikators.index')
            ->with('success', 'Indikator berhasil dihapus');
    }
}
