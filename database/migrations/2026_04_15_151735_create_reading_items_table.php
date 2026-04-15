<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('cover_url')->nullable();
            $table->enum('status', ['want_to_read', 'currently_reading', 'completed'])->default('want_to_read');
            $table->smallInteger('year_read')->nullable();
            $table->tinyInteger('rating')->nullable();   // 1–5
            $table->text('notes')->nullable();
            $table->string('buy_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_items');
    }
};
