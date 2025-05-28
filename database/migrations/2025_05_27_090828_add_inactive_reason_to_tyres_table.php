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
            $table->enum('inactive_reason', ['Scrap', 'Breakdown', 'Repair'])->nullable()->after('is_active');
            $table->datetime('inactive_date')->nullable()->after('inactive_reason');
            $table->text('inactive_notes')->nullable()->after('inactive_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tyres', function (Blueprint $table) {
            $table->dropColumn(['inactive_reason', 'inactive_date', 'inactive_notes']);
        });
    }
};
