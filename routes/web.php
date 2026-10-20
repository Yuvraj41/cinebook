<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminShowController;

// Homepage
Route::get('/', function () {
    return redirect()->route('movies.index');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});



// Public movie browsing
Route::get('/movies', [MovieController::class, 'index'])
    ->name('movies.index');

Route::get('/movies/{movie}/shows', [ShowController::class, 'index'])
    ->name('shows.index');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Seat booking
    Route::get('/shows/{show}/seats', [BookingController::class, 'seats'])
        ->name('booking.seats');

    Route::post('/shows/{show}/book', [BookingController::class, 'store'])
        ->name('booking.store');

    // My bookings
    Route::get('/my-bookings', [BookingController::class, 'index'])
        ->name('booking.index');

    Route::patch('/my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('booking.cancel');

    // Payments
    Route::get('/bookings/{booking}/checkout', [PaymentController::class, 'checkout'])
        ->name('payments.checkout');

    Route::post('/payments/verify', [PaymentController::class, 'verify'])
        ->name('payments.verify');


    // Profile routes
    Route::get('/profile', function () {
    return view('/profile/profile');})->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');



    // Admin-only routes
    Route::middleware('admin')->group(function () {

        // Movie management
        Route::get('/movies/create', [MovieController::class, 'create'])
            ->name('movies.create');

        Route::post('/movies', [MovieController::class, 'store'])
            ->name('movies.store');

        Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])
            ->name('movies.edit');

        Route::put('/movies/{movie}', [MovieController::class, 'update'])
            ->name('movies.update');

        Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])
            ->name('movies.destroy');

        // Show management
        Route::get('/shows/create', [ShowController::class, 'create'])
            ->name('shows.create');

        Route::post('/shows', [ShowController::class, 'store'])
            ->name('shows.store');

        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/admin/bookings', [AdminBookingController::class, 'index'])
            ->name('admin.bookings.index');

        Route::get('/admin/shows', [AdminShowController::class, 'index'])
            ->name('admin.shows.index');

        Route::get('/admin/shows/{show}/edit', [ShowController::class, 'edit'])
            ->name('admin.shows.edit');

        Route::put('/admin/shows/{show}', [ShowController::class, 'update'])
            ->name('admin.shows.update');

        Route::delete('/admin/shows/{show}', [ShowController::class, 'destroy'])
            ->name('admin.shows.destroy');
    });
});