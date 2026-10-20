<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\Booking;

class Show extends Model
{
protected $fillable = [
    'movie_id',
    'screen_id',
    'start_time',
    'ticket_price',
];

protected $casts = [
    'start_time' => 'datetime',
];

public function movie()
{
    return $this->belongsTo(Movie::class);
}

public function screen()
{
    return $this->belongsTo(Screen::class);
}

public function bookings()
{
    return $this->hasMany(Booking::class);
}

}
