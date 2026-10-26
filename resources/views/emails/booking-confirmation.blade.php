<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed</title>
</head>
<body>
    <h1>Your CineBook Booking is Confirmed!</h1>

    <p>Hi {{ $booking->user->name }},</p>

    <p>Your payment was successful and your booking is confirmed.</p>

    <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
    <p><strong>Movie:</strong> {{ $booking->show->movie->title }}</p>
    <p><strong>Show time:</strong> {{ $booking->show->start_time }}</p>
    <p><strong>Seats:</strong>
        {{ $booking->seats->pluck('seat_number')->join(', ') }}
    </p>
    <p><strong>Total paid:</strong> ₹{{ number_format($booking->total_price, 2) }}</p>

    <p>Thank you for choosing CineBook. Enjoy your movie!</p>
</body>
</html>