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
        Schema::create('hazard_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 20)->nullable();
            $table->foreignId('to_department_id');
            $table->foreignId('created_by');
            $table->foreignId('updated_by')->nullable();
            $table->string('project_code', 20)->nullable();
            $table->string('category', 20)->nullable();
            $table->foreignId('danger_type_id')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('closed_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_reports');
    }
};
