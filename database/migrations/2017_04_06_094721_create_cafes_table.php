<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCafesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cafes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('website')->default('');
            $table->string('email')->default('');
            $table->string('city')->default('');
            $table->string('address')->default('');

            $table->decimal('geo_latitude',8,5);
            $table->decimal('geo_longitude',8,5);
            $table->tinyInteger('free_wifi')->default(0);
            $table->tinyInteger('free_parking')->default(0);
            $table->tinyInteger('tea_avaliable')->default(0);
            $table->tinyInteger('terrace_avaliable')->default(0);
            $table->tinyInteger('cake_avaliable')->default(0);
            $table->integer('profile_image_id')->nullable();
            $table->time('weekday_open')->nullable();
            $table->time('monday_open')->nullable();
            $table->time('tuesday_open')->nullable();
            $table->time('wednesday_open')->nullable();
            $table->time('thursday_open')->nullable();
            $table->time('friday_open')->nullable();
            $table->time('weekday_close')->nullable();
            $table->time('monday_close')->nullable();
            $table->time('tuesday_close')->nullable();
            $table->time('wednesday_close')->nullable();
            $table->time('thursday_close')->nullable();
            $table->time('friday_close')->nullable();
            $table->tinyInteger('monday_is_closed')->nullable();
            $table->tinyInteger('tuesday_is_closed')->nullable();
            $table->tinyInteger('wednesday_is_closed')->nullable();
            $table->tinyInteger('thursday_is_closed')->nullable();
            $table->tinyInteger('friday_is_closed')->nullable();
            $table->tinyInteger('saturnday_is_closed')->nullable();
            $table->tinyInteger('sunday_is_closed')->nullable();

            $table->time('saturnday_close')->nullable();
            $table->time('saturnday_open')->nullable();
            $table->time('sunday_open')->nullable();
            $table->time('sunday_close')->nullable();
             $table->tinyInteger('cgf')->default(0);


            //$table->string('logo_path')->nullable();
            //$table->binary('data')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cafes');
    }
}
