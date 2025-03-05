<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('redeemable', function (Blueprint $table) {
            $table->smallInteger('redeem_rate')->default(0);
            $table->string('redeem_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redeemable', function (Blueprint $table) {
            //
        });
    }
};
