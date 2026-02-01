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
        Schema::create('product_variant_item_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_item_id')
                ->constrained('product_variant_items')
                ->cascadeOnDelete();

            $table->foreignId('child_item_id')
                ->constrained('product_variant_items')
                ->cascadeOnDelete();

            $table->unique(
                ['parent_item_id', 'child_item_id'],
                'pvi_parent_child_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_item_relations');
    }
};
