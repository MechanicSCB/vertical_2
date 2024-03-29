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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code');

            $table->foreignId('category_id');
            $table->string('vendor')->nullable();
            $table->string('model')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('price')->nullable();
            $table->integer('old_price')->nullable();
            $table->text('description')->nullable();
            $table->jsonb('params')->nullable();

            $table->integer('image_count')->nullable();
            $table->integer('reply_count')->nullable();
            $table->string('availability')->nullable();
            $table->float('order')->nullable();

            $table->timestamps();

            $table->fullText('description')->language('russian');
            $table->fullText('name')->language('russian');
            $table->fullText('params')->language('russian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
