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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('unit_no')->nullable();
            $table->integer('position')->nullable();
            $table->date('on_date')->nullable();
            $table->integer('on_hm')->nullable();
            $table->integer('on_rtd1')->nullable();
            $table->integer('on_rtd2')->nullable();
            $table->date('off_date')->nullable();
            $table->integer('off_rtd1')->nullable();
            $table->integer('off_rtd2')->nullable();
            $table->integer('lifetime')->nullable(); // lifetime = off_hm - on_hm
            $table->string('project')->nullable();
            $table->foreignId('removal_reason_id')->nullable();
            $table->string('action')->nullable();
            $table->foreignId('last_updated_by')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
