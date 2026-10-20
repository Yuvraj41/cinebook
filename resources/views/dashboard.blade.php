@extends('layouts.app')

@section('title', 'Dashboard | CineBook')

@section('content')
    <div class="space-y-8">

        {{-- Welcome Section --}}
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                Your Account
            </p>

            <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                Welcome back, {{ auth()->user()->name }}!
            </h1>

            <p class="mt-2 text-slate-400">
                Here's what's happening with your movie bookings.
            </p>
        </div>

        {{-- Booking Statistics --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Total Bookings</p>
                <p class="mt-3 text-4xl font-black text-white">
                    {{ $totalBookings }}
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    All your bookings
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Confirmed Bookings</p>
                <p class="mt-3 text-4xl font-black text-green-400">
                    {{ $confirmedBookings }}
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    Successfully paid
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Pending Bookings</p>
                <p class="mt-3 text-4xl font-black text-amber-400">
                    {{ $pendingBookings }}
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    Awaiting payment
                </p>
            </div>

        </div>

        {{-- Upcoming Bookings --}}
        <div>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        Upcoming Movies
                    </h2>
                    <p class="mt-1 text-sm text-slate-400">
                        Your confirmed movie shows.
                    </p>
                </div>

                <a href="{{ route('booking.index') }}"
                   class="text-sm font-semibold text-red-400 transition hover:text-red-300">
                    View All Bookings →
                </a>
            </div>

            @forelse ($upcomingBookings as $booking)
                <div class="mb-4 rounded-2xl border border-white/10 bg-slate-900 p-5 sm:p-6">
                    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                        <div>
                            <h3 class="text-xl font-bold text-white">
                                {{ $booking->show->movie->title }}
                            </h3>

                            <p class="mt-2 text-sm text-slate-400">
                                {{ $booking->show->screen->theatre->name }}
                                · {{ $booking->show->screen->name }}
                            </p>

                            <p class="mt-2 text-sm text-slate-300">
                                {{ $booking->show->start_time->format('d M Y, h:i A') }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($booking->seats as $seat)
                                    <span class="rounded-md bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-200">
                                        {{ $seat->seat_number }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="sm:text-right">
                            <p class="text-sm text-slate-400">Amount Paid</p>
                            <p class="mt-1 text-2xl font-bold text-green-400">
                                ₹{{ number_format($booking->total_price, 2) }}
                            </p>

                            <span class="mt-3 inline-block rounded-full bg-green-500/10 px-3 py-1 text-xs font-semibold text-green-400">
                                Confirmed
                            </span>
                        </div>

                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-slate-900/50 px-6 py-12 text-center">
                    <h3 class="text-lg font-bold text-white">
                        No upcoming movies
                    </h3>

                    <p class="mt-2 text-sm text-slate-400">
                        Book your next movie and it will appear here.
                    </p>

                    <a href="{{ route('movies.index') }}"
                       class="mt-5 inline-block rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
                        Explore Movies
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Quick Links --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('profile') }}"
               class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                <h3 class="font-bold text-white">My Profile →</h3>
                <p class="mt-2 text-sm text-slate-400">
                    View or update your account details.
                </p>
            </a>

            <a href="{{ route('movies.index') }}"
               class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                <h3 class="font-bold text-white">Browse Movies →</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Find your next movie experience.
                </p>
            </a>
        </div>

    </div>
@endsection
