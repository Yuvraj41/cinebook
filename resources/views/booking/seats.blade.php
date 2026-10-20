@extends('layouts.app')

@php
    $price = (float) $show->ticket_price;
@endphp

@section('title', 'Select Seats | CineBook')

@section('content')

<div class="mx-auto max-w-6xl">

    {{-- Back navigation --}}
    <a href="{{ route('shows.index', $show->movie) }}"
       class="mb-6 inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-white">
        &larr; Back to Showtimes
    </a>

    {{-- Show information --}}
    <section class="mb-8 rounded-2xl border border-white/10 bg-slate-900 p-5 sm:p-7">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-red-400">
                    Complete your booking
                </p>

                <h1 class="mt-3 text-3xl font-black sm:text-4xl">
                    {{ $show->movie->title }}<span class="text-red-500">.</span>
                </h1>

                <p class="mt-3 text-slate-300">
                    {{ $show->screen->theatre->name }}
                    <span class="text-slate-600">|</span>
                    {{ $show->screen->theatre->location }}
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    Screen {{ $show->screen->name }}
                    &bull;
                    {{ $show->start_time->format('D, d M Y') }}
                    &bull;
                    {{ $show->start_time->format('h:i A') }}
                </p>
            </div>

            <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-5 py-4 sm:min-w-36">
                <p class="text-sm text-slate-400">Price per seat</p>
                <p class="mt-1 text-2xl font-black text-red-400">
                    ₹{{ number_format($price, 2) }}
                </p>
            </div>

        </div>
    </section>

    {{-- Success and validation messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-500/30 bg-green-500/10 p-4 text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-300">
            <p class="mb-2 font-semibold">Please check the following:</p>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Seat selection panel --}}
    <section class="rounded-2xl border border-white/10 bg-slate-900 p-4 sm:p-8">

        <div class="text-center">
            <h2 class="text-2xl font-bold">Choose Your Seats</h2>
            <p class="mt-2 text-sm text-slate-400">
                Select up to 8 seats for your movie experience.
            </p>
        </div>

        {{-- Seat legend --}}
        <div class="my-8 flex flex-wrap justify-center gap-x-6 gap-y-3 text-sm text-slate-300">

            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded border border-slate-600 bg-slate-700"></span>
                Available
            </span>

            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded bg-sky-500"></span>
                Selected
            </span>

            <span class="flex items-center gap-2">
                <span class="h-4 w-4 rounded bg-slate-800 ring-1 ring-white/10"></span>
                Reserved
            </span>

        </div>

        {{-- Cinema screen --}}
        <div class="mx-auto mb-10 max-w-3xl px-4 text-center">
            <div class="h-1.5 rounded-[50%] bg-sky-400 shadow-[0_0_24px_rgba(56,189,248,0.45)]"></div>
            <p class="mt-4 text-xs font-bold tracking-[0.45em] text-slate-500">
                SCREEN THIS WAY
            </p>
        </div>

        {{-- Seat map --}}
        <div class="overflow-x-auto pb-5">
            <form id="bookingForm"
                  method="POST"
                  action="{{ route('booking.store', $show) }}">
                @csrf

                <div id="seatMap" class="mx-auto min-w-[570px] max-w-4xl"></div>

                <div id="seatInputs"></div>
            </form>
        </div>

        {{-- Selection summary --}}
        <div class="mt-8 rounded-2xl border border-white/10 bg-slate-950 p-5 sm:p-6">

            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                <div class="min-w-0">
                    <p class="text-sm text-slate-400">Your selected seats</p>

                    <p id="selectedSeats"
                       aria-live="polite"
                       class="mt-2 break-words font-semibold text-white">
                        None
                    </p>

                    <p class="mt-4 text-sm text-slate-400">Total amount</p>

                    <p id="total" class="mt-1 text-3xl font-black text-white">
                        ₹0.00
                    </p>

                    <p class="mt-2 text-xs text-slate-500">
                        Maximum 8 seats per booking
                    </p>
                </div>

                <button
                    id="continueBtn"
                    type="submit"
                    form="bookingForm"
                    disabled
                    class="rounded-xl bg-red-600 px-6 py-3.5 font-bold text-white transition hover:bg-red-500 disabled:cursor-not-allowed disabled:bg-slate-700 disabled:text-slate-400">
                    Continue to Booking &rarr;
                </button>

            </div>
        </div>

    </section>

</div>

<script>
    const rows = 'ABCDEFGHIJ'.split('');
    const pricePerSeat = {{ Illuminate\Support\Js::from($price) }};
    const reservedSeats = {{ Illuminate\Support\Js::from($reservedSeats) }};

    const selectedSeats = new Set();
    const seatMap = document.getElementById('seatMap');
    const continueBtn = document.getElementById('continueBtn');

    const availableSeatClass =
        'rounded-md border border-slate-600 bg-slate-700 px-0 py-1.5 text-[9px] font-medium text-white transition hover:border-sky-400 hover:bg-slate-600';

    const selectedSeatClass =
        'rounded-md border border-sky-400 bg-sky-500 px-0 py-1.5 text-[9px] font-bold text-white shadow-[0_0_10px_rgba(56,189,248,0.3)]';

    const reservedSeatClass =
        'cursor-not-allowed rounded-md border border-slate-800 bg-slate-950 px-0 py-1.5 text-[9px] text-slate-600';

    rows.forEach(row => {
        const rowElement = document.createElement('div');

        rowElement.className =
            'mb-2 grid grid-cols-[20px_repeat(9,minmax(0,1fr))_20px_repeat(9,minmax(0,1fr))] items-center gap-1.5';

        const label = document.createElement('span');
        label.className = 'text-xs font-bold text-slate-500';
        label.textContent = row;
        rowElement.appendChild(label);

        for (let number = 1; number <= 18; number++) {

            // Aisle between seats 9 and 10
            if (number === 10) {
                const aisle = document.createElement('span');
                aisle.setAttribute('aria-hidden', 'true');
                rowElement.appendChild(aisle);
            }

            const seatId = row + number;
            const seat = document.createElement('button');

            seat.type = 'button';
            seat.textContent = seatId;
            seat.setAttribute('aria-label', 'Seat ' + seatId);

            if (reservedSeats.includes(seatId)) {
                seat.disabled = true;
                seat.className = reservedSeatClass;
                seat.setAttribute('aria-label', 'Seat ' + seatId + ', reserved');
            } else {
                seat.className = availableSeatClass;

                seat.addEventListener('click', () => {
                    if (selectedSeats.has(seatId)) {
                        selectedSeats.delete(seatId);
                        seat.className = availableSeatClass;
                    } else {
                        if (selectedSeats.size >= 8) {
                            alert('You can select up to 8 seats per booking.');
                            return;
                        }

                        selectedSeats.add(seatId);
                        seat.className = selectedSeatClass;
                    }

                    updateSummary();
                });
            }

            rowElement.appendChild(seat);
        }

        seatMap.appendChild(rowElement);
    });

    function updateSummary() {
        const seats = [...selectedSeats].sort((a, b) => {
            return a.charCodeAt(0) - b.charCodeAt(0)
                || Number(a.slice(1)) - Number(b.slice(1));
        });

        document.getElementById('selectedSeats').textContent =
            seats.length ? seats.join(', ') : 'None';

        document.getElementById('total').textContent =
            '₹' + (seats.length * pricePerSeat).toLocaleString('en-IN', {
                maximumFractionDigits: 2,
                minimumFractionDigits: 2
            });

        continueBtn.disabled = seats.length === 0;
    }

    document.getElementById('bookingForm').addEventListener('submit', function (event) {
        if (selectedSeats.size === 0) {
            event.preventDefault();
            alert('Please select at least one seat.');
            return;
        }

        const seatInputs = document.getElementById('seatInputs');
        seatInputs.replaceChildren();

        selectedSeats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seats[]';
            input.value = seat;
            seatInputs.appendChild(input);
        });
    });
</script>

@endsection
