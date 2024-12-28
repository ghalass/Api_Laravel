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
        Schema::create('saisiehims', function (Blueprint $table) {
            $table->id();
            $table->date('datesaisie');
            $table->foreignId('engin_id')->constrained('engins');
            $table->foreignId('panne_id')->constrained('pannes');
            $table->double('him')->min(0)->max(24);
            $table->integer('ni')->min(0);
            $table->timestamps();

            $table->unique(['datesaisie', 'engin_id', 'panne_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saisiehims');
    }
};