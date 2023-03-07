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
            $table->string('Steering_Mounted_Controls')->nullable();
            $table->string('Center_Console_Box')->nullable();
            $table->string('Speedometer_Cluster')->nullable();
            $table->string('Door_Trim_Panels')->nullable();
            $table->string('Headliner')->nullable();
            $table->string('Seat_Controls')->nullable();
            $table->string('Boot_Trunk_Area')->nullable();
            $table->string('Central_Lock_Operation')->nullable();
            $table->string('Music_Multimedia_System')->nullable();
            $table->string('Navigation_Control')->nullable();
            $table->string('Headlights')->nullable();
            $table->string('Tail_Lights')->nullable();
            $table->string('Sunroof_Condition')->nullable();
            $table->string('Windows_Controls_Condition')->nullable();
            $table->string('Cruise_Control')->nullable();
            $table->string('Push_Stop_Button')->nullable();
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
