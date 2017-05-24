<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('address')->default('');
            $table->integer('linked_to_facebook')->default(-1);
            $table->integer('notifications_enabled')->default(-1);


            $table->string('email')->default('')->unique();
            $table->string('password')->default('');
            $table->string('sex')->default('');
            $table->date('birth_date')->nullable();

            $table->string('facebook_token')->default('');
            $table->string('facebook_avatar')->default('');
            $table->string('avatar_type')->nullable();
            $table->integer('avatar_id')->default(-1);

            $table->string('facebook_id')->default('');
            $table->rememberToken();
            $table->timestamps();
            $table->decimal('in_geo_latitude',8,5)->default(0);
            $table->decimal('in_geo_longitude',8,5)->default(0);
            $table->integer('in_cafe_id')->default(-1);
            $table->string('device_token')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
