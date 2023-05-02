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
        Schema::create('specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->boolean("Fog_Lights")->nullable();
            $table->boolean("Parking_Sensor")->nullable();
            $table->boolean("Winch")->nullable();
            $table->boolean("Roof_Rack")->nullable();
            $table->boolean("Spoiler")->nullable();
            $table->boolean("Dual_Exhaust")->nullable();
            $table->boolean("Alarm")->nullable();
            $table->boolean("Rear_Video")->nullable();
            $table->boolean("Premium_Sound")->nullable();
            $table->boolean("Heads_Up_Display")->nullable();
            $table->boolean("Aux_Audio")->nullable();
            $table->boolean("Bluetooth")->nullable();
            $table->boolean("Climate_Control")->nullable();
            $table->boolean("Keyless_Entry")->nullable();
            $table->boolean("Keyless_Start")->nullable();
            $table->boolean("Leather_Seats")->nullable();
            $table->boolean("Racing_Seats")->nullable();
            $table->boolean("Cooled_Seats")->nullable();
            $table->boolean("Heated_Seats")->nullable();
            $table->boolean("Power_Seats")->nullable();
            $table->boolean("Power_Locks")->nullable();
            $table->boolean("Power_Mirrors")->nullable();
            $table->boolean("Power_Windows")->nullable();
            $table->boolean("Memory_Seats")->nullable();
            $table->boolean("View_Camera")->nullable();
            $table->boolean("Blind_Spot_Indicator")->nullable();
            $table->boolean("Anti_Lock")->nullable();
            $table->boolean("Adaptive_Cruise_Control")->nullable();
            $table->boolean("Power_Steering")->nullable();
            $table->boolean("Night_Vision")->nullable();
            $table->boolean("Lift_Kit")->nullable();
            $table->boolean("Park_Assist")->nullable();
            $table->boolean("Adaptive_Suspension")->nullable();
            $table->boolean("Height_Control")->nullable();
            $table->boolean("Navigation_System")->nullable();
            $table->string("Drives")->nullable();
            $table->string("Sunroof_Type")->nullable();
            $table->boolean("N29_System")->nullable();
            $table->boolean("Side_Steps")->nullable();
            $table->string("Convertible")->nullable();
            $table->string('Other_Features')->nullable();
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
        Schema::dropIfExists('specs');
    }
};
