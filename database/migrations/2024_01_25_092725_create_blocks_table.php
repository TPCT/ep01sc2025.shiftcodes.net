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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();

            $table->text('image')->nullable();
            $table->text('title');
            $table->text('second_title')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('features')->nullable();
            $table->text('buttons')->nullable();
            $table->text('bullets')->nullable();

            $table->foreignIdFor(\App\Models\Admin::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Dropdown\Dropdown::class)->index()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->timestamp('published_at');
            $table->boolean('status')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
