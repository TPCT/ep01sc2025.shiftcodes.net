<?php

use App\Models\Branch\Branch;
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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(\App\Models\Merchant\Merchant::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('offers_lang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->index()->references('id')->on('offers')->cascadeOnDelete();
            $table->string('language', 10)->index()->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('details')->nullable(false);
            $table->string('description')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
