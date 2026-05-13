<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::latest()->get();

        return view(
            'admin.periods.index',
            compact('periods')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        // hanya 1 periode aktif
        if ($request->is_active) {

            Period::where('is_active', true)
                ->update([
                    'is_active' => false
                ]);
        }

        Period::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ? true : false,
            'is_locked' => $request->is_locked ? true : false,
        ]);

        return back()->with(
            'success',
            'Periode berhasil ditambahkan'
        );
    }

    public function update(Request $request, $id)
    {
        $period = Period::findOrFail($id);

        if ($request->is_active) {

            Period::where('is_active', true)
                ->update([
                    'is_active' => false
                ]);
        }

        $period->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ? true : false,
            'is_locked' => $request->is_locked ? true : false,
        ]);

        return back()->with(
            'success',
            'Periode berhasil diupdate'
        );
    }

    public function destroy($id)
    {
        $period = Period::findOrFail($id);

        $period->delete();

        return back()->with(
            'success',
            'Periode berhasil dihapus'
        );
    }
}
