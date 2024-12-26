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
        Schema::create('saisielubrifiants', function (Blueprint $table) {
            $table->id();
            $table->date('du');
            $table->date('au');
            $table->foreignId('engin_id')->constrained('engins');
            $table->foreignId('lubrifiant_id')->constrained('lubrifiants');
            $table->double('qte')->min(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saisielubrifiants');
    }
};
