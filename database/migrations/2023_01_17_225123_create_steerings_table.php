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
        Schema::create('steerings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->string('Brake_Pads')->nullable();
            $table->string('Brake_Discs_Or_Lining')->nullable();
            $table->string('Parking_Brake_Operations')->nullable();
            $table->string('Suspension')->nullable();
            $table->string('Shock_Absorber_Operation')->nullable();
            $table->string('Steering_Operation')->nullable();
            $table->string('Steering_Alignment')->nullable();
            $table->string('Wheel_Alignment')->nullable();
            $table->string('Steering_Comment')->nullable();
            $table->string('Rotors_and_Drums')->nullable();
            $table->string('Struts_and_Shocks')->nullable();
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
        Schema::dropIfExists('steerings');
    }
};
