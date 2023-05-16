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
            $table->date('warranty_exp_date')->nullable()->after('accumulated_hm'); // waranty expire date
            $table->integer('warranty_exp_hm')->nullable();
            $table->integer('last_hm_before_reset')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tyres', function (Blueprint $table) {
            $table->dropColumn('exp_hm');
        });
    }
};
