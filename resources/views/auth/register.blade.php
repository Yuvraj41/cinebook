@extends('layouts.app')

@section('title', 'Create Account | CineBook')

@section('content')

<div class="flex min-h-[65vh] items-center justify-center py-8">

    <div class="w-full max-w-md">

        {{-- Heading --}}
        <div class="mb-8 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/10 text-3xl">
                🎟️
            </div>

            <h1 class="mt-6 text-3xl font-black tracking-tight sm:text-4xl">
                Join CineBook<span class="text-red-500">.</span>
            </h1>

            <p class="mt-3 text-slate-400">
                Create your account and get ready for movie night.
            </p>
        </div>

        {{-- Registration form --}}
        <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 shadow-xl sm:p-8">

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
                    <p class="mb-2 font-semibold">Please correct the following:</p>

                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">
                        Full Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your name"
                        autocomplete="name"
                        required
                        autofocus
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-200">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >
                </div>

                {{-- Confirm password --}}
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-200">
                        Confirm Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Enter your password again"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-red-600 px-5 py-3.5 font-bold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-900"
                >
                    Create Account &rarr;
                </button>
            </form>

            {{-- Login link --}}
            <p class="mt-7 text-center text-sm text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}"
                   class="font-semibold text-red-400 transition hover:text-red-300">
                    Login
                </a>
            </p>

        </div>

        <p class="mt-6 text-center text-xs text-slate-500">
            Your next movie night starts here.
        </p>

    </div>

</div>

@endsection
