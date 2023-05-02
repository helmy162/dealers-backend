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
        Schema::create('wheels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedSmallInteger('FrontRight')->nullable();
            $table->unsignedSmallInteger('RearRight')->nullable();
            $table->unsignedSmallInteger('FrontLeft')->nullable();
            $table->unsignedSmallInteger('RearLeft')->nullable();
            $table->unsignedSmallInteger('Spare_Tyre')->nullable();
            $table->string('rim_type')->nullable();
            $table->string('rim_condition')->nullable();
            $table->string('Tyres_Comment')->nullable();
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
        Schema::dropIfExists('wheels');
    }
};
