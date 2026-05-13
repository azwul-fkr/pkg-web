@php
    $user = auth()->user();
    $roleName = strtolower(optional(optional($user)->role)->name ?? '');
    $roleLabel = strtoupper($roleName ?: 'USER');

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

    <title>@yield('title', 'SIMPEG MTs')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
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
            border-radius: .5rem;
            padding: .65rem .75rem;
            color: rgb(203 213 225);
            font-size: .875rem;
            font-weight: 600;
            transition: background-color .18s ease, color .18s ease, box-shadow .18s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
        }

        .sidebar-link.active {
            background: #0891b2;
            color: #fff;
            box-shadow: 0 10px 22px rgba(8, 145, 178, .22);
        }

        .sidebar-link i {
            width: 1.1rem;
            height: 1.1rem;
            flex: none;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800 bg-slate-950 text-white transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-20 items-center gap-3 border-b border-white/10 px-5">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-500 text-white shadow-sm">
                    <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="truncate text-lg font-bold leading-tight">SIMPEG MTs</h1>
                    <p class="text-sm text-slate-400">PKG Madrasah</p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-5">
                <p class="mb-3 px-2 text-[11px] font-bold uppercase tracking-[.18em] text-slate-500">
                    Menu Utama
                </p>

                <div class="space-y-1">
                    @foreach ($navigation as $item)
                        @if (\Illuminate\Support\Facades\Route::has($item['route']))
                            <a href="{{ route($item['route']) }}"
                                class="sidebar-link {{ request()->routeIs($item['active']) ? 'active' : '' }}"
                                @click="sidebarOpen = false">
                                <i data-lucide="{{ $item['icon'] }}"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="mb-3 flex items-center gap-3 rounded-lg bg-white/[.06] p-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-500 text-sm font-bold text-white">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold">{{ $user->name ?? 'User' }}</p>
                        <p class="mt-0.5 text-xs font-semibold tracking-wide text-slate-400">{{ $roleLabel }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-400/20 bg-red-500/10 px-3 py-2.5 text-sm font-bold text-red-100 transition hover:bg-red-500 hover:text-white">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 lg:hidden"
                            @click="sidebarOpen = true">
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        <div class="min-w-0">
                            <h2 class="truncate text-xl font-bold text-slate-900 sm:text-2xl">
                                @yield('title', 'Dashboard')
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

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
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
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const renderIcons = () => {
            lucide.createIcons();
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
