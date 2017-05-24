<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    //
        protected $fillable = ['name', 'geo_latitude', 'geo_longitude'];



            public function favorited_by_users(){
        		return $this->belongsToMany('App\User','favorites');
    		}
    		public function reviews(){
    			return $this->hasMany('App\Review');
    		}
    		public function events(){
    			return $this->hasMany('App\Event');
    		}
            public function images(){
                return $this->hasMany('App\Image');
    }

            

}

