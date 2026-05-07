<!DOCTYPE html>
<html>
<head>
    <title>SPK Guru</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <nav>
        <a href="/">Home</a>

        @auth
            <span> | {{ auth()->user()->name }}</span>

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </nav>

    <hr>

    <div>
        @yield('content')
    </div>

</body>
</html>