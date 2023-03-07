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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no');
            $table->string('name');
            $table->string('sap_code')->nullable();
            $table->string('badan_hukum', 20)->nullable();
            $table->string('npwp')->nullable();
            $table->integer('experience')->nullable(); // tahun berdiri
            $table->integer('jumlah_karyawan')->nullable();
            $table->foreignId('account_officer')->nullable();
            $table->string('status', 10)->default('active');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
