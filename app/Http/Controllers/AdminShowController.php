<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;

class AdminShowController extends Controller
{
    public function index(Request $request)
    {
        $query = Show::with([
            'movie',
            'screen.theatre',
        ])->orderBy('start_time');

        // Search by movie title
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('movie', function ($movieQuery) use ($search) {
                $movieQuery->where('title', 'like', "%{$search}%");
            });
        }

        // Filter upcoming or past shows
        if ($request->filled('timing')) {
            if ($request->timing === 'upcoming') {
                $query->where('start_time', '>=', now());
            } elseif ($request->timing === 'past') {
                $query->where('start_time', '<', now());
            }
        }

        $shows = $query->paginate(10)->withQueryString();

        return view('admin.shows.index', compact('shows'));
    }
}

