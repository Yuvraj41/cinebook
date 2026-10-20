<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CineBook | Edit Movie</title>
</head>
<body>
    <h1>Edit Movie</h1>

    <form method="POST"
          action="{{ route('movies.update', $movie) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Movie Title</label>
        <input type="text" name="title"
               value="{{ old('title', $movie->title) }}" required>
        @error('title') <p>{{ $message }}</p> @enderror

        <label>Genre</label>
        <input type="text" name="genre"
               value="{{ old('genre', $movie->genre) }}" required>
        @error('genre') <p>{{ $message }}</p> @enderror

        <label>Duration (minutes)</label>
        <input type="number" name="duration"
               value="{{ old('duration', $movie->duration) }}"
               min="1" required>
        @error('duration') <p>{{ $message }}</p> @enderror

        <label>Language</label>
        <input type="text" name="language"
               value="{{ old('language', $movie->language) }}" required>
        @error('language') <p>{{ $message }}</p> @enderror

        <label>Description</label>
        <textarea name="description" required>{{ old('description', $movie->description) }}</textarea>
        @error('description') <p>{{ $message }}</p> @enderror

        <label>Current Poster</label>
        @if ($movie->poster_url)
            <img src="{{ asset('storage/' . $movie->poster_url) }}"
                 alt="{{ $movie->title }} poster" width="150">
        @else
            <p>No poster uploaded.</p>
        @endif

        <label>Replace Poster (optional)</label>
        <input type="file" name="poster" accept="image/*">
        @error('poster') <p>{{ $message }}</p> @enderror

        <label>Release Date</label>
        <input type="date" name="release_date"
               value="{{ old('release_date', $movie->release_date) }}">
        @error('release_date') <p>{{ $message }}</p> @enderror

        <button type="submit">Update Movie</button>
    </form>

    <a href="{{ route('movies.index') }}">Back to movies</a>
</body>
</html>
