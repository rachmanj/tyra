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
        Schema::create('hazard_report_danger_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hazard_report_id')->constrained('hazard_reports')->cascadeOnDelete();
            $table->foreignId('danger_type_id')->constrained('danger_types')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_report_danger_type');
    }
};
