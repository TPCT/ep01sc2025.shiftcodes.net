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
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_branch_id_foreign');
            $table->dropIndex('offers_branch_id_index');
            $table->dropColumn('branch_id');
        });

        Schema::table('offers_lang', function (Blueprint $table) {
            $table->string('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
