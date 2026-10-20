@extends('layouts.app')

@section('title', 'My Bookings | CineBook')

@section('content')

    {{-- Page heading --}}
    <section class="mb-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="mb-3 text-sm font-bold uppercase tracking-[0.3em] text-red-500">
                Your movie nights
            </p>

            <h1 class="text-4xl font-black tracking-tight sm:text-5xl">
                My Bookings<span class="text-red-500">.</span>
            </h1>

            <p class="mt-3 text-slate-400">
                Manage your tickets and view your booking details.
            </p>
        </div>

        <a href="{{ route('movies.index') }}"
           class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
            Browse Movies &rarr;
        </a>
    </section>

    {{-- Booking list --}}
    @forelse ($bookings as $booking)

        <article class="mb-6 overflow-hidden rounded-2xl border border-white/10 bg-slate-900">

            {{-- Booking header --}}
            <div class="flex flex-col justify-between gap-4 border-b border-white/10 p-5 sm:flex-row sm:items-center sm:p-6">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Booking #{{ $booking->id }}
                    </p>

                    <h2 class="mt-2 text-2xl font-bold">
                        {{ $booking->show->movie->title }}
                    </h2>

                    <p class="mt-2 text-sm text-slate-400">
                        Booked on {{ $booking->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>

                <span class="inline-flex w-fit items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold
                    {{ $booking->status === 'confirmed'
                        ? 'bg-green-500/10 text-green-400 ring-1 ring-green-500/20'
                        : ($booking->status === 'pending'
                            ? 'bg-amber-500/10 text-amber-400 ring-1 ring-amber-500/20'
                            : 'bg-slate-700/50 text-slate-300 ring-1 ring-white/10') }}">

                    <span class="h-2 w-2 rounded-full
                        {{ $booking->status === 'confirmed'
                            ? 'bg-green-400'
                            : ($booking->status === 'pending'
                                ? 'bg-amber-400'
                                : 'bg-slate-400') }}">
                    </span>

                    {{ ucfirst($booking->status) }}
                </span>

            </div>

            {{-- Booking details --}}
            <div class="grid gap-6 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-3">

                <div>
                    <p class="text-sm text-slate-500">Theatre</p>
                    <p class="mt-2 font-semibold text-white">
                        {{ $booking->show->screen->theatre->name }}
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $booking->show->screen->theatre->location }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Showtime</p>
                    <p class="mt-2 font-semibold text-white">
                        {{ $booking->show->start_time->format('d M Y') }}
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $booking->show->start_time->format('h:i A') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Screen</p>
                    <p class="mt-2 font-semibold text-white">
                        {{ $booking->show->screen->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Your Seats</p>

                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse ($booking->seats as $seat)
                            <span class="rounded-lg border border-sky-500/20 bg-sky-500/10 px-3 py-1.5 text-sm font-semibold text-sky-300">
                                {{ $seat->seat_number }}
                            </span>
                        @empty
                            <span class="text-sm text-slate-500">
                                No seats recorded
                            </span>
                        @endforelse
                    </div>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Total Amount</p>
                    <p class="mt-2 text-2xl font-black text-white">
                        ₹{{ number_format((float) $booking->total_price, 2) }}
                    </p>
                </div>

            </div>

            {{-- Booking actions --}}
            @if ($booking->status === 'pending')
                <div class="flex flex-col gap-3 border-t border-white/10 bg-slate-950/50 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <p class="text-sm text-amber-400">
                        Complete your payment to confirm this booking.
                    </p>

                    <div class="flex flex-wrap gap-3">

                        <form method="POST"
                              action="{{ route('booking.cancel', $booking) }}"
                              onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="rounded-xl border border-red-500/30 px-4 py-3 font-semibold text-red-400 transition hover:bg-red-500/10">
                                Cancel Booking
                            </button>
                        </form>

                        <a href="{{ route('payments.checkout', $booking) }}"
                           class="rounded-xl bg-red-600 px-5 py-3 text-center font-semibold text-white transition hover:bg-red-500">
                            Pay Now &rarr;
                        </a>

                    </div>
                </div>
            @elseif ($booking->status === 'confirmed')
                <div class="border-t border-green-500/10 bg-green-500/5 px-5 py-4 sm:px-6">
                    <p class="text-sm font-medium text-green-400">
                        ✓ Payment confirmed. Your booking is confirmed.
                    </p>
                </div>
            @endif

        </article>

    @empty

        {{-- Empty state --}}
        <section class="rounded-2xl border border-dashed border-white/10 bg-slate-900/50 px-6 py-20 text-center">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/10 text-3xl">
                🎟️
            </div>

            <h2 class="mt-6 text-2xl font-bold">
                No bookings yet
            </h2>

            <p class="mx-auto mt-3 max-w-md text-slate-400">
                Your movie tickets will appear here after you book a show.
                Find a movie and plan your next cinema experience.
            </p>

            <a href="{{ route('movies.index') }}"
               class="mt-7 inline-flex rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500">
                Explore Movies &rarr;
            </a>

        </section>

    @endforelse

@endsection

