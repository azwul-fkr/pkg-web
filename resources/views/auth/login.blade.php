<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SIMPEG MTs</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <main
        class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('{{ asset('images/login-school-bg.png') }}');"
    >
        <div class="absolute inset-0 bg-slate-950/55"></div>

        <div class="relative flex min-h-screen items-stretch">
            <section class="hidden flex-1 items-end p-10 lg:flex">
                <div class="max-w-xl pb-6 text-white">
                    <div class="mb-5 inline-flex items-center rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur">
                        Sistem Penilaian Kinerja Guru
                    </div>
                    <h1 class="text-4xl font-bold leading-tight">SIMPEG MTs</h1>
                    <p class="mt-4 text-lg leading-8 text-slate-100">
                        Portal terpadu untuk pengelolaan data guru, evidence, monitoring, dan penilaian kinerja madrasah.
                    </p>
                </div>
            </section>

            <section class="flex w-full items-center justify-center px-5 py-8 lg:w-[480px] lg:bg-slate-50/95 lg:px-10">
                <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-xl lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none">
                    <div class="mb-8">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-cyan-600 text-white shadow-sm">
                            <span class="text-xl font-bold">S</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Masuk ke akun</h2>
                        <p class="mt-2 text-sm text-slate-500">Gunakan email dan password yang sudah terdaftar.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                required
                                autofocus
                                autocomplete="username"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="form-label">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control"
                                required
                                autocomplete="current-password"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <label for="remember_me" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500"
                                >
                                Ingat saya
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-bold text-cyan-700 hover:text-cyan-900">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn-primary w-full py-3">
                            Login
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
