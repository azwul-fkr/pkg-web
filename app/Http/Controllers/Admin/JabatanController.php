<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::latest()
            ->paginate(10);

        return view(
            'admin.jabatans.index',
            compact('jabatans')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.jabatans.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Jabatan::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.jabatans.index')
            ->with('success', 'Jabatan berhasil ditambahkan');
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
        return redirect()->route('admin.jabatans.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $jabatan = Jabatan::findOrFail($id);

        $jabatan->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.jabatans.index')
            ->with('success', 'Jabatan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $jabatan->delete();

        return redirect()
            ->route('admin.jabatans.index')
            ->with('success', 'Jabatan berhasil dihapus');
    }
}
