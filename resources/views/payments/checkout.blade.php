
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook | Payment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-xl px-4 py-12">
        <div class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">

            <a href="{{ route('booking.index') }}"
               class="text-sm font-medium text-blue-600 hover:underline">
                ← Back to My Bookings
            </a>

            <h1 class="mt-6 text-2xl font-bold">Complete your payment</h1>

            <p class="mt-2 text-slate-500">
                {{ $booking->show->movie->title }}
            </p>

            <div class="mt-6 rounded-xl bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Booking ID</p>
                <p class="font-semibold">#{{ $booking->id }}</p>

                <p class="mt-4 text-sm text-slate-500">Amount to pay</p>
                <p class="mt-1 text-3xl font-bold text-blue-700">
                    ₹{{ number_format((float) $booking->total_price, 2) }}
                </p>
            </div>

            <button
                id="payButton"
                type="button"
                class="mt-6 w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Pay ₹{{ number_format((float) $booking->total_price, 2) }}
            </button>

            <p id="paymentMessage" class="mt-4 text-sm text-slate-600"></p>

            <p class="mt-5 text-xs text-slate-400">
                Test Mode: no real money will be charged.
            </p>
        </div>
    </main>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        const payButton = document.getElementById('payButton');
        const paymentMessage = document.getElementById('paymentMessage');

        payButton.addEventListener('click', function () {
            const options = {
                key: @json($razorpayKey),
                amount: {{ (int) $order['amount'] }},
                currency: @json($order['currency']),
                name: 'CineBook',
                description: @json($booking->show->movie->title),
                order_id: @json($order['id']),

                
handler: async function (response) {
    payButton.disabled = true;
    paymentMessage.textContent = 'Verifying your payment...';

    try {
        const result = await fetch(@json(route('payments.verify')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token())
            },
            body: JSON.stringify({
                booking_id: @json($booking->id),
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature
            })
        });

        const data = await result.json();

        if (!result.ok) {
            throw new Error(
                data.message || 'Payment verification failed.'
            );
        }

        paymentMessage.textContent = data.message;

        window.location.href =
            data.redirect || @json(route('booking.index'));

    } catch (error) {
        paymentMessage.textContent =
            error.message +
            ' Check My Bookings before attempting another payment.';

        payButton.disabled = false;
    }
},


                modal: {
                    ondismiss: function () {
                        paymentMessage.textContent =
                            'Checkout closed. Your booking is still pending.';
                    }
                },

                theme: {
                    color: '#2563eb'
                }
            };

            const checkout = new Razorpay(options);

            checkout.on('payment.failed', function () {
                paymentMessage.textContent =
                    'Payment failed. Your booking has not been confirmed.';
            });

            checkout.open();
        });
    </script>
</body>
</html>
