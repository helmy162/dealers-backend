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
        Schema::create('engines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->string('Radiator_Condition')->nullable();
            $table->string('Radiator_Fan')->nullable();
            $table->string('Engine_Noise')->nullable();
            $table->string('Axels')->nullable();
            $table->string('Coolant')->nullable();
            $table->string('Engine_Idling')->nullable();
            $table->string('Engine_Smoke')->nullable();
            $table->string('Transmission_Condition')->nullable();
            $table->string('Silencer')->nullable();
            $table->string('Engine_Comment')->nullable();
            $table->string('Airbag')->nullable();
            $table->string('Engine_Group')->nullable();
            $table->string('Hoses_and_Pipes_Condition')->nullable();
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
        Schema::dropIfExists('engines');
    }
};
