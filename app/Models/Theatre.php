<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Screen;

class Theatre extends Model
{
    public function screens()
   {
    return $this->hasMany(Screen::class);
   }
}
