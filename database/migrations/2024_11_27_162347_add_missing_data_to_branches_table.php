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
        Schema::table('branches', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Merchant\Merchant::class)->index()->after('id')->constrained()->cascadeOnDelete();
            $table->float('longitude', 8, 2)->nullable()->after('merchant_id');
            $table->float('latitude', 8, 2)->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
};
