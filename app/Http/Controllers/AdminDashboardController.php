<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Show;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalMovies = Movie::count();

        $totalShows = Show::count();

        $totalBookings = Booking::count();

        $confirmedBookings = Booking::where('status', 'confirmed')
            ->count();

        $pendingBookings = Booking::where('status', 'pending')
            ->count();

        $totalRevenue = Booking::where('status', 'confirmed')
            ->sum('total_price');

        $recentBookings = Booking::with([
            'user',
            'show.movie',
            'show.screen.theatre'
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMovies',
            'totalShows',
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'totalRevenue',
            'recentBookings'
        ));
    }
}

