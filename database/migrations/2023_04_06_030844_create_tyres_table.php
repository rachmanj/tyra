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
        Schema::create('tyres', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->foreignId('size_id')->nullable();
            $table->string('TyreSize')->nullable(); // migration
            $table->foreignId('brand_id')->nullable();
            $table->string('TyreManufName')->nullable(); // migration
            $table->foreignId('pattern_id')->nullable();
            $table->string('TyrePattern')->nullable(); // migration
            $table->string('po_no')->nullable();
            $table->string('do_no')->nullable();
            $table->date('do_date')->nullable();
            $table->integer('otd')->nullable(); // Original Usable Tread Depth
            $table->integer('pressure')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->string('TyreVendor')->nullable(); // migration
            $table->integer('price')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('current_project')->nullable();
            $table->integer('hours_target')->nullable();
            $table->string('TyreCPH')->nullable(); // migration
            $table->integer('accumulated_hm')->default(0)->after('hours_target');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(true);
            $table->date('waranty_exp_date')->nullable(); // waranty expire date
            $table->foreignId('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyres');
    }
};
