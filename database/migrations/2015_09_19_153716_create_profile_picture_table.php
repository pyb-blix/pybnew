<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilePictureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('profile_picture');
        
        Schema::create('profile_picture', function (Blueprint $table) {
            $table->integer('user_id')->unique()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('src');
            $table->string('upload_driver',20)->default('Local');
            $table->string('photo_source',50)->default('Local');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_picture');
    }
}
