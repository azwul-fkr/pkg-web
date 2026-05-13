<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data sub kriteria terbaru dengan pagination
        $subKriterias = SubKriteria::with('kriteria')
            ->latest()
            ->paginate(10);

        $kriterias = Kriteria::all();

        return view(
            'admin.sub-kriterias.index',
            compact('subKriterias', 'kriterias')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.sub-kriterias.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'kriteria_id' => 'required',
            'kode' => 'required',
            'name' => 'required',
            'bobot' => 'required|numeric|min:0'
        ]);

        // total bobot kompetensi
        $totalBobot = SubKriteria::where(
            'kriteria_id',
            $request->kriteria_id
        )->sum('bobot');

        // validasi
        if (($totalBobot + $request->bobot) > 100) {

            return back()->withErrors([
                'bobot' =>
                'Total bobot kompetensi pada kriteria ini tidak boleh lebih dari 100%'
            ])->withInput();
        }

        SubKriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'kode' => $request->kode,
            'name' => $request->name,
            'bobot' => $request->bobot,
        ]);

        return redirect()
            ->route('admin.sub-kriterias.index')
            ->with(
                'success',
                'Kompetensi berhasil ditambahkan'
            );
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
        return redirect()->route('admin.sub-kriterias.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'kriteria_id' => 'required',
            'kode' => 'required',
            'name' => 'required',
            'bobot' => 'required|numeric|min:0'
        ]);

        $subKriteria = SubKriteria::findOrFail($id);

        // total tanpa data lama
        $totalBobot = SubKriteria::where(
            'kriteria_id',
            $request->kriteria_id
        )
            ->where('id', '!=', $id)
            ->sum('bobot');

        if (($totalBobot + $request->bobot) > 100) {

            return back()->withErrors([
                'bobot' =>
                'Total bobot kompetensi pada kriteria ini tidak boleh lebih dari 100%'
            ])->withInput();
        }

        $subKriteria->update([
            'kriteria_id' => $request->kriteria_id,
            'kode' => $request->kode,
            'name' => $request->name,
            'bobot' => $request->bobot,
        ]);

        return redirect()
            ->route('admin.sub-kriterias.index')
            ->with(
                'success',
                'Kompetensi berhasil diupdate'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $subKriteria = SubKriteria::findOrFail($id);

        $subKriteria->delete();

        return redirect()
            ->route('admin.sub-kriterias.index')
            ->with('success', 'Kompetensi berhasil dihapus');
    }
}
