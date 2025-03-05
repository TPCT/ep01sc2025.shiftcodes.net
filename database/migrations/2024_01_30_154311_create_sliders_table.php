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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Admin::class)->index()->constrained()->cascadeOnDelete();
            $table->string('category')->nullable(false);
            $table->text('title')->nullable();
            $table->text('second_title')->nullable();
            $table->text('description')->nullable();
            $table->text('bullets')->nullable(false);
            $table->text('slides')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->boolean('status')->default(1);
            $table->timestamp('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
