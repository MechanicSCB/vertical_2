<?php

use App\Models\Node;
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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();

            $table->string('path');
            $table->unique(['path']);

            $table->string('parent_path')->nullable();
            $table->foreign('parent_path')
                ->references('path')
                ->on('nodes')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $separator = Node::$separator;
            $table->integer('level')->storedAs(("length(path) - length(replace(path, '$separator', '')) "));
            $table->bigInteger('order')->default(9999);
            $table->timestamps();

            $table->unique(['parent_path', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
