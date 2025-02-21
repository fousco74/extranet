<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['day', 'month', 'year','startClock' ,'endClock' ,'user_id', 'description', 'title', 'monthNumber', 'dayName'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
