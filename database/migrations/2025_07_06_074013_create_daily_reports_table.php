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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeding_cycle_id')->constrained('breeding_cycles')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->bigInteger('days_number');
            $table->bigInteger('mortality_count')->nullable();
            $table->bigInteger('total_mortality')->nullable();
            $table->string('description')->nullable();
            $table->string('feed_type')->nullable();
            $table->string('actions_taken')->nullable();
            $table->bigInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
