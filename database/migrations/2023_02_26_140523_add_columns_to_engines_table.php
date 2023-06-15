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
        Schema::table('engines', function (Blueprint $table) {
            $table->string('Chassis')->nullable();
            $table->string('Chassis_Extension')->nullable();
            $table->boolean('Warning_Signal')->nullable();
            $table->string('Oil_Leaks')->nullable();
            $table->string('Water_Sladge')->nullable();
            $table->string('Shift_Interlock_Condition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engines', function (Blueprint $table) {
            $table->dropColumn([
                'Chassis',
                'Chassis_Extension',
                'Warning_Signal',
                'Oil_Leaks',
                'Water_Sladge',
                'Shift_Interlock_Condition',
            ]);
        });
    }
};
