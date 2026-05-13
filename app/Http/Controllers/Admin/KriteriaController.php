<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data kriteria terbaru dengan pagination
        $totalBobot = Kriteria::sum('bobot');
        $kriterias = Kriteria::latest()
            ->paginate(10);

        return view(
            'admin.kriterias.index',
            compact('kriterias', 'totalBobot')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.kriterias.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input

        $request->validate([
            'name' => 'required',
            'bobot' => 'required|numeric|min:0'
        ]);

        // total bobot sekarang
        $totalBobot = Kriteria::sum('bobot');

        // cek jika melebihi 100
        if (($totalBobot + $request->bobot) > 100) {

            return back()->withErrors([
                'bobot' =>
                'Total bobot kriteria tidak boleh lebih dari 100%'
            ])->withInput();
        }

        Kriteria::create([
            'name' => $request->name,
            'bobot' => $request->bobot,
        ]);

        return redirect()
            ->route('admin.kriterias.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
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
        return redirect()->route('admin.kriterias.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validasi input
        $request->validate([
            'name' => 'required',
            'bobot' => 'required|numeric|min:0'
        ]);

        $kriteria = Kriteria::findOrFail($id);

        // total tanpa bobot lama
        $totalBobot = Kriteria::where('id', '!=', $id)
            ->sum('bobot');

        if (($totalBobot + $request->bobot) > 100) {

            return back()->withErrors([
                'bobot' =>
                'Total bobot kriteria tidak boleh lebih dari 100%'
            ])->withInput();
        }

        $kriteria->update([
            'name' => $request->name,
            'bobot' => $request->bobot,
        ]);

        return redirect()
            ->route('admin.kriterias.index')
            ->with('success', 'Kriteria berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $kriteria = Kriteria::findOrFail($id);

        $kriteria->delete();

        return redirect()
            ->route('admin.kriterias.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }
}
