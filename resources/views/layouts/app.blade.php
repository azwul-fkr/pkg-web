@php
    $user = auth()->user();
    $roleName = strtolower(optional(optional($user)->role)->name ?? '');
    $roleLabel = strtoupper($roleName ?: 'USER');
    $pageTitle = trim($__env->yieldContent('title'));

    if ($pageTitle === '' && isset($header)) {
        $pageTitle = trim(strip_tags((string) $header));
    }

    if ($pageTitle === '') {
        $pageTitle = 'Dashboard';
    }

    $menus = [
        'admin' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'icon' => 'layout-dashboard',
            ],
            ['label' => 'Data User', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'user-cog'],
            ['label' => 'Data Guru', 'route' => 'admin.gurus.index', 'active' => 'admin.gurus.*', 'icon' => 'users'],
            [
                'label' => 'Jabatan',
                'route' => 'admin.jabatans.index',
                'active' => 'admin.jabatans.*',
                'icon' => 'briefcase-business',
            ],
            [
                'label' => 'Kriteria',
                'route' => 'admin.kriterias.index',
                'active' => 'admin.kriterias.*',
                'icon' => 'clipboard-list',
            ],
            [
                'label' => 'Kompetensi',
                'route' => 'admin.sub-kriterias.index',
                'active' => 'admin.sub-kriterias.*',
                'icon' => 'layers-3',
            ],
            [
                'label' => 'Indikator',
                'route' => 'admin.indikators.index',
                'active' => 'admin.indikators.*',
                'icon' => 'list-checks',
            ],
            [
                'label' => 'Rubrik Score',
                'route' => 'admin.indikator-scores.index',
                'active' => 'admin.indikator-scores.*',
                'icon' => 'star',
            ],
            [
                'label' => 'Penugasan',
                'route' => 'admin.assignments.index',
                'active' => 'admin.assignments.*',
                'icon' => 'user-check',
            ],
            [
                'label' => 'Validasi Evidence',
                'route' => 'admin.evidences.index',
                'active' => 'admin.evidences.*',
                'icon' => 'file-check-2',
            ],
            [
                'label' => 'Monitoring',
                'route' => 'admin.monitoring.index',
                'active' => 'admin.monitoring.*',
                'icon' => 'monitor',
            ],
            [
                'label' => 'Periode',
                'route' => 'admin.periods.index',
                'active' => 'admin.periods.*',
                'icon' => 'calendar-range',
            ],
        ],
        'guru' => [
            [
                'label' => 'Dashboard',
                'route' => 'guru.dashboard',
                'active' => 'guru.dashboard',
                'icon' => 'layout-dashboard',
            ],
            [
                'label' => 'Evidence',
                'route' => 'guru.evidence.index',
                'active' => 'guru.evidence.*',
                'icon' => 'folder-check',
            ],
            [
                'label' => 'Self Assessment',
                'route' => 'guru.self-assessment.index',
                'active' => 'guru.self-assessment.*',
                'icon' => 'clipboard-check',
            ],
        ],
        'penilai' => [
            [
                'label' => 'Dashboard',
                'route' => 'penilai.dashboard',
                'active' => 'penilai.dashboard',
                'icon' => 'layout-dashboard',
            ],
            ['label' => 'Data Guru', 'route' => 'penilai.guru.index', 'active' => 'penilai.guru.*', 'icon' => 'users'],
            [
                'label' => 'Hasil Penilaian',
                'route' => 'penilai.hasil',
                'active' => 'penilai.hasil*',
                'icon' => 'bar-chart-3',
            ],
        ],
    ];

    $navigation = $menus[$roleName] ?? [];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }} - SIMPEG MTSs</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: .875rem;
            padding: .7rem .8rem;
            color: rgba(226, 232, 240, .88);
            font-size: .875rem;
            font-weight: 600;
            transition: background-color .18s ease, color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .09);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(96, 165, 250, 1), rgba(14, 165, 233, .95));
            color: #fff;
            box-shadow: 0 12px 24px rgba(37, 99, 235, .24);
        }

        .sidebar-link i {
            width: 1.1rem;
            height: 1.1rem;
            flex: none;
        }

        /* Dark Mode Support */
        :root {
            color-scheme: light;
        }

        :root.dark {
            color-scheme: dark;
        }

        :root.dark body {
            background: #020617;
            color: #e2e8f0;
        }

        :root.dark .page-card {
            background: rgba(15, 23, 42, .92);
            border-color: rgba(51, 65, 85, .85);
            box-shadow: 0 10px 30px rgba(2, 6, 23, .35);
        }

        :root.dark .page-card-header {
            border-color: rgba(51, 65, 85, .85);
        }

        :root.dark .form-control {
            background: rgba(15, 23, 42, .95);
            border-color: rgba(51, 65, 85, .9);
            color: #e2e8f0;
        }

        :root.dark .form-control:focus {
            background: rgba(15, 23, 42, .98);
            border-color: #06b6d4;
        }

        :root.dark .form-label {
            color: #cbd5e1;
        }

        :root.dark .tab-btn {
            color: #cbd5e1;
        }

        :root.dark header {
            background: rgba(15, 23, 42, .88);
            border-color: rgba(51, 65, 85, .85);
        }

        :root.dark .badge {
            color: #e2e8f0;
        }

        :root.dark .app-table thead {
            background: rgba(15, 23, 42, .96);
        }

        :root.dark .app-table th,
        :root.dark .app-table td {
            border-color: rgba(51, 65, 85, .8);
        }

        :root.dark .empty-state {
            color: #94a3b8;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -right-24 top-24 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute -left-24 bottom-24 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
    </div>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-blue-900/70 bg-gradient-to-b from-blue-950 via-blue-900 to-cyan-900 text-white shadow-2xl shadow-blue-950/30 backdrop-blur-xl transition-transform duration-200 lg:sticky lg:top-4 lg:m-4 lg:h-[calc(100vh-2rem)] lg:translate-x-0 lg:rounded-3xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-20 items-center gap-3 border-b border-white/10 px-5">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15 text-white shadow-sm ring-1 ring-white/15">
                    <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="truncate text-lg font-bold leading-tight">SIMPEG APP</h1>
                    <p class="text-sm text-slate-200/80">MTS JIDRIS AS-SALAM</p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-5">
                <p class="mb-3 px-2 text-[11px] font-bold uppercase tracking-[.18em] text-slate-300/70">
                    Menu Utama
                </p>

                <div class="space-y-1">
                    @foreach ($navigation as $item)
                        @if (\Illuminate\Support\Facades\Route::has($item['route']))
                            @php
                                $isActive = request()->routeIs($item['active']);
                            @endphp
                            <a href="{{ route($item['route']) }}"
                                class="sidebar-link {{ $isActive ? 'active' : '' }}"
                                @if ($isActive) aria-current="page" @endif
                                @click="{{ $isActive ? '$event.preventDefault()' : 'sidebarOpen = false' }}">
                                <i data-lucide="{{ $item['icon'] }}"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </nav>

            <div class="border-t border-white/10 p-4 space-y-3">
                <div class="mb-3 flex items-center gap-3 rounded-2xl bg-white/[.08] p-3 ring-1 ring-white/10">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-sm font-bold text-white ring-1 ring-white/10">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold">{{ $user->name ?? 'User' }}</p>
                        <p class="mt-0.5 text-xs font-semibold tracking-wide text-slate-200/70">{{ $roleLabel }}</p>
                    </div>
                </div>

                <!-- SETTINGS BUTTON -->
                @if ($roleName === 'guru')
                    <a href="{{ route('guru.settings.index') }}"
                        class="sidebar-link {{ request()->routeIs('guru.settings.*') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span class="truncate">Pengaturan</span>
                    </a>
                @elseif ($roleName === 'penilai')
                    <a href="{{ route('penilai.settings.index') }}"
                        class="sidebar-link {{ request()->routeIs('penilai.settings.*') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span class="truncate">Pengaturan</span>
                    </a>
                @elseif ($roleName === 'admin')
                    <a href="{{ route('admin.settings.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i data-lucide="settings"></i>
                        <span class="truncate">Pengaturan</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="relative flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/85 backdrop-blur-xl">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 lg:hidden"
                            @click="sidebarOpen = true">
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        <div class="min-w-0">
                            <h2 class="truncate text-xl font-bold text-slate-900 sm:text-2xl">
                                {{ $pageTitle }}
                            </h2>
                            <p class="mt-1 hidden text-sm text-slate-500 sm:block">
                                Sistem Penilaian Kinerja Guru
                            </p>
                        </div>
                    </div>

                    <div
                        class="hidden items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 shadow-sm sm:flex">
                        <i data-lucide="shield-check" class="h-4 w-4 text-cyan-600"></i>
                        {{ $roleLabel }}
                    </div>
                </div>
            </header>

            <main class="mx-auto flex-1 w-full max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
                {{-- SUCCESS ALERT --}}
                @if (session('success'))
                    <div id="success-alert"
                        class="
            mb-6
            rounded-2xl
            border
            border-emerald-200
            bg-emerald-50
            px-5
            py-4
            text-emerald-700
            shadow-sm
            transition
        ">

                        <div class="
            flex
            items-center
            gap-3
        ">

                            <i data-lucide="check-circle" class="w-5 h-5"></i>

                            <span class="font-medium">

                                {{ session('success') }}

                            </span>

                        </div>

                    </div>
                @endif

                {{-- ERROR ALERT --}}
                @if ($errors->any())

                    <div
                        class="
        mb-6
        rounded-2xl
        border
        border-red-200
        bg-red-50
        px-5
        py-4
        text-red-700
        shadow-sm
    ">

                        <ul class="space-y-1 list-disc ml-5">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif

                {{-- PAGE CONTENT --}}
                @hasSection('content')
                    @yield('content')
                @elseif (isset($slot))
                    {{ $slot }}
                @endif
            </main>
        </div>
    </div>

    <script>
        // Apply theme preference on load
        (function() {
            const theme = '{{ auth()->user()->theme_preference ?? 'light' }}';
            const html = document.documentElement;

            function applyTheme(t) {
                if (t === 'dark') {
                    html.classList.add('dark');
                } else if (t === 'light') {
                    html.classList.remove('dark');
                } else if (t === 'auto') {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }
            }

            applyTheme(theme);

            // Watch for system theme changes when auto
            if (theme === 'auto') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (e.matches) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                });
            }
        })();

        const renderIcons = () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderIcons);
        } else {
            renderIcons();
        }
    </script>
    @stack('scripts')
</body>

</html>
