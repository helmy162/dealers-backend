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
            $table->unsignedBigInteger('inspector_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            //general information
            $table->string('make', 20)->nullable();
            $table->string('model', 20)->nullable();
            $table->string('trim', 20)->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedBigInteger('mileage')->nullable();
            $table->string('registered_emirates', 20)->nullable();
            $table->json('engine')->nullable();
            $table->string('interior_type', 35)->nullable();
            $table->string('body_type', 20)->nullable();
            $table->string('specification', 20)->nullable();
            $table->string('exterior_color', 20)->nullable();
            $table->string('interios_Color', 20)->nullable();
            $table->unsignedInteger('keys')->nullable();
            $table->boolean('is_new')->nullable();
            $table->boolean('first_owner')->nullable();
            //history
            $table->string('service_history', 20)->nullable();
            $table->string('manuals', 20)->nullable();
            $table->boolean('warranty')->nullable();
            $table->boolean('ownership')->nullable();
            $table->string('accident_history', 20)->nullable();
            $table->boolean('bank_finance')->nullable();
            $table->string('car_history_comment')->nullable();

            //engine & transmission
            $table->string('Radiator_Condition', 20)->nullable();
            $table->string('Engine_Noise', 20)->nullable();
            $table->string('Engine_Belts', 20)->nullable();
            $table->string('Engine_Idling', 20)->nullable();
            $table->string('Engine_Oil', 20)->nullable();
            $table->string('Engine_Oil_Level', 20)->nullable();
            $table->string('Engine_Oil_Pressure', 20)->nullable();
            $table->string('Battery_Condition', 20)->nullable();
            $table->string('Engine_Smoke', 20)->nullable();
            $table->string('Gear_Lever', 20)->nullable();
            $table->string('4WD_System_Condition', 20)->nullable();
            $table->string('Radiator_Fan', 20)->nullable();
            $table->string('Coolant', 20)->nullable();
            $table->string('Coolant_Level', 20)->nullable();
            $table->string('Gear_Shifting', 20)->nullable();
            $table->string('Silencer', 20)->nullable();
            $table->string('Axels', 20)->nullable();
            $table->string('Exhaust', 20)->nullable();
            $table->string('engine_comment')->nullable();

            //steering, suspension & brakes
            $table->string('Brake_Pads', 20)->nullable();
            $table->string('Parking_Brake_Operation', 20)->nullable();
            $table->string('Shock_Absorber_Operation', 20)->nullable();
            $table->string('Steering_Alignment', 20)->nullable();
            $table->string('Brake_Discs_Or_Lining', 20)->nullable();
            $table->string('Suspension', 20)->nullable();
            $table->string('Steering_Operation', 20)->nullable();
            $table->string('Wheel_Alignment', 20)->nullable();
            $table->string('steering_comment')->nullable();

            //interior, electricals & AC
            $table->string('Dashboard_Condition', 20)->nullable();
            $table->string('Air_Conditioner', 20)->nullable();
            $table->string('Center_Console_Box', 20)->nullable();
            $table->string('Door_Trim_Panels', 20)->nullable();
            $table->string('Seat_Controls', 20)->nullable();
            $table->string('Central_Lock_Operation', 20)->nullable();
            $table->string('Navigation_Control', 20)->nullable();
            $table->string('Tail_Lights', 20)->nullable();
            $table->string('Windows_Controls_Condition', 20)->nullable();
            $table->string('Push_Stop_Button', 20)->nullable();
            $table->string('Convertible_Operations', 20)->nullable();
            $table->string('Steering_Mounted_Controls', 20)->nullable();
            $table->string('Speedometer_Cluster', 20)->nullable();
            $table->string('Head_Lining', 20)->nullable();
            $table->string('Boot_Trunk_Area', 20)->nullable();
            $table->string('Music_Multimedia_System', 20)->nullable();
            $table->string('Headlights', 20)->nullable();
            $table->string('Sunroof_Condition', 20)->nullable();
            $table->string('Cruise_Control', 20)->nullable();
            $table->string('AC_Cooling', 20)->nullable();

            //wheels
            $table->string('Front_Left_Year', 20)->nullable();
            $table->string('Front_Right_Year', 20)->nullable();
            $table->string('Rear_Left_Year', 20)->nullable();
            $table->string('Rear_Right_Year', 20)->nullable();
            $table->string('Spare_Year', 20)->nullable();

            //specs
            $table->boolean("Fog_Lights")->nullable();
            $table->boolean("Rear_Spoiler")->nullable();
            $table->boolean("Roof_Rails")->nullable();
            $table->boolean("Roof_Rack")->nullable();
            $table->boolean("Sunroof")->nullable();
            $table->boolean("TowBar")->nullable();
            $table->boolean("Tinted_Windows")->nullable();
            $table->boolean("Rear_Wiper")->nullable();
            $table->boolean("Rear_Window_Defroster")->nullable();
            $table->boolean("Rear_Window_Washer")->nullable();
            $table->boolean("Rear_Window_Wiper")->nullable();
            $table->boolean("Spoiler")->nullable();
            $table->boolean("Sunroof_Moonroof")->nullable();
            $table->boolean("Tinted_Glass")->nullable();
            $table->boolean("Tow_Package")->nullable();
            $table->boolean("Tow_Hitch")->nullable();
            $table->boolean("Tow_Hooks")->nullable();
            $table->boolean("Tow_Package_Hitch")->nullable();
            $table->boolean("Tow_Package_Hitch_Hooks")->nullable();
            $table->boolean("Tow_Package_Hooks")->nullable();
            $table->boolean("Tow_Package_Hitch_Tow_Hooks")->nullable();
            $table->boolean("Premium_Sound_System")->nullable();
            $table->boolean("Heads_Up_Display")->nullable();
            $table->boolean("Aux_Audio_In")->nullable();
            $table->boolean("Bluetooth_System")->nullable();
            $table->boolean("Climate_Control")->nullable();
            $table->boolean("Keyless_Entry")->nullable();
            $table->boolean("Keyless_Start")->nullable();
            $table->boolean("Leather_Seats")->nullable();
            $table->boolean("Racing_Seats")->nullable();
            $table->boolean("Cooled_Seats")->nullable();
            $table->boolean("HeatedSeats")->nullable();
            $table->boolean("Power_Seats")->nullable();
            $table->boolean("Power_Locks")->nullable();
            $table->boolean("Power_Mirrors")->nullable();
            $table->boolean("Power_Windows")->nullable();
            $table->boolean("Memory_Seats")->nullable();
            $table->boolean("View_Camera")->nullable();
            $table->boolean("Blind_Spot_Indicator")->nullable();
            $table->boolean("Anti_Lock_Brakes_ABS")->nullable();
            $table->boolean("Adaptive_Cruise_Control")->nullable();
            $table->boolean("Power_Steering")->nullable();
            $table->boolean("Front_Airbags")->nullable();
            $table->boolean("Side_Airbags")->nullable();
            $table->boolean("Triptonic_Gears")->nullable();
            $table->boolean("Night_Vision")->nullable();
            $table->boolean("Carbon_Fiber_Interior")->nullable();
            $table->boolean("Lane_Change_Assist")->nullable();
            $table->boolean("Headlamp_Washer")->nullable();
            $table->boolean("Ceramic_Brakes")->nullable();
            $table->boolean("Lift_Kit")->nullable();
            $table->boolean("Park_Assist")->nullable();
            $table->boolean("Adaptive_Suspension")->nullable();
            $table->boolean("Height_Control")->nullable();
            $table->boolean("Navigation_System")->nullable();
            $table->string("Drive", 20)->nullable();
            $table->string("Sunroof_Type", 20)->nullable();
            $table->boolean("N2O_System")->nullable();
            $table->string("Wheels_Type", 20)->nullable();
            $table->boolean("Side_Steps")->nullable();
            $table->boolean("Convertible")->nullable();

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
