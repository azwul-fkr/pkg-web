<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
        public function index()
    {
        $guru = auth()->user()->guru;

        $evidences = Evidence::where('guru_id', $guru->id)
            ->latest()
            ->get();

        return view('guru.evidence.index', compact('evidences'));
    }

    public function create()
    {
        return view('guru.evidence.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'description' => 'required',
            'subject' => 'required',
            'class' => 'required',
            'tanggal' => 'required|date',
        ]);

        // upload file
        $filePath = $request->file('file')->store('evidences', 'public');

        Evidence::create([
            'guru_id' => auth()->user()->guru->id,
            'file' => $filePath,
            'description' => $request->description,
            'subject' => $request->subject,
            'class' => $request->class,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        return redirect()->route('guru.evidence.index')
            ->with('success', 'Evidence berhasil diupload');
    }

}
