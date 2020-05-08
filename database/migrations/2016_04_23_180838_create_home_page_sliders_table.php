<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomePageSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 100);
            $table->integer('order');
            $table->string('name',25);
            $table->string('description',100);
            $table->enum('source', ['Local', 'Cloudinary'])->default('Local');
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_page_sliders');
    }
}
