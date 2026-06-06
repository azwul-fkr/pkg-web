<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Ambil profil guru login saat ini.
     */
    private function currentGuru(): Guru
    {
        return Guru::with([
            'user',
            'jabatan',
        ])
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    /**
     * Tampilkan halaman settings guru
     */
    public function index()
    {
        $guru = $this->currentGuru();

        $themes = [
            'light' => 'Light (Terang)',
            'dark' => 'Dark (Gelap)',
            'auto' => 'Auto (Sesuai Sistem)',
        ];

        return view('guru.settings.index', compact('guru', 'themes'));
    }

    /**
     * Update biodata guru
     */
    public function updateBiodata(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'social_media_twitter' => 'nullable|url|max:255',
            'social_media_instagram' => 'nullable|url|max:255',
            'social_media_linkedin' => 'nullable|url|max:255',
        ]);

        $guru = $this->currentGuru();
        $guru->update($validated);

        return redirect()->route('guru.settings.index')
            ->with('success', 'Biodata berhasil diperbarui.');
    }

    /**
     * Upload foto profil
     */
    public function uploadPhoto(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $guru = $this->currentGuru();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($guru->photo_path) {
                Storage::disk('public')->delete($guru->photo_path);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('guru-photos', 'public');
            $guru->update(['photo_path' => $path]);
        }

        return redirect()->route('guru.settings.index')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Update preferensi tema
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 401);
        }

        $user->theme_preference = $validated['theme'];
        $user->save();

        if (!$request->expectsJson()) {
            return redirect()
                ->route('guru.settings.index')
                ->with('success', 'Preferensi tema berhasil disimpan.');
        }

        return response()->json([
            'success' => true,
            'theme' => $user->theme_preference,
            'message' => 'Preferensi tema berhasil disimpan.',
        ]);
    }

    /**
     * Tambah achievement
     */
    public function addAchievement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string|max:500',
        ]);

        $guru = $this->currentGuru();
        $achievements = $guru->achievements ?? [];

        $achievements[] = array_merge($validated, ['id' => uniqid()]);

        $guru->update(['achievements' => $achievements]);

        return redirect()->route('guru.settings.index')
            ->with('success', 'Pencapaian berhasil ditambahkan.');
    }

    /**
     * Hapus achievement
     */
    public function deleteAchievement(Request $request)
    {
        $validated = $request->validate([
            'achievement_id' => 'required|string',
        ]);

        $guru = $this->currentGuru();
        $achievements = $guru->achievements ?? [];

        $achievements = array_filter($achievements, function ($item) use ($validated) {
            return $item['id'] !== $validated['achievement_id'];
        });

        $guru->update(['achievements' => array_values($achievements)]);

        return redirect()->route('guru.settings.index')
            ->with('success', 'Pencapaian berhasil dihapus.');
    }

    /**
     * Tambah certification
     */
    public function addCertification(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issued_date' => 'required|date',
            'expires_date' => 'nullable|date|after:issued_date',
            'credential_url' => 'nullable|url|max:255',
        ]);

        $guru = $this->currentGuru();
        $certifications = $guru->certifications ?? [];

        $certifications[] = array_merge($validated, ['id' => uniqid()]);

        $guru->update(['certifications' => $certifications]);

        return redirect()->route('guru.settings.index')
            ->with('success', 'Sertifikasi berhasil ditambahkan.');
    }

    /**
     * Hapus certification
     */
    public function deleteCertification(Request $request)
    {
        $validated = $request->validate([
            'certification_id' => 'required|string',
        ]);

        $guru = $this->currentGuru();
        $certifications = $guru->certifications ?? [];

        $certifications = array_filter($certifications, function ($item) use ($validated) {
            return $item['id'] !== $validated['certification_id'];
        });

        $guru->update(['certifications' => array_values($certifications)]);

        return redirect()->route('guru.settings.index')
            ->with('success', 'Sertifikasi berhasil dihapus.');
    }
}
