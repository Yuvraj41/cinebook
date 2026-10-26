<?php

namespace App\Jobs;

use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmation implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $bookingId
    ) {}

    public function handle(): void
    {
        $booking = Booking::with([
            'user',
            'show.movie',
            'show.screen.theatre',
            'seats',
        ])->findOrFail($this->bookingId);

        Mail::to($booking->user->email)
            ->send(new BookingConfirmationMail($booking));
    }
}