@extends('layouts.app')

@section('title', 'Edit Show | CineBook')

@section('content')
    <div class="mx-auto max-w-2xl">

        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                Administration
            </p>

            <h1 class="mt-2 text-3xl font-black text-white">
                Edit Show
            </h1>

            <p class="mt-2 text-slate-400">
                Update the movie, screen, showtime, or ticket price.
            </p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 sm:p-8">

            <form action="{{ route('admin.shows.update', $show) }}"
                  method="POST"
                  class="space-y-6">

                @csrf
                @method('PUT')

                {{-- Movie --}}
                <div>
                    <label for="movie_id"
                           class="mb-2 block text-sm font-semibold text-slate-200">
                        Movie
                    </label>

                    <select id="movie_id"
                            name="movie_id"
                            required
                            class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-red-500 focus:outline-none">

                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}"
                                @selected(old('movie_id', $show->movie_id) == $movie->id)>
                                {{ $movie->title }}
                            </option>
                        @endforeach

                    </select>

                    @error('movie_id')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Screen --}}
                <div>
                    <label for="screen_id"
                           class="mb-2 block text-sm font-semibold text-slate-200">
                        Screen
                    </label>

                    <select id="screen_id"
                            name="screen_id"
                            required
                            class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-red-500 focus:outline-none">

                        @foreach ($screens as $screen)
                            <option value="{{ $screen->id }}"
                                @selected(old('screen_id', $show->screen_id) == $screen->id)>
                                {{ $screen->theatre->name }} - {{ $screen->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('screen_id')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Time --}}
                <div>
                    <label for="start_time"
                           class="mb-2 block text-sm font-semibold text-slate-200">
                        Show Date and Time
                    </label>

                    <input type="datetime-local"
                           id="start_time"
                           name="start_time"
                           value="{{ old('start_time', $show->start_time->format('Y-m-d\TH:i')) }}"
                           required
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-red-500 focus:outline-none">

                    @error('start_time')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ticket Price --}}
                <div>
                    <label for="ticket_price"
                           class="mb-2 block text-sm font-semibold text-slate-200">
                        Ticket Price (₹)
                    </label>

                    <input type="number"
                           id="ticket_price"
                           name="ticket_price"
                           value="{{ old('ticket_price', $show->ticket_price) }}"
                           min="1"
                           step="0.01"
                           required
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-red-500 focus:outline-none">

                    @error('ticket_price')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit"
                            class="rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500">
                        Save Changes
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
