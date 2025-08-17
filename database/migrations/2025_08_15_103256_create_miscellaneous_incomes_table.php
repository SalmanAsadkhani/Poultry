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
        Schema::create('miscellaneous_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeding_cycle_id')->constrained('breeding_cycles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('misc_categories_id')->constrained('miscellaneous_income_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->bigInteger('quantity');
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
        Schema::dropIfExists('miscellaneous_incomes');
    }
};
