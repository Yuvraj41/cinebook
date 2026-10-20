@extends('layouts.app')

@section('title', 'Admin Dashboard | CineBook')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                    Administration
                </p>

                <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                    Admin Dashboard
                </h1>

                <p class="mt-2 text-slate-400">
                    Manage CineBook and monitor booking activity.
                </p>
            </div>

            <a href="{{ route('movies.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
                + Add Movie
            </a>
        </div>

        {{-- Statistics --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Total Movies</p>
                <p class="mt-3 text-4xl font-black text-white">
                    {{ $totalMovies }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Total Shows</p>
                <p class="mt-3 text-4xl font-black text-white">
                    {{ $totalShows }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Total Bookings</p>
                <p class="mt-3 text-4xl font-black text-white">
                    {{ $totalBookings }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Confirmed Bookings</p>
                <p class="mt-3 text-4xl font-black text-green-400">
                    {{ $confirmedBookings }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Pending Payments</p>
                <p class="mt-3 text-4xl font-black text-amber-400">
                    {{ $pendingBookings }}
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Confirmed Booking Value</p>
                <p class="mt-3 text-4xl font-black text-red-400">
                    ₹{{ number_format($totalRevenue, 2) }}
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Before fees and any refunds
                </p>
            </div>

        </div>

        {{-- Quick Actions --}}
        <div>
            <h2 class="mb-4 text-2xl font-bold text-white">
                Quick Actions
            </h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <a href="{{ route('movies.create') }}"
                   class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                    <h3 class="font-bold text-white">Add a Movie →</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        Add a new movie to the CineBook catalogue.
                    </p>
                </a>

                <a href="{{ route('shows.create') }}"
                   class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                    <h3 class="font-bold text-white">Schedule a Show →</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        Create a show for a movie and screen.
                    </p>
                </a>

                <a href="{{ route('movies.index') }}"
                   class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                    <h3 class="font-bold text-white">Manage Movies →</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        Browse movies and access editing options.
                    </p>
                </a>

                <a href="{{ route('booking.index') }}"
                   class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                    <h3 class="font-bold text-white">My Bookings →</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        View your own bookings.
                    </p>
                </a>
               
                <a href="{{ route('admin.bookings.index') }}"
                  class="rounded-2xl border border-white/10 bg-slate-900 p-5 transition hover:border-red-500/50">
                   <h3 class="font-bold text-white">Manage Bookings →</h3>
                   <p class="mt-2 text-sm text-slate-400">
                       Search bookings, filter payment status, and review customer reservations.
                   </p>
               </a>
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div>
            <h2 class="mb-4 text-2xl font-bold text-white">
                Recent Bookings
            </h2>

            <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="border-b border-white/10 bg-white/5 text-slate-400">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Movie</th>
                            <th class="px-5 py-4 font-semibold">Showtime</th>
                            <th class="px-5 py-4 font-semibold">Amount</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/5">
                        @forelse ($recentBookings as $booking)
                            <tr class="text-slate-300">
                                <td class="px-5 py-4">
                                    {{ $booking->user->name ?? 'Unknown' }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-white">
                                    {{ $booking->show->movie->title ?? 'Movie unavailable' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $booking->show->start_time->format('d M Y, h:i A') }}
                                </td>

                                <td class="px-5 py-4">
                                    ₹{{ number_format($booking->total_price, 2) }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($booking->status === 'confirmed')
                                        <span class="rounded-full bg-green-500/10 px-3 py-1 text-xs font-semibold text-green-400">
                                            Confirmed
                                        </span>
                                    @elseif ($booking->status === 'pending')
                                        <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-400">
                                            Pending
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-700 px-3 py-1 text-xs font-semibold text-slate-300">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                    No bookings yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
