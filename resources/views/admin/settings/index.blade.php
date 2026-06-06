@extends('layouts.app')

@section('title', 'Pengaturan Profil Admin')

@section('content')
    <div class="page-stack">
        <!-- PROFILE HEADER -->
        <div class="page-card p-3 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Email: {{ $user->email }}</p>
                        <p class="text-sm text-slate-500">Role: Administrator</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Preferensi Tema</p>
                        <select id="themeSelect" class="form-control text-sm mt-1">
                            <option value="light" @selected($user->theme_preference === 'light')>Light</option>
                            <option value="dark" @selected($user->theme_preference === 'dark')>Dark</option>
                            <option value="auto" @selected($user->theme_preference === 'auto')>Auto</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS NAVIGATION -->
        <div class="page-card p-0 border-b">
            <div class="flex border-b overflow-x-auto">
                <button class="tab-btn active px-4 py-3 text-sm font-semibold border-b-2 border-orange-500"
                    onclick="switchTab('profile')">
                    <i data-lucide="user" class="w-4 h-4 inline mr-2"></i> Profil
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent hover:border-slate-200"
                    onclick="switchTab('password')">
                    <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i> Keamanan
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent hover:border-slate-200"
                    onclick="switchTab('system')">
                    <i data-lucide="settings" class="w-4 h-4 inline mr-2"></i> Sistem
                </button>
            </div>
        </div>

        <!-- TAB: PROFIL -->
        <div id="tab-profile" class="tab-content">
            <div class="page-card p-3 sm:p-5">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-4">
                    <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>Edit Profil
                </h3>
                <form method="POST" action="{{ route('admin.settings.updateProfile') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label text-xs sm:text-sm">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" required maxlength="255"
                            class="form-control text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs sm:text-sm">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required maxlength="255"
                            class="form-control text-xs sm:text-sm">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary text-xs sm:text-sm">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: KEAMANAN -->
        <div id="tab-password" class="tab-content hidden">
            <div class="page-card p-3 sm:p-5">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-4">
                    <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i>Ubah Password
                </h3>
                <form method="POST" action="{{ route('admin.settings.updatePassword') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label text-xs sm:text-sm">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                            class="form-control text-xs sm:text-sm">
                        @error('current_password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label text-xs sm:text-sm">Password Baru</label>
                        <input type="password" name="password" required minlength="8"
                            class="form-control text-xs sm:text-sm"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label text-xs sm:text-sm">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="form-control text-xs sm:text-sm">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary text-xs sm:text-sm">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: SISTEM -->
        <div id="tab-system" class="tab-content hidden">
            <div class="grid gap-3 sm:gap-4 md:grid-cols-2">
                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm font-bold text-slate-900 mb-3">
                        <i data-lucide="info" class="w-4 h-4 inline mr-2"></i>Informasi Sistem
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <p><span class="font-semibold">Laravel Version:</span> {{ app()->version() }}</p>
                        <p><span class="font-semibold">PHP Version:</span> {{ phpversion() }}</p>
                        <p><span class="font-semibold">Database:</span> {{ config('database.default') }}</p>
                        <p><span class="font-semibold">Environment:</span> {{ config('app.env') }}</p>
                    </div>
                </div>

                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm font-bold text-slate-900 mb-3">
                        <i data-lucide="database" class="w-4 h-4 inline mr-2"></i>Database Stats
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <p>
                            <span class="font-semibold">Total Users:</span>
                            {{ \App\Models\User::count() }}
                        </p>
                        <p>
                            <span class="font-semibold">Total Guru:</span>
                            {{ \App\Models\Guru::count() }}
                        </p>
                        <p>
                            <span class="font-semibold">Total Evaluations:</span>
                            {{ \App\Models\Evaluation::count() }}
                        </p>
                        <p>
                            <span class="font-semibold">Total Evidence:</span>
                            {{ \App\Models\Evidence::count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="page-card p-3 sm:p-5 mt-4">
                <h3 class="text-sm font-bold text-slate-900 mb-3">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-2"></i>Aksi Berbahaya
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-3">
                    Tindakan berikut dapat mempengaruhi sistem. Gunakan dengan hati-hati.
                </p>
                <div class="space-y-2">
                    <button type="button"
                        onclick="if(confirm('Ini akan membersihkan cache aplikasi. Lanjutkan?')) { alert('Cache cleared!'); }"
                        class="btn-sm bg-amber-100 text-amber-900 hover:bg-amber-200 text-xs sm:text-sm w-full sm:w-auto">
                        <i data-lucide="trash-2" class="w-3 h-3 inline mr-1"></i> Bersihkan Cache
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-orange-500');
                el.classList.add('border-transparent');
            });

            document.getElementById(`tab-${tabName}`).classList.remove('hidden');
            event.target.closest('.tab-btn').classList.add('border-orange-500');
            event.target.closest('.tab-btn').classList.remove('border-transparent');
        }

        document.getElementById('themeSelect')?.addEventListener('change', function() {
            fetch('{{ route("admin.settings.updateTheme") }}', {
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
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
        }

        const theme = document.getElementById('themeSelect')?.value || 'light';
        applyTheme(theme);
    </script>
@endpush
