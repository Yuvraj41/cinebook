@extends('layouts.app')

@section('title', 'Edit Profile | CineBook')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                Account Settings
            </p>

            <h1 class="mt-2 text-3xl font-black text-white">
                Edit Profile
            </h1>

            <p class="mt-2 text-slate-400">
                Update your personal information.
            </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 sm:p-8">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >

                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500"
                    >
                        Save Changes
                    </button>

                    <a
                        href="{{ route('profile') }}"
                        class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-200 transition hover:bg-white/5"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
