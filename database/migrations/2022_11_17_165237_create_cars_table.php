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
            $table->unsignedBigInteger('engine_id')->nullable();
            $table->unsignedBigInteger('steering_id')->nullable();
            $table->unsignedBigInteger('interior_id')->nullable();
            $table->unsignedBigInteger('specs_id')->nullable();
            $table->unsignedBigInteger('wheels_id')->nullable();
            $table->unsignedBigInteger('exterior_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            //general information
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('trim')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedBigInteger('mileage')->nullable();
            $table->string('registered_emirates')->nullable();
            $table->json('engine')->nullable();
            $table->string('interior_type')->nullable();
            $table->string('body_type')->nullable();
            $table->string('specification')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('interios_Color')->nullable();
            $table->unsignedTinyInteger('keys')->nullable();
            $table->boolean('is_new')->nullable();
            $table->boolean('first_owner')->nullable();
            
            //history
            $table->string('service_history')->nullable();
            $table->string('manuals')->nullable();
            $table->boolean('warranty')->nullable();
            $table->boolean('ownership')->nullable();
            $table->string('accident_history')->nullable();
            $table->boolean('bank_finance')->nullable();
            $table->string('car_history_comment')->nullable();

            //images
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
