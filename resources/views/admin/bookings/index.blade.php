@extends('layouts.app')

@section('title', 'Manage Bookings | CineBook')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                Administration
            </p>

            <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                Manage Bookings
            </h1>

            <p class="mt-2 text-slate-400">
                Search and review bookings from all CineBook users.
            </p>
        </div>

        {{-- Search and Filter --}}
        <form action="{{ route('admin.bookings.index') }}" method="GET"
              class="grid gap-4 rounded-2xl border border-white/10 bg-slate-900 p-5 sm:grid-cols-3">

            <div class="sm:col-span-2">
                <label for="search" class="mb-2 block text-sm font-semibold text-slate-300">
                    Search Customer
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Enter customer name or email"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-red-500"
                >
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-slate-300">
                    Booking Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500"
                >
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3 sm:col-span-3">
                <button
                    type="submit"
                    class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500"
                >
                    Search Bookings
                </button>

                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="rounded-xl border border-white/10 px-5 py-3 font-semibold text-slate-200 transition hover:bg-white/5"
                >
                    Reset
                </a>
            </div>
        </form>

        {{-- Bookings Table --}}
        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900">
            <table class="w-full min-w-[950px] text-left text-sm">
                <thead class="border-b border-white/10 bg-white/5 text-slate-400">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Booking ID</th>
                        <th class="px-5 py-4 font-semibold">Customer</th>
                        <th class="px-5 py-4 font-semibold">Movie</th>
                        <th class="px-5 py-4 font-semibold">Showtime</th>
                        <th class="px-5 py-4 font-semibold">Seats</th>
                        <th class="px-5 py-4 font-semibold">Amount</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/5">
                    @forelse ($bookings as $booking)
                        <tr class="text-slate-300">
                            <td class="px-5 py-4 font-semibold text-white">
                                #{{ $booking->id }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-semibold text-white">
                                    {{ $booking->user->name ?? 'Unknown' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $booking->user->email ?? '' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 font-semibold text-white">
                                {{ $booking->show->movie->title ?? 'Unavailable' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $booking->show->start_time->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($booking->seats as $seat)
                                        <span class="rounded-md bg-slate-800 px-2 py-1 text-xs">
                                            {{ $seat->seat_number }}
                                        </span>
                                    @empty
                                        <span class="text-slate-500">No seats</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-5 py-4 font-semibold">
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
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                No bookings found. Try changing your search or filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div>
            {{ $bookings->links() }}
        </div>

        <div>
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm font-semibold text-red-400 transition hover:text-red-300">
                ← Back to Admin Dashboard
            </a>
        </div>

    </div>
@endsection
