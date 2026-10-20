<?php
namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\BookingSeat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class BookingController extends Controller
{
    public function seats(Show $show)
    {
        $this->expirePendingBookings();
        // Get seats already booked for this show
        $reservedSeats = BookingSeat::where('show_id', $show->id)
    ->whereHas('booking', function ($query) {
        $query->whereIn('status', ['pending', 'confirmed']);
    })
    ->pluck('seat_number')
    ->toArray();

        // Load movie, screen and theatre details
        $show->load('movie', 'screen.theatre');

        return view('booking.seats', compact('show', 'reservedSeats'));
    }


    
public function store(Request $request, Show $show)
{
    $validated = $request->validate([
        'seats' => ['required', 'array', 'min:1', 'max:8'],
        'seats.*' => [
            'required',
            'string',
            'distinct',
            'regex:/^[A-J](?:[1-9]|1[0-8])$/',
        ],
    ]);

    try {
        $booking = DB::transaction(function () use ($validated, $show, $request) {

            // Lock the show while checking seat availability.
            $lockedShow = Show::whereKey($show->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedShow->start_time->isPast()) {
                throw ValidationException::withMessages([
                    'seats' => 'This show has already started.',
                ]);
            }

            $seats = $validated['seats'];

            $alreadyBooked = BookingSeat::where('show_id', $lockedShow->id)
                ->whereIn('seat_number', $seats)
                ->whereHas('booking', function ($query) {
                    $query->whereIn('status', ['pending', 'confirmed']);
                })
                ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages([
                    'seats' => 'One or more selected seats are no longer available. Please choose again.',
                ]);
            }

            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'show_id' => $lockedShow->id,
                'total_price' => count($seats) * $lockedShow->ticket_price,
                'status' => 'pending',
            ]);

            foreach ($seats as $seatNumber) {
                $booking->seats()->create([
                    'show_id' => $lockedShow->id,
                    'seat_number' => $seatNumber,
                ]);
            }

            return $booking;
        });
    } catch (QueryException $e) {
        if (in_array($e->errorInfo[1] ?? null, [1062, 19], true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'seats' => 'One or more seats have just been booked. Please select different seats.',
                ]);
        }
        throw $e;
    }

    return redirect()
        ->route('booking.index')
        ->with('success', 'Booking created successfully!');
}



public function index()
{
    $bookings = \App\Models\Booking::with([
        'show.movie',
        'show.screen.theatre',
        'seats',
    ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('booking.index', compact('bookings'));
}


public function cancel(Booking $booking)
{
    if ($booking->user_id !== auth()->id()) {
        abort(403, 'You cannot cancel this booking.');
    }

    DB::transaction(function () use ($booking) {
        $booking = Booking::whereKey($booking->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            throw ValidationException::withMessages([
                'booking' => 'Only pending bookings can be cancelled.',
            ]);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        // Release the seats associated with this booking.
        $booking->seats()->delete();
    });

    return redirect()
        ->route('booking.index')
        ->with('success', 'Booking cancelled successfully.');
}

private function expirePendingBookings(): void
{
    Booking::where('status', 'pending')
        ->where('created_at', '<=', now()->subMinutes(10))
        ->update([
            'status' => 'expired',
        ]);
}

}

