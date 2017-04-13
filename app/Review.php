<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ['title','body','cafe_id','user_id','recommended'];
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function cafe()
    {
    	return $this->belongsTo('App\Cafe');
    }
}
