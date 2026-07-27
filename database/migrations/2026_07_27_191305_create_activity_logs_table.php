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
        Schema::create('activity_logs', function (Blueprint $table) {
            // L'utilisation qui a effectué l'action
            $table->id();

            // Description lisible de l'action
            $table->string('action');

            // Détails facultatifs
            $table->text('description')->nullable();

            // Adresse IP depuis laquelle l'action a été effectuée
            $table->string('ip_adress')->nullable();

            // Date et heure automatiques de l'action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
