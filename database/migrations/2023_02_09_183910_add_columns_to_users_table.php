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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('bid_limit')->nullable()->after('password');
            $table->string('company')->nullable()->after('password');
            $table->boolean('is_verified')->default(false)->after('email_verified_at');
            $table->unsignedBigInteger('assigned_by')->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->DropColumn(['company', 'is_verified']);
        });
    }
};
