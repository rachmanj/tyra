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
            $table->foreignId('tyre_id');
            $table->date('date')->nullable();
            $table->string('unit_no')->nullable();
            $table->enum('tx_type', ['ON', 'OFF'])->nullable(); // ON = installation, OFF = removal
            $table->integer('position')->nullable();
            $table->integer('hm')->nullable();
            $table->integer('rtd1')->nullable();
            $table->integer('rtd2')->nullable();
            // $table->date('off_date')->nullable();
            // $table->integer('off_rtd1')->nullable();
            // $table->integer('off_rtd2')->nullable();
            // $table->integer('lifetime')->nullable(); // lifetime = off_hm - on_hm
            $table->string('project')->nullable();
            $table->foreignId('removal_reason_id')->nullable();
            $table->string('action')->nullable();
            $table->string('remark')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
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
