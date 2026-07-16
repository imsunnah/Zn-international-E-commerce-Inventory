<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_builder_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('cpu'); // Lucide icon name
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->enum('group', ['core', 'peripherals'])->default('core');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_builder_categories');
    }
};
