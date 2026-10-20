<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalBookings = Booking::where('user_id', $user->id)->count();

        $confirmedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $pendingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $upcomingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('show', function ($query) {
                $query->where('start_time', '>=', now());
            })
            ->with(['show.movie', 'show.screen.theatre', 'seats'])
            ->get();

        return view('dashboard', compact(
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'upcomingBookings'
        ));
    }
}
