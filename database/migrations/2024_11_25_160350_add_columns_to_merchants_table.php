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
        Schema::table('merchants', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Category\Category::class)->after('id')->constrained()->cascadeOnDelete();
            $table->string('name')->after('category_id');
            $table->string('country_code')->default("+962")->after('name');
            $table->string('phone')->nullable()->after('country_code');
            $table->string('email')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            //
        });
    }
};
