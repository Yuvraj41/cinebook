@extends('layouts.app')

@section('title', $movie->title . ' | Showtimes | CineBook')

@section('content')

    {{-- Back navigation --}}
    <a href="{{ route('movies.index') }}"
       class="mb-8 inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-white">
        <span>&larr;</span> Back to Movies
    </a>

    {{-- Movie heading --}}
    <section class="mb-10">
        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.3em] text-red-500">
            Find your show
        </p>

        <h1 class="text-4xl font-black tracking-tight sm:text-5xl">
            {{ $movie->title }}<span class="text-red-500">.</span>
        </h1>

        <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-300">
            <span class="rounded-full bg-white/5 px-4 py-2">
                {{ $movie->genre }}
            </span>

            <span class="rounded-full bg-white/5 px-4 py-2">
                {{ $movie->duration }} minutes
            </span>

            <span class="rounded-full bg-white/5 px-4 py-2">
                {{ $movie->language }}
            </span>
        </div>

        @if ($movie->description)
            <p class="mt-5 max-w-3xl leading-7 text-slate-400">
                {{ $movie->description }}
            </p>
        @endif
    </section>

    {{-- Available shows --}}
    <section>
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Available Shows</h2>
            <p class="mt-2 text-sm text-slate-400">
                Choose a theatre and showtime to select your seats.
            </p>
        </div>

        @forelse ($shows as $show)

            <article class="mb-5 rounded-2xl border border-white/10 bg-slate-900 p-5 sm:p-6">

                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                    {{-- Theatre details --}}
                    <div>
                        <h3 class="text-xl font-bold">
                            {{ $show->screen->theatre->name }}
                        </h3>

                        <p class="mt-2 text-sm text-slate-400">
                            {{ $show->screen->theatre->location }}
                        </p>

                        <p class="mt-1 text-sm text-slate-400">
                            Screen: {{ $show->screen->name }}
                        </p>

                        <p class="mt-3 font-medium text-white">
                            {{ $show->start_time->format('D, d M Y') }}
                            <span class="mx-1 text-slate-600">|</span>
                            {{ $show->start_time->format('h:i A') }}
                        </p>
                    </div>

                    {{-- Price and booking --}}
                    <div class="flex flex-col gap-3 sm:items-end">
                        <p class="text-2xl font-black text-red-400">
                            ₹{{ number_format($show->ticket_price, 2) }}
                            <span class="text-sm font-normal text-slate-400">/ ticket</span>
                        </p>

                        <a href="{{ route('booking.seats', $show) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 font-semibold transition hover:bg-red-500">
                            Select Seats
                            <span class="ml-2">&rarr;</span>
                        </a>
                    </div>

                </div>
            </article>

        @empty

            <div class="rounded-2xl border border-dashed border-white/10 px-6 py-16 text-center">
                <p class="text-5xl">🎟️</p>

                <h3 class="mt-4 text-xl font-bold">
                    No shows available
                </h3>

                <p class="mt-2 text-slate-400">
                    There are currently no shows scheduled for this movie.
                </p>

                <a href="{{ route('movies.index') }}"
                   class="mt-6 inline-flex rounded-xl border border-white/10 px-5 py-3 font-semibold transition hover:bg-white/5">
                    Browse Other Movies
                </a>
            </div>

        @endforelse
    </section>

@endsection
