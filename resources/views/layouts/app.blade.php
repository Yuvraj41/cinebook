<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'CineBook')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white antialiased">

    <nav class="border-b border-white/10 bg-slate-950/90">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            <a href="{{ route('home') }}"
               class="text-2xl font-black tracking-tight text-red-500">
                CineBook<span class="text-white">.</span>
            </a>

            <div class="flex items-center gap-5 text-sm font-medium">
                <a href="{{ route('movies.index') }}"
                   class="text-slate-300 transition hover:text-white">
                    Movies
                </a>

                @auth
                    <a href="{{ route('booking.index') }}"
                       class="text-slate-300 transition hover:text-white">
                        My Bookings
                    </a>

                    <a href="{{ route('profile') }}" class="text-slate-300 transition hover:text-white">Profile</a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-slate-300 transition hover:text-white">
                            Admin Dashboard
                        </a>

                        <a href="{{ route('movies.create') }}"
                           class="text-slate-300 transition hover:text-white">
                            Add Movie
                        </a>
                    @endif


                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-white transition hover:bg-red-500">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-slate-300 transition hover:text-white">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-red-600 px-4 py-2 text-white transition hover:bg-red-500">
                        Register
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    <main class="mx-auto min-h-[75vh] max-w-7xl px-6 py-10">

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="border-t border-white/10 px-6 py-6 text-center text-sm text-slate-500">
        © {{ date('Y') }} CineBook. Your movie night starts here.
    </footer>

</body>
</html>