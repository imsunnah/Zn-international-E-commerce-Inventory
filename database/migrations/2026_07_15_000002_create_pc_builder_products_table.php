<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_builder_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_builder_category_id')->constrained('pc_builder_categories')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('wattage')->default(0);
            $table->timestamps();

            $table->unique(['pc_builder_category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_builder_products');
    }
};
