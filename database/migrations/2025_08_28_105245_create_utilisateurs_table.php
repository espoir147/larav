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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('email', 100)->unique();
            $table->string('indicatif_pays', 5)->nullable();
            $table->string('telephone', 10);
            $table->date('date_naissance')->nullable();
            $table->string('mot_de_passe');
            $table->enum('type', ['client', 'proprietaire', 'admin'])->default('client');
            $table->enum('statut', ['en_attente', 'actif', 'rejete', 'bloque'])->default('en_attente');
            $table->string('photo_profil')->nullable();
            $table->timestamp('date_inscription')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
