<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\Show;

class ShowController extends Controller
{
    public function create()
    {
        $movies = Movie::all();
        $screens = Screen::with('theatre')->get();

        return view('shows.create', compact('movies', 'screens'));
    }
    
public function store(Request $request)
{
    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,id',
        'screen_id' => 'required|exists:screens,id',
        'start_time' => 'required|date|after:now',
        'ticket_price' => 'required|numeric|min:1',
    ]);

    Show::create($validated);

    return redirect()->route('shows.create')
        ->with('success', 'Show scheduled successfully!');
}

public function index(\App\Models\Movie $movie)
{
    $shows = $movie->shows()
        ->with('screen.theatre')
        ->orderBy('start_time')
        ->get();

    return view('shows.index', compact('movie', 'shows'));
}

public function edit(Show $show)
{
    $movies = Movie::all();
    $screens = Screen::with('theatre')->get();

    return view('admin.shows.edit', compact(
        'show',
        'movies',
        'screens'
    ));
}

public function update(Request $request, Show $show)
{
    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,id',
        'screen_id' => 'required|exists:screens,id',
        'start_time' => 'required|date|after:now',
        'ticket_price' => 'required|numeric|min:1',
    ]);

    $show->update($validated);

    return redirect()
        ->route('admin.shows.index')
        ->with('success', 'Show updated successfully!');
}

public function destroy(Show $show)
{
    if ($show->bookings()->exists()) {
        return redirect()
            ->route('admin.shows.index')
            ->with('error', 'Cannot delete a show that has bookings.');
    }

    $show->delete();

    return redirect()
        ->route('admin.shows.index')
        ->with('success', 'Show deleted successfully!');
}

}
