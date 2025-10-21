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
        Schema::table('tyres', function (Blueprint $table) {
            $table->enum('inactive_reason', ['Scrap', 'Breakdown', 'Repair', 'Consignment', 'Rotable', 'Spare'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tyres', function (Blueprint $table) {
            $table->enum('inactive_reason', ['Scrap', 'Breakdown', 'Repair', 'Consignment', 'Rotable'])->nullable()->change();
        });
    }
};
