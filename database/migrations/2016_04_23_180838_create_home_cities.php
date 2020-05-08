<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('display_name', 50);
            $table->string('latitude',50);
            $table->string('longitude',50);
            $table->string('image', 100);
            $table->enum('source', ['Local', 'Cloudinary'])->default('Local');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('home_cities');
    }
}
