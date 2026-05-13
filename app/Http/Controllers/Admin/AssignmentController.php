<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Period;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with([
            'penilai',
            'guru.user',
            'period'
        ])->latest()->get();

        $penilais = User::whereHas('role', function ($q) {
            $q->where('name', 'penilai');
        })->get();

        $gurus = Guru::with('user')->get();

        $periods = Period::all();

        return view(
            'admin.assignments.index',
            compact(
                'assignments',
                'penilais',
                'gurus',
                'periods'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'penilai_id' => 'required',
            'guru_id' => 'required',
            'period_id' => 'required',
        ]);

        Assignment::create([
            'penilai_id' => $request->penilai_id,
            'guru_id' => $request->guru_id,
            'period_id' => $request->period_id,
        ]);

        return back()->with(
            'success',
            'Assignment berhasil dibuat'
        );
    }

    public function destroy($id)
    {
        Assignment::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Assignment berhasil dihapus'
        );
    }
}
