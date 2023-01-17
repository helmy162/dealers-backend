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
            $table->string('make', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('trim', 50)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedBigInteger('mileage')->nullable();
            $table->string('registered_emirates', 50)->nullable();
            $table->json('engine')->nullable();
            $table->string('interior_type', 50)->nullable();
            $table->string('body_type', 50)->nullable();
            $table->string('specification', 50)->nullable();
            $table->string('exterior_color', 50)->nullable();
            $table->string('interios_Color', 50)->nullable();
            $table->unsignedTinyInteger('keys')->nullable();
            $table->boolean('is_new')->nullable();
            $table->boolean('first_owner')->nullable();
            //history
            $table->string('service_history', 50)->nullable();
            $table->string('manuals', 29)->nullable();
            $table->boolean('warranty')->nullable();
            $table->boolean('ownership')->nullable();
            $table->string('accident_history', 29)->nullable();
            $table->boolean('bank_finance')->nullable();
            $table->string('car_history_comment')->nullable();

            //engine & transmission
            $table->string('Radiator_Condition', 29)->nullable();
            $table->string('Radiator_Fan', 29)->nullable();
            $table->string('Engine_Noise', 29)->nullable();
            $table->string('Axels', 29)->nullable();
            $table->string('Engine_Oil', 29)->nullable();
            $table->string('Engine_Belt', 29)->nullable();
            $table->string('Coolant', 29)->nullable();
            $table->string('Battery_Condition', 29)->nullable();
            $table->string('Engine_Idling', 29)->nullable();
            $table->string('Engine_Smoke', 29)->nullable();
            $table->string('Gear_Shifting', 29)->nullable();
            $table->string('Gear_Lever', 29)->nullable();
            $table->string('Exhaust', 29)->nullable();
            $table->string('Silencer', 29)->nullable();
            $table->string('Engine_Comment')->nullable();
            $table->boolean('engine_status')->default(0);

            //steering, suspension & brakes
            $table->string('Brake_Pads', 29)->nullable();
            $table->string('Brake_Disk', 29)->nullable();
            $table->string('Parking_Brake', 29)->nullable();
            $table->string('Suspension', 29)->nullable();
            $table->string('Shock_Absorber', 29)->nullable();
            $table->string('Steering_Operation', 29)->nullable();
            $table->string('Steering_Alignment', 29)->nullable();
            $table->string('Wheel_Alignment', 29)->nullable();
            $table->string('Steering_Comment')->nullable();
            $table->boolean('steering_status')->default(0);

            //interior, electricals & AC
            $table->string('Dashboard_Condition', 29)->nullable();
            $table->string('Steering_Controls', 29)->nullable();
            $table->string('Center_Console_Box', 29)->nullable();
            $table->string('Speedometer_Cluster', 29)->nullable();
            $table->string('Door_Trims', 29)->nullable();
            $table->string('Headliner', 29)->nullable();
            $table->string('Seat_Controller', 29)->nullable();
            $table->string('Boot_Trunk', 29)->nullable();
            $table->string('Central_Lock', 29)->nullable();
            $table->string('Music_System', 29)->nullable();
            $table->string('Navigation_Control', 29)->nullable();
            $table->string('Headlights', 29)->nullable();
            $table->string('Tail_Light', 29)->nullable();
            $table->string('Sunroof_Condition', 29)->nullable();
            $table->string('Windows_Control', 29)->nullable();
            $table->string('Cruise_Control', 29)->nullable();
            $table->string('Push_start', 29)->nullable();
            $table->string('AC_Cooling', 29)->nullable();
            $table->string('Convertible_Operations', 29)->nullable();
            $table->string('AC_Heating', 29)->nullable();
            $table->string('Interior_Comment')->nullable();
            $table->boolean('interior_status')->default(0);

            //specs
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
            $table->boolean("Aux_Audio_In")->nullable();
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
            $table->boolean("Power_Mirros")->nullable();
            $table->boolean("Power_Windows")->nullable();
            $table->boolean("Memory_Seats")->nullable();
            $table->boolean("View_Camera")->nullable();
            $table->boolean("Blind_Spot_Indicator")->nullable();
            $table->boolean("Anti_Lock_Brakes_ABS")->nullable();
            $table->boolean("Specs_Cruise_Control")->nullable();    
            $table->boolean("Power_Steering")->nullable();
            $table->boolean("Front_Airbags")->nullable();
            $table->boolean("Side_Airbags")->nullable();
            $table->string("Trip_Gears", 29)->nullable();
            $table->boolean("Night_Vision")->nullable();
            $table->boolean("Lift_Kit")->nullable();
            $table->boolean("Park_Assist")->nullable();
            $table->boolean("Adaptive_Suspension")->nullable();
            $table->boolean("Height_Control")->nullable();
            $table->boolean("Navigation_System")->nullable();
            $table->string("Drives", 29)->nullable();
            $table->string("Sunroof_Type", 29)->nullable();
            $table->boolean("N29_System")->nullable();
            $table->string("Wheels_Type", 29)->nullable();
            $table->boolean("Side_Steps")->nullable();
            $table->string("Convertible")->nullable();
            $table->string('Other_Features')->nullable();
            $table->boolean('specs_status')->default(0);
            
            //wheels
            $table->unsignedSmallInteger('Front_Right_Year')->nullable();
            $table->unsignedSmallInteger('Rear_Right_Year')->nullable();
            $table->unsignedSmallInteger('Front_Left_Year')->nullable();
            $table->unsignedSmallInteger('Rear_Left_Year')->nullable();
            $table->unsignedSmallInteger('Spare_Year')->nullable();
            $table->string('Wheels_Comment')->nullable();
            $table->boolean('wheels_status')->default(0);

            //exterior condition
            $table->json('markers')->nullable();
            $table->boolean('exterior_status')->default(0);

            //images;
            $table->json('images')->nullable();
            $table->boolean('images_status')->default(0);

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
