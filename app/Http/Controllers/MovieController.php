<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    // Display all movies
    public function index()
    {
        $movies = Cache::remember('movies.index',60,function(){
         return Movie::latest()->get();
        });

        return view('movies.index', compact('movies'));
    }

    // Show the add movie form
    public function create()
    {
        return view('movies.create');
    }

    // Save a new movie
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'language' => 'required|string|max:100',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'release_date' => 'nullable|date',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster_url'] = $request->file('poster')
                ->store('movies/posters', 'public');
        }

        unset($validated['poster']);

        Movie::create($validated);
        Cache::forget('movies.index');

        return redirect()->route('movies.index')
            ->with('success', 'Movie added successfully!');
    }

    
    // Show the edit movie form
    public function edit(Movie $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    // Update an existing movie
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'language' => 'required|string|max:100',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'release_date' => 'nullable|date',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster_url'] = $request->file('poster')
                ->store('movies/posters', 'public');
        }

        unset($validated['poster']);

        $movie->update($validated);
        Cache::forget('movies.index');

        return redirect()->route('movies.index')
            ->with('success', 'Movie updated successfully!');
    }


    public function destroy(Movie $movie)
    {
    if ($movie->poster_url) {
        Storage::disk('public')->delete($movie->poster_url);
    }

    $movie->delete();
    Cache::forget('movies.index');

    return redirect()->route('movies.index')
        ->with('success', 'Movie deleted successfully!');
    }
}
