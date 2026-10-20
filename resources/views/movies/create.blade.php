@extends('layouts.app')

@section('title', 'Add Movie | CineBook')

@section('content')
<div class="mx-auto max-w-3xl">

    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
            Administration
        </p>
        <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
            Add a Movie
        </h1>
        <p class="mt-2 text-slate-400">
            Add a new movie to the CineBook catalogue.
        </p>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-900 p-6 sm:p-8">
        <form action="{{ route('movies.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">

                <div>
                    <label for="title" class="mb-2 block text-sm font-semibold text-slate-200">
                        Movie Title *
                    </label>
                    <input id="title" name="title" type="text"
                           value="{{ old('title') }}" required
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    @error('title')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="genre" class="mb-2 block text-sm font-semibold text-slate-200">
                        Genre *
                    </label>
                    <input id="genre" name="genre" type="text"
                           value="{{ old('genre') }}" required
                           placeholder="e.g. Action, Comedy"
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    @error('genre')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="mb-2 block text-sm font-semibold text-slate-200">
                        Duration (minutes) *
                    </label>
                    <input id="duration" name="duration" type="number"
                           value="{{ old('duration') }}" min="1" required
                           placeholder="e.g. 120"
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    @error('duration')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="language" class="mb-2 block text-sm font-semibold text-slate-200">
                        Language *
                    </label>
                    <input id="language" name="language" type="text"
                           value="{{ old('language') }}" required
                           placeholder="e.g. English"
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    @error('language')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-200">
                        Description *
                    </label>
                    <textarea id="description" name="description" rows="4" required
                              placeholder="Write a short description of the movie..."
                              class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="poster" class="mb-2 block text-sm font-semibold text-slate-200">
                        Movie Poster
                    </label>
                    <input id="poster" name="poster" type="file"
                           accept="image/*"
                           class="w-full rounded-xl border border-white/10 bg-slate-950 p-3 text-sm text-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-red-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-red-500">
                    @error('poster')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="release_date" class="mb-2 block text-sm font-semibold text-slate-200">
                        Release Date <span class="text-slate-500">(optional)</span>
                    </label>
                    <input id="release_date" name="release_date" type="date"
                           value="{{ old('release_date') }}"
                           class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500">
                    @error('release_date')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex flex-wrap gap-3 border-t border-white/10 pt-6">
                <button type="submit"
                        class="rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500">
                    Save Movie
                </button>
                <a href="{{ route('movies.index') }}"
                   class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-200 transition hover:bg-white/5">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
