@extends('layouts.app')

@section('title', 'Pengaturan Profil Guru')

@section('content')
    <div class="page-stack">
        <!-- PROFILE HEADER -->
        <div class="page-card p-3 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if ($guru->photo_path)
                        <img src="{{ asset('storage/' . $guru->photo_path) }}" alt="{{ $guru->user->name }}"
                            class="w-16 h-16 rounded-full object-cover border-4 border-cyan-500">
                    @else
                        <div
                            class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-white font-bold text-2xl">
                            {{ strtoupper(substr($guru->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $guru->user->name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">NIP: {{ $guru->nip ?? 'Belum diisi' }}</p>
                        <p class="text-sm text-slate-500">Mata Pelajaran: {{ $guru->subject ?? 'Belum diisi' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Preferensi Tema</p>
                        <select id="themeSelect" class="form-control text-sm mt-1">
                            <option value="light" @selected(auth()->user()->theme_preference === 'light')>Light</option>
                            <option value="dark" @selected(auth()->user()->theme_preference === 'dark')>Dark</option>
                            <option value="auto" @selected(auth()->user()->theme_preference === 'auto')>Auto</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS NAVIGATION -->
        <div class="page-card p-0 border-b">
            <div class="flex border-b overflow-x-auto">
                <button class="tab-btn active px-4 py-3 text-sm font-semibold border-b-2 border-cyan-500"
                    onclick="switchTab(event, 'biodata')">
                    <i data-lucide="user" class="w-4 h-4 inline mr-2"></i> Biodata
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent hover:border-slate-200"
                    onclick="switchTab(event, 'media')">
                    <i data-lucide="link" class="w-4 h-4 inline mr-2"></i> Media Sosial
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent hover:border-slate-200"
                    onclick="switchTab(event, 'achievement')">
                    <i data-lucide="trophy" class="w-4 h-4 inline mr-2"></i> Pencapaian
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent hover:border-slate-200"
                    onclick="switchTab(event, 'certification')">
                    <i data-lucide="award" class="w-4 h-4 inline mr-2"></i> Sertifikasi
                </button>
            </div>
        </div>

        <!-- TAB: BIODATA -->
        <div id="tab-biodata" class="tab-content">
            <div class="grid gap-3 sm:gap-4 md:grid-cols-2">
                <!-- FOTO PROFIL -->
                <div class="page-card p-3 sm:p-5 md:col-span-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="image" class="w-4 h-4 inline mr-2"></i>Foto Profil
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.uploadPhoto') }}" enctype="multipart/form-data"
                        class="space-y-3">
                        @csrf
                        <input type="file" name="photo" accept="image/*" class="form-control text-xs sm:text-sm" required>
                        <button type="submit" class="btn-primary text-xs sm:text-sm">
                            <i data-lucide="upload" class="w-4 h-4 inline mr-1"></i> Upload Foto
                        </button>
                    </form>
                </div>

                <!-- INFORMASI DASAR -->
                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="info" class="w-4 h-4 inline mr-2"></i>Informasi Dasar
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <p><span class="font-semibold text-slate-600">Nama:</span> {{ $guru->user->name }}</p>
                        <p><span class="font-semibold text-slate-600">Email:</span> {{ $guru->user->email }}</p>
                        <p><span class="font-semibold text-slate-600">NIP:</span> {{ $guru->nip ?? '-' }}</p>
                        <p><span class="font-semibold text-slate-600">Jabatan:</span> {{ $guru->jabatan->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- KONTAK & ALAMAT -->
                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="map-pin" class="w-4 h-4 inline mr-2"></i>Kontak & Alamat
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <p><span class="font-semibold text-slate-600">Telepon:</span> {{ $guru->phone ?? '-' }}</p>
                        <p><span class="font-semibold text-slate-600">Alamat:</span> {{ $guru->address ?? '-' }}</p>
                    </div>
                </div>

                <!-- FORM EDIT BIODATA -->
                <div class="page-card p-3 sm:p-5 md:col-span-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>Edit Biodata
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.updateBiodata') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="form-label text-xs sm:text-sm">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ $guru->phone }}" maxlength="15"
                                class="form-control text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="form-label text-xs sm:text-sm">Alamat</label>
                            <textarea name="address" rows="3" class="form-control text-xs sm:text-sm">{{ $guru->address }}</textarea>
                        </div>
                        <div>
                            <label class="form-label text-xs sm:text-sm">Biodata / Tentang Saya</label>
                            <textarea name="bio" rows="4" placeholder="Tuliskan biodata Anda di sini..." maxlength="1000"
                                class="form-control text-xs sm:text-sm">{{ $guru->bio }}</textarea>
                            <p class="text-xs text-slate-400 mt-1">Maksimal 1000 karakter</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-xs sm:text-sm">
                                <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Simpan Biodata
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB: MEDIA SOSIAL -->
        <div id="tab-media" class="tab-content hidden">
            <div class="page-card p-3 sm:p-5">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-4">
                    <i data-lucide="share-2" class="w-4 h-4 inline mr-2"></i>Media Sosial & Website
                </h3>
                <form method="POST" action="{{ route('guru.settings.updateBiodata') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label text-xs sm:text-sm flex items-center">
                            <i data-lucide="globe" class="w-4 h-4 mr-2"></i> Website
                        </label>
                        <input type="url" name="website" value="{{ $guru->website }}" placeholder="https://example.com"
                            class="form-control text-xs sm:text-sm">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7a4.5 4.5 0 00-3.14 1.53" />
                            </svg>
                            Twitter
                        </label>
                        <input type="url" name="social_media_twitter" value="{{ $guru->social_media_twitter }}"
                            placeholder="https://twitter.com/username" class="form-control text-xs sm:text-sm">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                                <path
                                    d="M16 11.37A4 4 0 1112.63 8M17.5 6.5h.01"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Instagram
                        </label>
                        <input type="url" name="social_media_instagram" value="{{ $guru->social_media_instagram }}"
                            placeholder="https://instagram.com/username" class="form-control text-xs sm:text-sm">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"
                                    fill="currentColor" />
                                <circle cx="4" cy="4" r="2" fill="currentColor" />
                            </svg>
                            LinkedIn
                        </label>
                        <input type="url" name="social_media_linkedin" value="{{ $guru->social_media_linkedin }}"
                            placeholder="https://linkedin.com/in/username" class="form-control text-xs sm:text-sm">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary text-xs sm:text-sm">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Simpan Media Sosial
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: PENCAPAIAN -->
        <div id="tab-achievement" class="tab-content hidden">
            <div class="space-y-4">
                <!-- LIST PENCAPAIAN -->
                @if ($guru->achievements && count($guru->achievements) > 0)
                    <div class="space-y-3">
                        @foreach ($guru->achievements as $achievement)
                            <div class="page-card p-3 sm:p-4 border-l-4 border-amber-500">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $achievement['title'] }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">Tahun: {{ $achievement['year'] }}</p>
                                        @if ($achievement['description'])
                                            <p class="text-xs sm:text-sm text-slate-600 mt-2">{{ $achievement['description'] }}</p>
                                        @endif
                                    </div>
                                    <form method="POST"
                                        action="{{ route('guru.settings.deleteAchievement', ['achievement_id' => $achievement['id']]) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="achievement_id" value="{{ $achievement['id'] }}">
                                        <button type="submit" class="btn-sm btn-danger text-xs"
                                            onclick="return confirm('Hapus pencapaian ini?')">
                                            <i data-lucide="trash" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500 text-sm">
                        Belum ada pencapaian. Tambahkan pencapaian Anda.
                    </div>
                @endif

                <!-- FORM TAMBAH PENCAPAIAN -->
                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>Tambah Pencapaian
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.addAchievement') }}" class="space-y-3">
                        @csrf
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-xs sm:text-sm">Judul Pencapaian</label>
                                <input type="text" name="title" required placeholder="Misal: Juara 1 Olimpiade Matematika"
                                    class="form-control text-xs sm:text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs sm:text-sm">Tahun</label>
                                <input type="number" name="year" required min="1900" max="{{ date('Y') }}"
                                    placeholder="{{ date('Y') }}" class="form-control text-xs sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-xs sm:text-sm">Deskripsi (opsional)</label>
                            <textarea name="description" rows="2" maxlength="500"
                                placeholder="Penjelasan singkat tentang pencapaian..." class="form-control text-xs sm:text-sm"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-xs sm:text-sm">
                                <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Tambah Pencapaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB: SERTIFIKASI -->
        <div id="tab-certification" class="tab-content hidden">
            <div class="space-y-4">
                <!-- LIST SERTIFIKASI -->
                @if ($guru->certifications && count($guru->certifications) > 0)
                    <div class="space-y-3">
                        @foreach ($guru->certifications as $cert)
                            <div class="page-card p-3 sm:p-4 border-l-4 border-emerald-500">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $cert['name'] }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">Penerbit: {{ $cert['issuer'] }}</p>
                                        <p class="text-xs text-slate-500">
                                            Tanggal: {{ \Carbon\Carbon::parse($cert['issued_date'])->format('d M Y') }}
                                            @if ($cert['expires_date'])
                                                s/d {{ \Carbon\Carbon::parse($cert['expires_date'])->format('d M Y') }}
                                            @endif
                                        </p>
                                        @if ($cert['credential_url'])
                                            <a href="{{ $cert['credential_url'] }}" target="_blank"
                                                class="text-xs text-cyan-600 hover:underline mt-2 inline-block">
                                                <i data-lucide="external-link" class="w-3 h-3 inline"></i> Lihat Kredensial
                                            </a>
                                        @endif
                                    </div>
                                    <form method="POST"
                                        action="{{ route('guru.settings.deleteCertification', ['certification_id' => $cert['id']]) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="certification_id" value="{{ $cert['id'] }}">
                                        <button type="submit" class="btn-sm btn-danger text-xs"
                                            onclick="return confirm('Hapus sertifikasi ini?')">
                                            <i data-lucide="trash" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500 text-sm">
                        Belum ada sertifikasi. Tambahkan sertifikasi Anda.
                    </div>
                @endif

                <!-- FORM TAMBAH SERTIFIKASI -->
                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-3">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>Tambah Sertifikasi
                    </h3>
                    <form method="POST" action="{{ route('guru.settings.addCertification') }}" class="space-y-3">
                        @csrf
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-xs sm:text-sm">Nama Sertifikasi</label>
                                <input type="text" name="name" required
                                    placeholder="Misal: Google Cloud Certified Associate"
                                    class="form-control text-xs sm:text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs sm:text-sm">Penerbit</label>
                                <input type="text" name="issuer" required placeholder="Misal: Google"
                                    class="form-control text-xs sm:text-sm">
                            </div>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="form-label text-xs sm:text-sm">Tanggal Terbit</label>
                                <input type="date" name="issued_date" required class="form-control text-xs sm:text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs sm:text-sm">Tanggal Kadaluarsa (opsional)</label>
                                <input type="date" name="expires_date" class="form-control text-xs sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-xs sm:text-sm">URL Kredensial (opsional)</label>
                            <input type="url" name="credential_url" placeholder="https://www.credly.com/..."
                                class="form-control text-xs sm:text-sm">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-xs sm:text-sm">
                                <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Tambah Sertifikasi
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
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-cyan-500');
                el.classList.add('border-transparent');
            });

            // Show selected tab
            document.getElementById(`tab-${tabName}`).classList.remove('hidden');

            // Add active to clicked button
            const button = event.currentTarget;
            button.classList.add('border-cyan-500');
            button.classList.remove('border-transparent');
        }

        // Handle theme change
        document.getElementById('themeSelect')?.addEventListener('change', function() {
            fetch('{{ route("guru.settings.updateTheme") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    theme: this.value
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Apply theme immediately
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
            } else {
                // auto
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
        }

        // Apply theme on load
        const theme = document.getElementById('themeSelect')?.value || 'light';
        applyTheme(theme);
    </script>
@endpush
