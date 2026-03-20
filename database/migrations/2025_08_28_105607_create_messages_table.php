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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediteur_id')->constrained('utilisateurs');
            $table->foreignId('destinataire_id')->constrained('utilisateurs');
            $table->enum('logement_type', ['maison', 'appartement']);
            $table->integer('logement_id');
            $table->text('contenu');
            $table->timestamp('date_envoi')->useCurrent();
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
