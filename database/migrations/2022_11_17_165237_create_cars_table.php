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
            $table->unsignedBigInteger('details_id')->nullable();
            $table->unsignedBigInteger('history_id')->nullable();
            $table->unsignedBigInteger('engine_id')->nullable();
            $table->unsignedBigInteger('steering_id')->nullable();
            $table->unsignedBigInteger('interior_id')->nullable();
            $table->unsignedBigInteger('specs_id')->nullable();
            $table->unsignedBigInteger('wheels_id')->nullable();
            $table->unsignedBigInteger('exterior_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
