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
        Schema::create('block_features', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Admin::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Block\Block::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('block_features_lang', function (Blueprint $table){
            $table->id();
            $table->foreignId('parent_id')->index()->references('id')->on('block_features')->cascadeOnDelete();
            $table->string('language', 10);
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_features');
        Schema::dropIfExists('block_features_lang');
    }
};
