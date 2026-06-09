<?php

namespace App\Http\Controllers\Api\Guru;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends BaseGuruApiController
{
    public function index()
    {
        $guru = $this->currentGuru();

        return $this->success([
            'guru' => $this->formatGuru($guru),
            'themes' => [
                'light' => 'Light (Terang)',
                'dark' => 'Dark (Gelap)',
                'auto' => 'Auto (Sesuai Sistem)',
            ],
        ]);
    }

    public function updateProfile(Request $request)
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

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Biodata berhasil diperbarui.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $guru = $this->currentGuru();

        if ($guru->photo_path) {
            Storage::disk('public')->delete($guru->photo_path);
        }

        $path = $request->file('photo')->store('guru-photos', 'public');
        $guru->update(['photo_path' => $path]);

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Foto profil berhasil diperbarui.');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $user = $request->user();
        $user->update([
            'theme_preference' => $validated['theme'],
        ]);

        return $this->success([
            'theme' => $user->theme_preference,
        ], 'Preferensi tema berhasil disimpan.');
    }

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

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Pencapaian berhasil ditambahkan.', 201);
    }

    public function deleteAchievement(Request $request, $achievementId)
    {
        $guru = $this->currentGuru();
        $achievements = $guru->achievements ?? [];

        $achievements = array_values(array_filter($achievements, fn ($item) => ($item['id'] ?? null) !== $achievementId));
        $guru->update(['achievements' => $achievements]);

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Pencapaian berhasil dihapus.');
    }

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

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Sertifikasi berhasil ditambahkan.', 201);
    }

    public function deleteCertification(Request $request, $certificationId)
    {
        $guru = $this->currentGuru();
        $certifications = $guru->certifications ?? [];

        $certifications = array_values(array_filter($certifications, fn ($item) => ($item['id'] ?? null) !== $certificationId));
        $guru->update(['certifications' => $certifications]);

        return $this->success([
            'guru' => $this->formatGuru($guru->fresh()),
        ], 'Sertifikasi berhasil dihapus.');
    }
}
