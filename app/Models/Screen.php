<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Theatre;
use App\Models\Show; 

class Screen extends Model
{
    
public function theatre()
{
    return $this->belongsTo(Theatre::class);
}

public function shows()
{
    return $this->hasMany(Show::class);
}


}
