<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook | Edit Movie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        <a href="{{ route('movies.index') }}"
           class="text-sm text-slate-400 transition hover:text-red-400">
            &larr; Back to movies
        </a>

        <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-xl sm:p-8">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-red-400">
                    Admin panel
                </p>
                <h1 class="mt-2 text-3xl font-bold">Edit Movie</h1>
                <p class="mt-2 text-slate-400">
                    Update the details for {{ $movie->title }}.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
                    Please correct the errors below.
                </div>
            @endif

            <form method="POST"
                  action="{{ route('movies.update', $movie) }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-300">Movie Title</label>
                    <input id="title" type="text" name="title"
                           value="{{ old('title', $movie->title) }}" required
                           class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    @error('title') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="genre" class="mb-2 block text-sm font-medium text-slate-300">Genre</label>
                        <input id="genre" type="text" name="genre"
                               value="{{ old('genre', $movie->genre) }}" required
                               class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                        @error('genre') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="duration" class="mb-2 block text-sm font-medium text-slate-300">Duration (minutes)</label>
                        <input id="duration" type="number" name="duration"
                               value="{{ old('duration', $movie->duration) }}"
                               min="1" required
                               class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                        @error('duration') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="language" class="mb-2 block text-sm font-medium text-slate-300">Language</label>
                    <input id="language" type="text" name="language"
                           value="{{ old('language', $movie->language) }}" required
                           class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    @error('language') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-300">Description</label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full resize-y rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">{{ old('description', $movie->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-3 block text-sm font-medium text-slate-300">Current Poster</label>
                    @if ($movie->poster_url)
                        <img src="{{ asset('storage/' . $movie->poster_url) }}"
                             alt="{{ $movie->title }} poster"
                             class="mb-4 h-64 w-44 rounded-xl border border-slate-700 object-cover shadow-lg">
                    @else
                        <p class="mb-4 text-sm text-slate-500">No poster uploaded.</p>
                    @endif

                    <label for="poster" class="mb-2 block text-sm font-medium text-slate-300">Replace Poster (optional)</label>
                    <input id="poster" type="file" name="poster" accept="image/jpeg,image/png,image/webp"
                           class="block w-full cursor-pointer rounded-lg border border-slate-700 bg-slate-950 text-sm text-slate-300 file:mr-4 file:border-0 file:bg-slate-800 file:px-4 file:py-3 file:font-medium file:text-white hover:file:bg-slate-700">
                    <p class="mt-2 text-xs text-slate-500">JPG, PNG or WebP. Maximum size: 2 MB.</p>
                    @error('poster') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="release_date" class="mb-2 block text-sm font-medium text-slate-300">Release Date</label>
                    <input id="release_date" type="date" name="release_date"
                           value="{{ old('release_date', $movie->release_date ? \Illuminate\Support\Carbon::parse($movie->release_date)->format('Y-m-d') : '') }}"
                           class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    @error('release_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-800 pt-6 sm:flex-row">
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Update Movie
                    </button>
                    <a href="{{ route('movies.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-700 px-6 py-3 font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-500">
            CineBook Admin &bull; Movie Management
        </p>
    </main>
</body>
</html>
