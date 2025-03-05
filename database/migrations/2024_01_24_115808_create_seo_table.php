<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->foreignId(\App\Models\Admin::class)->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->text('title')->nullable();
            $table->text('image')->nullable();
            $table->text('author')->nullable();
            $table->string('robots')->nullable();
            $table->text('canonical_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
