<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use App\Jobs\SendBookingConfirmation;

class PaymentController extends Controller
{
    public function checkout(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        abort_unless(
            $booking->status === 'pending',
            403,
            'This booking cannot be paid for.'
        );

        $booking->load('show.movie');

        abort_if(
            $booking->show->start_time->isPast(),
            403,
            'This show has already started.'
        );

        $amountInPaise = (int) round(
            (float) $booking->total_price * 100
        );

        abort_if(
            $amountInPaise < 100,
            422,
            'The booking amount must be at least ₹1.'
        );

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt' => 'booking_' . $booking->id,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'notes' => [
                'booking_id' => (string) $booking->id,
            ],
        ]);

        // Save the Razorpay order ID BEFORE returning the view.
        $booking->update([
            'razorpay_order_id' => $order['id'],
        ]);

        return view('payments.checkout', [
            'booking' => $booking,
            'order' => $order,
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $booking = Booking::whereKey($validated['booking_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Expire overdue pending bookings.
        if (
            $booking->status === 'pending'
            && $booking->created_at->lte(now()->subMinutes(10))
        ) {
            $booking->update(['status' => 'expired']);
        }

        abort_unless(
            $booking->status === 'pending',
            403,
            'This booking has expired or cannot be confirmed.'
        );

        abort_unless(
            $booking->status === 'pending',
            403,
            'This booking cannot be confirmed.'
        );

        abort_unless(
            $booking->razorpay_order_id !== null
                && hash_equals(
                    $booking->razorpay_order_id,
                    $validated['razorpay_order_id']
                ),
            422,
            'The payment order does not match this booking.'
        );

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        // Verify the signature using the stored order ID.
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $booking->razorpay_order_id,
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_signature' => $validated['razorpay_signature'],
        ]);

        // Verify the payment against Razorpay's server.
        $payment = $api->payment->fetch(
            $validated['razorpay_payment_id']
        );

        abort_unless(
            $payment['order_id'] === $booking->razorpay_order_id,
            422,
            'The payment belongs to a different order.'
        );

        abort_unless(
            (int) $payment['amount'] === (int) round(
                (float) $booking->total_price * 100
            ) && $payment['currency'] === 'INR',
            422,
            'The payment amount or currency does not match.'
        );

        abort_unless(
            $payment['status'] === 'captured',
            422,
            'The payment has not been captured yet.'
        );

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status !== 'pending') {
                abort(409, 'This booking has already been processed.');
            }

            $lockedBooking->update([
                'status' => 'confirmed',
                'razorpay_payment_id' =>
                    $validated['razorpay_payment_id'],
            ]);
        });
        SendBookingConfirmation::dispatch($booking->id);

        // Return JSON AFTER the transaction succeeds.
        return response()->json([
            'message' => 'Payment verified and booking confirmed!',
            'redirect' => route('booking.index'),
        ]);
    }
}
