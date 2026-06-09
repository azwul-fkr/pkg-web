<?php

namespace App\Http\Controllers\Api\Guru;

use App\Models\TeacherReflection;
use Illuminate\Http\Request;

class ReflectionController extends BaseGuruApiController
{
    public function store(Request $request, $evaluationId)
    {
        $validated = $request->validate([
            'reflection' => 'nullable|string',
            'improvement_plan' => 'nullable|string',
        ]);

        $guru = $this->currentGuru();

        $reflection = TeacherReflection::updateOrCreate(
            [
                'guru_id' => $guru->id,
                'evaluation_id' => $evaluationId,
            ],
            [
                'reflection' => $validated['reflection'] ?? null,
                'improvement_plan' => $validated['improvement_plan'] ?? null,
            ]
        );

        return $this->success([
            'reflection' => [
                'id' => $reflection->id,
                'guru_id' => $reflection->guru_id,
                'evaluation_id' => $reflection->evaluation_id,
                'reflection' => $reflection->reflection,
                'improvement_plan' => $reflection->improvement_plan,
                'created_at' => $reflection->created_at,
                'updated_at' => $reflection->updated_at,
            ],
        ], 'Refleksi berhasil disimpan.');
    }
}
