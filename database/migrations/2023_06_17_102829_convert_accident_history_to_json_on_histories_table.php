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
        // Add a new JSON column to store the converted accident history
        Schema::table('histories', function (Blueprint $table) {
            $table->json('new_accident_history')->after('warranty')->nullable();
        });

        // Iterate over each record and convert the accident history to JSON
        $histories = DB::table('histories')->select('id', 'accident_history')->get();

        foreach ($histories as $history) {
            $accidentHistory = ! empty($history->accident_history) ? json_encode([$history->accident_history]) : json_encode([]);
            DB::table('histories')->where('id', $history->id)->update(['new_accident_history' => $accidentHistory]);
        }

        // Remove the old accident_history column
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn('accident_history');
        });

        // Rename the new_accident_history column to accident_history
        Schema::table('histories', function (Blueprint $table) {
            $table->renameColumn('new_accident_history', 'accident_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the column rename
        Schema::table('histories', function (Blueprint $table) {
            $table->renameColumn('accident_history', 'new_accident_history');
        });

        // Add back the old accident_history column as string type
        Schema::table('histories', function (Blueprint $table) {
            $table->string('accident_history')->after('warranty')->nullable();
        });

        // Iterate over each record and convert the accident history back to a string
        $histories = DB::table('histories')->select('id', 'new_accident_history')->get();

        foreach ($histories as $history) {
            $accidentHistory = json_decode($history->new_accident_history, true)[0];
            DB::table('histories')->where('id', $history->id)->update(['accident_history' => $accidentHistory]);
        }

        // Remove the new_accident_history column
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn('new_accident_history');
        });
    }
};
