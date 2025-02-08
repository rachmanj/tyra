<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('model_name');
            $table->unsignedBigInteger('model_id');
            $table->text('activity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}; 