<?php

namespace App\Http\Controllers\Penilai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman settings penilai
     */
    public function index()
    {
        $user = auth()->user();

        $themes = [
            'light' => 'Light (Terang)',
            'dark' => 'Dark (Gelap)',
            'auto' => 'Auto (Sesuai Sistem)',
        ];

        return view('penilai.settings.index', compact('user', 'themes'));
    }

    /**
     * Update profil penilai
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($validated);

        return redirect()->route('penilai.settings.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update preferensi tema
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        auth()->user()->update(['theme_preference' => $validated['theme']]);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi tema berhasil disimpan.',
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('penilai.settings.index')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
