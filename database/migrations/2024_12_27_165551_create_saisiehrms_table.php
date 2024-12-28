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
        Schema::create('saisiehrms', function (Blueprint $table) {
            $table->id();
            $table->date('datesaisie');
            $table->foreignId('engin_id')->constrained('engins');
            $table->foreignId('site_id')->constrained('sites');
            $table->double('hrm')->min(0)->max(24);
            $table->double('nho')->min(0)->max(24)->default(24);
            $table->timestamps();

            $table->unique(['datesaisie', 'engin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saisiehrms');
    }
};