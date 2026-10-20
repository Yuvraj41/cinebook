@extends('layouts.app')

@section('title', 'Schedule a Show | CineBook')

@section('content')
<div class="mx-auto max-w-3xl">

    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
            Administration
        </p>
        <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
            Schedule a Movie Show
        </h1>
        <p class="mt-2 text-slate-400">
            Choose a movie, screen, showtime, and ticket price.
        </p>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 sm:p-8">
        <form action="{{ route('shows.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="movie_id" class="mb-2 block text-sm font-semibold text-slate-200">
                    Movie *
                </label>
                <select id="movie_id" name="movie_id" required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    <option value="">Select a movie</option>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->id }}"
                            @selected(old('movie_id') == $movie->id)>
                            {{ $movie->title }}
                        </option>
                    @endforeach
                </select>
                @error('movie_id')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="screen_id" class="mb-2 block text-sm font-semibold text-slate-200">
                    Screen *
                </label>
                <select id="screen_id" name="screen_id" required
                        class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    <option value="">Select a screen</option>
                    @foreach ($screens as $screen)
                        <option value="{{ $screen->id }}"
                            @selected(old('screen_id') == $screen->id)>
                            {{ $screen->name }} — {{ $screen->theatre->name }}
                        </option>
                    @endforeach
                </select>
                @error('screen_id')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="start_time" class="mb-2 block text-sm font-semibold text-slate-200">
                    Start Date and Time *
                </label>
                <input id="start_time" name="start_time" type="datetime-local"
                       value="{{ old('start_time') }}" required
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                @error('start_time')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ticket_price" class="mb-2 block text-sm font-semibold text-slate-200">
                    Ticket Price (₹) *
                </label>
                <input id="ticket_price" name="ticket_price" type="number"
                       value="{{ old('ticket_price') }}" min="1" step="0.01" required
                       placeholder="e.g. 250"
                       class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                @error('ticket_price')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3 border-t border-white/10 pt-6">
                <button type="submit"
                        class="rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500">
                    Schedule Show
                </button>
                <a href="{{ route('admin.shows.index') }}"
                   class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-200 transition hover:bg-white/5">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
