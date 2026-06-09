@extends('layouts.app')

@section('title', 'Pengaturan Profil Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card overflow-hidden">
            <div class="border-b border-slate-200/80 px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-center gap-4">
                        @if ($guru->photo_path)
                            <img src="{{ asset('storage/' . $guru->photo_path) }}" alt="{{ $guru->user->name }}"
                                class="h-16 w-16 rounded-2xl object-cover ring-4 ring-cyan-50">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-2xl font-semibold text-white shadow-lg shadow-cyan-900/10">
                                {{ strtoupper(substr($guru->user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h2 class="truncate text-2xl font-semibold tracking-tight text-slate-900">{{ $guru->user->name }}</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">NIP: {{ $guru->nip ?? 'Belum diisi' }}</p>
                            <p class="text-sm leading-6 text-slate-500">Mata Pelajaran: {{ $guru->subject ?? 'Belum diisi' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[.18em] text-slate-500">Preferensi Tema</p>
                        <select id="themeSelect" class="form-control mt-2 text-sm min-w-[160px]">
                            <option value="light" @selected(auth()->user()->theme_preference === 'light')>Light</option>
                            <option value="dark" @selected(auth()->user()->theme_preference === 'dark')>Dark</option>
                            <option value="auto" @selected(auth()->user()->theme_preference === 'auto')>Auto</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200/80 px-3 py-3 sm:px-5">
                <div class="flex gap-2 overflow-x-auto">
                    <button class="tab-btn active rounded-full border border-cyan-100 bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-700 transition"
                        onclick="switchTab(event, 'biodata')">
                        <i data-lucide="user" class="mr-2 inline h-4 w-4"></i>Biodata
                    </button>
                    <button class="tab-btn rounded-full border border-transparent px-4 py-2 text-sm font-semibold text-slate-500 transition hover:border-slate-200 hover:bg-slate-50"
                        onclick="switchTab(event, 'media')">
                        <i data-lucide="link" class="mr-2 inline h-4 w-4"></i>Media Sosial
                    </button>
                    <button class="tab-btn rounded-full border border-transparent px-4 py-2 text-sm font-semibold text-slate-500 transition hover:border-slate-200 hover:bg-slate-50"
                        onclick="switchTab(event, 'achievement')">
                        <i data-lucide="trophy" class="mr-2 inline h-4 w-4"></i>Pencapaian
                    </button>
                    <button class="tab-btn rounded-full border border-transparent px-4 py-2 text-sm font-semibold text-slate-500 transition hover:border-slate-200 hover:bg-slate-50"
                        onclick="switchTab(event, 'certification')">
                        <i data-lucide="award" class="mr-2 inline h-4 w-4"></i>Sertifikasi
                    </button>
                </div>
            </div>
        </div>

        <div id="tab-biodata" class="tab-content">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="page-card p-5 md:col-span-2">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold tracking-tight text-slate-900">
                                <i data-lucide="image" class="mr-2 inline h-4 w-4"></i>Foto Profil
                            </h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Gunakan foto yang jelas agar profil lebih mudah dikenali.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('guru.settings.uploadPhoto') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="file" name="photo" accept="image/*" class="form-control text-sm" required>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-sm">
                                <i data-lucide="upload" class="h-4 w-4"></i> Upload Foto
                            </button>
                        </div>
                    </form>
                </div>

                <div class="page-card p-5">
                    <h3 class="text-base font-semibold tracking-tight text-slate-900">
                        <i data-lucide="info" class="mr-2 inline h-4 w-4"></i>Informasi Dasar
                    </h3>
                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <p><span class="font-semibold text-slate-700">Nama:</span> {{ $guru->user->name }}</p>
                        <p><span class="font-semibold text-slate-700">Email:</span> {{ $guru->user->email }}</p>
                        <p><span class="font-semibold text-slate-700">NIP:</span> {{ $guru->nip ?? '-' }}</p>
                        <p><span class="font-semibold text-slate-700">Jabatan:</span> {{ $guru->jabatan->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="page-card p-5">
                    <h3 class="text-base font-semibold tracking-tight text-slate-900">
                        <i data-lucide="map-pin" class="mr-2 inline h-4 w-4"></i>Kontak & Alamat
                    </h3>
                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <p><span class="font-semibold text-slate-700">Telepon:</span> {{ $guru->phone ?? '-' }}</p>
                        <p><span class="font-semibold text-slate-700">Alamat:</span> {{ $guru->address ?? '-' }}</p>
                    </div>
                </div>

                <div class="page-card p-5 md:col-span-2">
                    <h3 class="text-base font-semibold tracking-tight text-slate-900">
                        <i data-lucide="edit" class="mr-2 inline h-4 w-4"></i>Edit Biodata
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.updateBiodata') }}" class="mt-4 space-y-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="form-label text-sm">Nomor Telepon</label>
                                <input type="tel" name="phone" value="{{ $guru->phone }}" maxlength="15" class="form-control text-sm">
                            </div>
                            <div>
                                <label class="form-label text-sm">Alamat</label>
                                <textarea name="address" rows="3" class="form-control text-sm">{{ $guru->address }}</textarea>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-sm">Biodata / Tentang Saya</label>
                            <textarea name="bio" rows="4" placeholder="Tuliskan biodata Anda di sini..." maxlength="1000" class="form-control text-sm">{{ $guru->bio }}</textarea>
                            <p class="mt-1 text-xs text-slate-400">Maksimal 1000 karakter</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-sm">
                                <i data-lucide="save" class="h-4 w-4"></i> Simpan Biodata
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab-media" class="tab-content hidden">
            <div class="page-card p-5">
                <h3 class="text-base font-semibold tracking-tight text-slate-900">
                    <i data-lucide="share-2" class="mr-2 inline h-4 w-4"></i>Media Sosial & Website
                </h3>
                <form method="POST" action="{{ route('guru.settings.updateBiodata') }}" class="mt-4 grid gap-4 md:grid-cols-2">
                    @csrf

                    <div class="md:col-span-2">
                        <label class="form-label text-sm flex items-center">
                            <i data-lucide="globe" class="mr-2 h-4 w-4"></i>Website
                        </label>
                        <input type="url" name="website" value="{{ $guru->website }}" placeholder="https://example.com" class="form-control text-sm">
                    </div>

                    <div>
                        <label class="form-label text-sm flex items-center">
                            <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7a4.5 4.5 0 00-3.14 1.53" />
                            </svg>
                            Twitter
                        </label>
                        <input type="url" name="social_media_twitter" value="{{ $guru->social_media_twitter }}" placeholder="https://twitter.com/username" class="form-control text-sm">
                    </div>

                    <div>
                        <label class="form-label text-sm flex items-center">
                            <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2" />
                                <path d="M16 11.37A4 4 0 1112.63 8M17.5 6.5h.01" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Instagram
                        </label>
                        <input type="url" name="social_media_instagram" value="{{ $guru->social_media_instagram }}" placeholder="https://instagram.com/username" class="form-control text-sm">
                    </div>

                    <div>
                        <label class="form-label text-sm flex items-center">
                            <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" fill="currentColor" />
                                <circle cx="4" cy="4" r="2" fill="currentColor" />
                            </svg>
                            LinkedIn
                        </label>
                        <input type="url" name="social_media_linkedin" value="{{ $guru->social_media_linkedin }}" placeholder="https://linkedin.com/in/username" class="form-control text-sm">
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-1">
                        <button type="submit" class="btn-primary text-sm">
                            <i data-lucide="save" class="h-4 w-4"></i> Simpan Media Sosial
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="tab-achievement" class="tab-content hidden">
            <div class="space-y-4">
                @if ($guru->achievements && count($guru->achievements) > 0)
                    <div class="space-y-3">
                        @foreach ($guru->achievements as $achievement)
                            <div class="page-card border-l-4 border-l-amber-400 p-4 sm:p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold tracking-tight text-slate-900 sm:text-base">{{ $achievement['title'] }}</h4>
                                        <p class="mt-1 text-xs text-slate-500">Tahun: {{ $achievement['year'] }}</p>
                                        @if ($achievement['description'])
                                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $achievement['description'] }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('guru.settings.deleteAchievement', ['achievement_id' => $achievement['id']]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="achievement_id" value="{{ $achievement['id'] }}">
                                        <button type="submit" class="btn-danger text-xs" onclick="return confirm('Hapus pencapaian ini?')">
                                            <i data-lucide="trash" class="h-3 w-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">Belum ada pencapaian. Tambahkan pencapaian Anda.</div>
                @endif

                <div class="page-card p-5">
                    <h3 class="text-base font-semibold tracking-tight text-slate-900">
                        <i data-lucide="plus" class="mr-2 inline h-4 w-4"></i>Tambah Pencapaian
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.addAchievement') }}" class="mt-4 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-sm">Judul Pencapaian</label>
                                <input type="text" name="title" required placeholder="Misal: Juara 1 Olimpiade Matematika" class="form-control text-sm">
                            </div>
                            <div>
                                <label class="form-label text-sm">Tahun</label>
                                <input type="number" name="year" required min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" class="form-control text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-sm">Deskripsi (opsional)</label>
                            <textarea name="description" rows="3" maxlength="500" placeholder="Penjelasan singkat tentang pencapaian..." class="form-control text-sm"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-sm">
                                <i data-lucide="plus" class="h-4 w-4"></i> Tambah Pencapaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab-certification" class="tab-content hidden">
            <div class="space-y-4">
                @if ($guru->certifications && count($guru->certifications) > 0)
                    <div class="space-y-3">
                        @foreach ($guru->certifications as $cert)
                            <div class="page-card border-l-4 border-l-emerald-400 p-4 sm:p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold tracking-tight text-slate-900 sm:text-base">{{ $cert['name'] }}</h4>
                                        <p class="mt-1 text-xs text-slate-500">Penerbit: {{ $cert['issuer'] }}</p>
                                        <p class="text-xs text-slate-500">
                                            Tanggal: {{ \Carbon\Carbon::parse($cert['issued_date'])->format('d M Y') }}
                                            @if ($cert['expires_date'])
                                                s/d {{ \Carbon\Carbon::parse($cert['expires_date'])->format('d M Y') }}
                                            @endif
                                        </p>
                                        @if ($cert['credential_url'])
                                            <a href="{{ $cert['credential_url'] }}" target="_blank" class="link-action mt-2">
                                                <i data-lucide="external-link" class="h-3 w-3"></i> Lihat Kredensial
                                            </a>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('guru.settings.deleteCertification', ['certification_id' => $cert['id']]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="certification_id" value="{{ $cert['id'] }}">
                                        <button type="submit" class="btn-danger text-xs" onclick="return confirm('Hapus sertifikasi ini?')">
                                            <i data-lucide="trash" class="h-3 w-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">Belum ada sertifikasi. Tambahkan sertifikasi Anda.</div>
                @endif

                <div class="page-card p-5">
                    <h3 class="text-base font-semibold tracking-tight text-slate-900">
                        <i data-lucide="plus" class="mr-2 inline h-4 w-4"></i>Tambah Sertifikasi
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.addCertification') }}" class="mt-4 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-sm">Nama Sertifikasi</label>
                                <input type="text" name="name" required placeholder="Misal: Google Cloud Certified Associate" class="form-control text-sm">
                            </div>
                            <div>
                                <label class="form-label text-sm">Penerbit</label>
                                <input type="text" name="issuer" required placeholder="Misal: Google" class="form-control text-sm">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-sm">Tanggal Terbit</label>
                                <input type="date" name="issued_date" required class="form-control text-sm">
                            </div>
                            <div>
                                <label class="form-label text-sm">Tanggal Kadaluarsa (opsional)</label>
                                <input type="date" name="expires_date" class="form-control text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-sm">URL Kredensial (opsional)</label>
                            <input type="url" name="credential_url" placeholder="https://www.credly.com/..." class="form-control text-sm">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-sm">
                                <i data-lucide="plus" class="h-4 w-4"></i> Tambah Sertifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(event, tabName) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active', 'border-cyan-100', 'bg-cyan-50', 'text-cyan-700');
                el.classList.add('border-transparent', 'text-slate-500');
            });

            const activeTab = document.getElementById(`tab-${tabName}`);
            if (activeTab) {
                activeTab.classList.remove('hidden');
            }

            const button = event.currentTarget;
            button.classList.add('active', 'border-cyan-100', 'bg-cyan-50', 'text-cyan-700');
            button.classList.remove('border-transparent', 'text-slate-500');
        }

        document.getElementById('themeSelect')?.addEventListener('change', function () {
            fetch('{{ route("guru.settings.updateTheme") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    theme: this.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    applyTheme(this.value);
                }
            });
        });

        function applyTheme(theme) {
            const html = document.documentElement;
            if (theme === 'dark') {
                html.classList.add('dark');
            } else if (theme === 'light') {
                html.classList.remove('dark');
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        applyTheme(document.getElementById('themeSelect')?.value || 'light');
    </script>
@endpush
