<?php

namespace App\Http\Controllers\Api\Guru;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseGuruApiController
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::with([
            'role',
            'guru.jabatan',
        ])
            ->where('email', $validated['email'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error(
                'Email atau password tidak valid.',
                422,
                [],
                'INVALID_CREDENTIALS'
            );
        }

        if (!$user->role || strtolower($user->role->name) !== 'guru') {
            return $this->error(
                'Akun ini bukan role guru.',
                403,
                [],
                'INVALID_ROLE'
            );
        }

        if (!$user->guru) {
            return $this->error(
                'Profil guru belum tersedia. Hubungi admin untuk melengkapi data guru.',
                422,
                [],
                'GURU_PROFILE_MISSING'
            );
        }

        $token = $user->createToken(
            $validated['device_name'] ?? 'guru-mobile'
        )->plainTextToken;

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'theme_preference' => $user->theme_preference,
                'role' => $user->role?->name,
            ],
            'guru' => $user->guru ? $this->formatGuru($user->guru) : null,
        ], 'Login berhasil.');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('Sesi tidak valid.', 401, [], 'UNAUTHENTICATED');
        }

        $user->load([
            'role',
            'guru.jabatan',
        ]);

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'theme_preference' => $user->theme_preference,
                'role' => $user->role?->name,
            ],
            'guru' => $user->guru ? $this->formatGuru($user->guru) : null,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success([], 'Logout berhasil.');
    }
}
