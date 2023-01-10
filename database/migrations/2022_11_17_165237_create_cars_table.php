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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('inspector_id');
            $table->enum('status', ['pending', 'approved', 'rejected']);

            $table->unsignedInteger('make');
            $table->string('color');
            $table->unsignedBigInteger('millage');
            $table->enum('fuel', ['Petrol', 'Diesel', 'Hybrid', 'Electric']);
            $table->enum('transmission', ['Automatic', 'Manual']);
            $table->enum('body_type', ['Sedan', 'Hatchback', 'SUV', 'MPV', 'Coupe', 'Convertible', 'Wagon', 'Van', 'Truck']);

            $table->enum('Radiator_Condition', ['Good','No Visible Faults','Needs Attention']);
            $table->enum('Engine_Noise', ['Good', 'Tappet Noise']);
            $table->enum('Engine_Belts', ['Good', 'No Visible Fault']);
            $table->enum('Engine_Idling', ['Good', 'No Visible Fault']);
            $table->enum('Engine_Oil', ['Good', 'Needs Attention']);
            $table->enum('Engine_Oil_Level', ['Good', 'Needs Attention']);
            $table->enum('Engine_Oil_Pressure', ['Good', 'Needs Attention']);
            $table->enum('Battery_Condition', ['Good', 'Weak']);
            $table->enum('Engine_Smoke', ['Black', 'Needs Attention']);
            $table->enum('Gear_Lever', ['Good', 'Needs Attention']);
            $table->enum('4WD_System_Condition', ['Good', 'N/A']);
            $table->enum('Radiator_Fan', ['Good', 'Needs Attention']);
            $table->enum('Coolant', ['Good', 'Needs Attention']);
            $table->enum('Coolant_Level', ['Good', 'Needs Attention']);
            $table->enum('Gear_Shifting', ['Good', 'Needs Attention']);
            $table->enum('Silencer', ['Good', 'Needs Attention']);

            $table->enum('Brake_Pads', ['Good', 'Needs Attention']);
            $table->enum('Parking_Brake_Operation', ['Good', 'Needs Attention']);
            $table->enum('Shock_Absorber_Operation', ['Good', 'Needs Attention']);
            $table->enum('Steering_Alignment', ['Good', 'Needs Attention']);
            $table->enum('Brake_Discs_Or_Lining', ['Good', 'Needs Attention']);
            $table->enum('Suspension', ['Good', 'Needs Attention']);
            $table->enum('Steering_Operation', ['Good', 'Needs Attention']);
            $table->enum('Wheel_Alignment', ['Good', 'Needs Attention']);

            $table->enum('Dashboard_Condition', ['Good', 'Needs Attention']);
            $table->enum('Air_Conditioner', ['Good', 'Needs Attention']);
            $table->enum('Center_Console_Box', ['Good', 'Needs Attention']);
            $table->enum('Door_Trim_Panels', ['Good', 'Needs Attention']);
            $table->enum('Seat_Controls', ['Good', 'Needs Attention']);
            $table->enum('Central_Lock_Operation', ['Good', 'Needs Attention']);
            $table->enum('Navigation_Control', ['Good', 'Needs Attention']);
            $table->enum('Tail_Lights', ['Good', 'Needs Attention']);
            $table->enum('Windows_Controls_Condition', ['Good', 'Needs Attention']);
            $table->enum('Push_Stop_Button', ['Good', 'Needs Attention']);
            $table->enum('Convertible_Operations', ['Good', 'Needs Attention']);
            $table->enum('Steering_Mounted_Controls', ['Good', 'Needs Attention']);
            $table->enum('Speedometer_Cluster', ['Good', 'Needs Attention']);
            $table->enum('Head_Lining', ['Good', 'Needs Attention']);
            $table->enum('Boot_Trunk_Area', ['Good', 'Needs Attention']);
            $table->enum('Music_Multimedia_System', ['Good', 'Needs Attention']);
            $table->enum('Headlights', ['Good', 'Needs Attention']);
            $table->enum('Sunroof_Condition', ['Good', 'Needs Attention']);
            $table->enum('Cruise_Control', ['Good', 'Needs Attention']);
            $table->enum('AC_Cooling', ['Good', 'Needs Attention']);

            $table->enum('Front_Left', ['Good', 'Needs Attention']);
            $table->enum('Front_Right', ['Good', 'Needs Attention']);
            $table->enum('Rear_Left', ['Good', 'Needs Attention']);
            $table->enum('Rear_Right', ['Good', 'Needs Attention']);
            $table->enum('Spare', ['Good', 'Needs Attention']);

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
        Schema::dropIfExists('cars');
    }
};
