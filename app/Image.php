<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ImageCon;

class Image extends Model
{
    //
		protected $fillable=['filepath','is_avatar'];

        public function cafe()
    {
    	return $this->belongsTo('App\Cafe');
    }
        public function user()
    {
    	return $this->belongsTo('App\User');
    }

}
