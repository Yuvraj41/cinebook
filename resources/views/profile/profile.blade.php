@extends('layouts.app')

@section('title', 'My Profile | CineBook')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                Account
            </p>

            <h1 class="mt-2 text-3xl font-black text-white">
                My Profile
            </h1>

            <p class="mt-2 text-slate-400">
                View your CineBook account details.
            </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 sm:p-8">
            <div class="mb-8 flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-600 text-2xl font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white">
                        {{ auth()->user()->name }}
                    </h2>

                    <p class="text-sm text-slate-400">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <div class="space-y-5">
                <div class="border-t border-white/10 pt-5">
                    <p class="text-sm text-slate-400">Full Name</p>
                    <p class="mt-1 font-semibold text-white">
                        {{ auth()->user()->name }}
                    </p>
                </div>

                <div class="border-t border-white/10 pt-5">
                    <p class="text-sm text-slate-400">Email Address</p>
                    <p class="mt-1 font-semibold text-white">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="border-t border-white/10 pt-5">
                    <p class="text-sm text-slate-400">Account Role</p>
                    <p class="mt-1 font-semibold capitalize text-white">
                        {{ auth()->user()->role }}
                    </p>
                </div>

                <div class="border-t border-white/10 pt-5">
                    <p class="text-sm text-slate-400">Member Since</p>
                    <p class="mt-1 font-semibold text-white">
                        {{ auth()->user()->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            
<div class="mt-8 flex flex-wrap gap-3">
    <a href="{{ route('profile.edit') }}"
       class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
        Edit Profile
    </a>

    <a href="{{ route('booking.index') }}"
       class="rounded-xl border border-white/10 px-5 py-3 font-semibold text-slate-200 transition hover:bg-white/5">
        My Bookings
    </a>

    <a href="{{ route('movies.index') }}"
       class="rounded-xl border border-white/10 px-5 py-3 font-semibold text-slate-200 transition hover:bg-white/5">
        Browse Movies
    </a>
</div>
        </div>
    </div>
@endsection
