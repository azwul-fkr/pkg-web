<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Support\Facades\Storage;

abstract class BaseGuruApiController extends Controller
{
    protected function currentGuru(): Guru
    {
        return Guru::with([
            'user',
            'jabatan',
        ])
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    protected function success(array $payload = [], string $message = 'OK', int $status = 200)
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
        ], $payload), $status);
    }

    protected function error(string $message, int $status = 422, array $errors = [], ?string $code = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ], $status);
    }

    protected function fileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return url(Storage::url($path));
    }

    protected function formatGuru(Guru $guru): array
    {
        $guru->loadMissing([
            'user',
            'jabatan',
        ]);

        return [
            'id' => $guru->id,
            'user_id' => $guru->user_id,
            'name' => $guru->user?->name,
            'email' => $guru->user?->email,
            'theme_preference' => $guru->user?->theme_preference,
            'jabatan' => $guru->jabatan?->name,
            'nip' => $guru->nip,
            'phone' => $guru->phone,
            'address' => $guru->address,
            'subject' => $guru->subject,
            'bio' => $guru->bio,
            'photo_path' => $guru->photo_path,
            'photo_url' => $this->fileUrl($guru->photo_path),
            'website' => $guru->website,
            'social_media_twitter' => $guru->social_media_twitter,
            'social_media_instagram' => $guru->social_media_instagram,
            'social_media_linkedin' => $guru->social_media_linkedin,
            'achievements' => $guru->achievements ?? [],
            'certifications' => $guru->certifications ?? [],
        ];
    }
}
