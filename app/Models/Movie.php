<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Show;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'duration',
        'language',
        'description',
        'poster_url',
        'release_date',
    ];

    
public function shows()
{
    return $this->hasMany(Show::class);
}

}
