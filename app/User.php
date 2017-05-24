<?php

namespace App;

use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','address', 'password','notifications_enabled','linked_to_facebook','in_geo_latitude','in_geo_longitude','in_cafe_id','avatar_id','facebook_id','sex','birth_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function favorite_cafes(){
        return $this->belongsToMany('App\Cafe','favorites');
    }
    public function reviews(){
        return $this->hasMany('App\Review');
    }
    public function images(){
        return $this->hasMany('App\Image');
    }
    public function roles(){
        return $this->belongsToMany('App/Role','user_roles');

    }



}
