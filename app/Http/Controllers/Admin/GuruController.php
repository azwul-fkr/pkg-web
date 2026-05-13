<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil data guru beserta relasi user dan jabatan
        $gurus = Guru::with([
            'user',
            'jabatan'
        ])->latest()->paginate(10);

        $users = User::whereHas('role', function ($q) {
            $q->where('name', 'guru');
        })
            ->where(function ($query) {
                $query->whereDoesntHave('guru');
            })
            ->get();

        $jabatans = Jabatan::all();

        return view(
            'admin.gurus.index',
            compact('gurus', 'users', 'jabatans')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.gurus.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate([
            'user_id' => 'required|unique:gurus,user_id',
            'jabatan_id' => 'required',
            'nip' => 'required',
            'subject' => 'required',
        ]);

        Guru::create([
            'user_id' => $request->user_id,
            'jabatan_id' => $request->jabatan_id,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('admin.gurus.index')
            ->with('success', 'Guru berhasil ditambahkan');
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
        return redirect()->route('admin.gurus.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'user_id' => [

            'required',

            Rule::unique('gurus', 'user_id')
                ->ignore(
                    $guru->user_id,
                    'user_id'
                )
            ],
            'jabatan_id' => 'required',
            'nip' => 'required',
            'subject' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $guru->update([
            'user_id' => $request->user_id,
            'jabatan_id' => $request->jabatan_id,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('admin.gurus.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()
            ->route('admin.gurus.index')
            ->with('success', 'Guru berhasil dihapus');
    }
}
