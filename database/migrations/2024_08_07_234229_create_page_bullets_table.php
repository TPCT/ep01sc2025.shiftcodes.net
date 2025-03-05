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
        Schema::create('page_bullets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Page\Page::class)->index()->constrained()->cascadeOnDelete();
            $table->boolean('status')->default(1);
            $table->smallInteger('order')->unsigned();
            $table->timestamps();
        });

        Schema::create('page_bullets_lang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->index()->references('id')->on('page_bullets')->cascadeOnDelete();
            $table->string('language', 10)->nullable(false);
            $table->string('title')->nullable(false);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_bullets');
    }
};
