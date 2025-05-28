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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Isi announcement
            $table->date('start_date'); // Tanggal mulai tampil
            $table->integer('duration_days'); // Durasi dalam hari
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status announcement
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin yang membuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
