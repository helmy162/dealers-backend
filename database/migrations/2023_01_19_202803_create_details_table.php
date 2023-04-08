<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('trim')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedBigInteger('mileage')->nullable();
            $table->string('registered_emirates')->nullable();
            $table->unsignedBigInteger('engine_size')->nullable();
            $table->unsignedBigInteger('number_of_cylinders')->nullable();
            $table->string('interior_type')->nullable();
            $table->string('body_type')->nullable();
            $table->string('specification')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('keys')->nullable();
            $table->string('first_owner')->nullable();
            $table->unsignedBigInteger('seller_price')->nullable();
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
        Schema::dropIfExists('details');
    }
};
