<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public function users(){
    	return belongsToMany('App/User','user_roles')->withTimestamps();

    }
}
