@extends('layouts.app')

@section('title', 'Explore Movies | CineBook')

@section('content')

    {{-- Page heading --}}
    <section class="mb-10">
        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.3em] text-red-500">
            Your next movie night
        </p>

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl">
                    Explore Movies<span class="text-red-500"></span>
                </h1>

                <p class="mt-3 text-slate-400">
                    Discover your next favourite movie.
                </p>
            </div>

            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('movies.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 font-semibold transition hover:bg-red-500">
                    + Add a Movie
                </a>
            @endif
        </div>
    </section>

    {{-- Movie cards --}}
    <section class="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @forelse ($movies as $movie)

            <article class="group overflow-hidden rounded-2xl border border-white/10 bg-slate-900 transition duration-300 hover:-translate-y-1 hover:border-red-500/50">

                {{-- Poster --}}
                <a href="{{ route('shows.index', $movie) }}"
                   class="block overflow-hidden bg-slate-800">

                    @if ($movie->poster_url)
                        <img
                            src="{{ asset('storage/' . $movie->poster_url) }}"
                            alt="{{ $movie->title }} poster"
                            class="h-80 w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                    @else
                        <div class="flex h-80 items-center justify-center text-slate-500">
                            <span class="text-6xl">🎬</span>
                        </div>
                    @endif

                </a>

                {{-- Movie information --}}
                <div class="p-5">

                    <h2 class="text-xl font-bold leading-snug">
                        <a href="{{ route('shows.index', $movie) }}"
                           class="transition hover:text-red-400">
                            {{ $movie->title }}
                        </a>
                    </h2>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span class="rounded-full bg-red-500/10 px-3 py-1 text-red-300">
                            {{ $movie->genre }}
                        </span>

                        <span class="rounded-full bg-white/5 px-3 py-1 text-slate-300">
                            {{ $movie->duration }} min
                        </span>
                    </div>

                    <p class="mt-3 text-sm text-slate-400">
                        Language: {{ $movie->language }}
                    </p>

                    @if ($movie->description)
                        <p class="mt-3 text-sm leading-6 text-slate-400">
                            {{ \Illuminate\Support\Str::limit($movie->description, 100) }}
                        </p>
                    @endif

                    <a href="{{ route('shows.index', $movie) }}"
                       class="mt-5 block rounded-xl bg-red-600 px-4 py-3 text-center font-semibold transition hover:bg-red-500">
                        View Shows
                    </a>

                    {{-- Admin actions --}}
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <div class="mt-3 flex gap-3">
                            <a href="{{ route('movies.edit', $movie) }}"
                               class="flex-1 rounded-lg border border-white/10 px-3 py-2 text-center text-sm text-slate-300 transition hover:bg-white/5">
                                Edit
                            </a>

                            <form action="{{ route('movies.destroy', $movie) }}"
                                  method="POST"
                                  class="flex-1"
                                  onsubmit="return confirm('Are you sure you want to delete this movie?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-full rounded-lg border border-red-500/30 px-3 py-2 text-sm text-red-400 transition hover:bg-red-500/10">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </article>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-white/10 py-20 text-center">
                <p class="text-5xl">🎬</p>
                <h2 class="mt-4 text-xl font-bold">No movies yet</h2>
                <p class="mt-2 text-slate-400">
                    New movies will appear here when they're added.
                </p>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('movies.create') }}"
                       class="mt-5 inline-block rounded-xl bg-red-600 px-5 py-3 font-semibold hover:bg-red-500">
                        Add Your First Movie
                    </a>
                @endif
            </div>

        @endforelse

    </section>
    @include('chatbot.widget')

@endsection
