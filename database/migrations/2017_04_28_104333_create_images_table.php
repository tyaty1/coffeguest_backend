<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('filepath')->default('');
            $table->integer('user_id')->default(-1);
            $table->integer('cafe_id')->default(-1);
            $table->string('category')->nullable();
            $table->tinyInteger('is_external')->default(0);
            $table->tinyInteger('is_avatar')->default(0);
            $table->tinyInteger('is_public')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
