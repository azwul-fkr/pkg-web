<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\TeacherReflection;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function store(
        Request $request,
        $evaluationId
    ) {
        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->first();

        TeacherReflection::updateOrCreate(

            [
                'guru_id' => $guru->id,
                'evaluation_id' => $evaluationId,
            ],

            [
                'reflection' =>
                $request->reflection,

                'improvement_plan' =>
                $request->improvement_plan,
            ]
        );

        return back()->with(
            'success',
            'Refleksi berhasil disimpan'
        );
    }
}
