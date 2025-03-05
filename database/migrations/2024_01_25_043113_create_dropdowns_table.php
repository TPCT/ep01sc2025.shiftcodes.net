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
        Schema::create('dropdowns', function (Blueprint $table) {
            $table->id();

            $table->text('title');
            $table->text('description')->nullable();
            $table->text('image')->nullable();

            $table->foreignIdFor(\App\Models\Admin::class)->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('url')->nullable();
            $table->string('category');
            $table->boolean('status')->default(false);
            $table->timestamp('published_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdowns');
    }
};
