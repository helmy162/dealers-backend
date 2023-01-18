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
        Schema::create('interiors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->string('Dashboard_Condition')->nullable();
            $table->string('Steering_Controls')->nullable();
            $table->string('Center_Console_Box')->nullable();
            $table->string('Speedometer_Cluster')->nullable();
            $table->string('Door_Trims')->nullable();
            $table->string('Headliner')->nullable();
            $table->string('Seat_Controller')->nullable();
            $table->string('Boot_Trunk')->nullable();
            $table->string('Central_Lock')->nullable();
            $table->string('Music_System')->nullable();
            $table->string('Navigation_Control')->nullable();
            $table->string('Headlights')->nullable();
            $table->string('Tail_Light')->nullable();
            $table->string('Sunroof_Condition')->nullable();
            $table->string('Windows_Control')->nullable();
            $table->string('Cruise_Control')->nullable();
            $table->string('Push_start')->nullable();
            $table->string('AC_Cooling')->nullable();
            $table->string('Convertible_Operations')->nullable();
            $table->string('AC_Heating')->nullable();
            $table->string('Interior_Comment')->nullable();
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
        Schema::dropIfExists('interiors');
    }
};
