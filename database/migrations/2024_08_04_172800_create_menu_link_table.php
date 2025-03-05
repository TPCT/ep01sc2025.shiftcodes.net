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
        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->references('id')->on('menus')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->bigInteger('parent_id')->default(null)->nullable();
            $table->unsignedBigInteger('sort')->default(0);
            $table->boolean('status')->default(true);
            $table->index(['menu_id', 'user_id', 'parent_id']);
            $table->timestamps();
        });

        Schema::create('menu_links_lang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->references('id')->on('menu_links')->cascadeOnDelete();
            $table->index(['parent_id']);
            $table->string('language', 10);
            $table->string('title');
            $table->string('link');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_links_lang');
        Schema::dropIfExists('menu_links');
    }
};
