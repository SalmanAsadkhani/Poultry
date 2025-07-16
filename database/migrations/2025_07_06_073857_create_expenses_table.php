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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeding_cycle_id')->constrained('breeding_cycles')->cascadeOnDelete();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->string('name');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
