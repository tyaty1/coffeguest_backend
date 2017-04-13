<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    public function cafe()
    {
    	return $this->belongsTo('App\Cafe');
    }
}
