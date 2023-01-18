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
            $table->unsignedSmallInteger('Front_Right_Year')->nullable();
            $table->unsignedSmallInteger('Rear_Right_Year')->nullable();
            $table->unsignedSmallInteger('Front_Left_Year')->nullable();
            $table->unsignedSmallInteger('Rear_Left_Year')->nullable();
            $table->unsignedSmallInteger('Spare_Year')->nullable();
            $table->string('Wheels_Comment')->nullable();
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
