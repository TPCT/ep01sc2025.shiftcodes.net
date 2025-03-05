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
        Schema::create('slider_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Admin::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Slider\Slider::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('link')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_slides');
    }
};
