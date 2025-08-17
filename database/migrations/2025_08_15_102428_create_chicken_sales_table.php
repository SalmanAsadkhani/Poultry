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
        Schema::create('chicken_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeding_cycle_id')->constrained('breeding_cycles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('chicken_categories_id')->constrained('chicken_sales_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->bigInteger('quantity');
            $table->decimal('weight', 2, 1);
            $table->bigInteger('price')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chicken_sales');
    }
};
