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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs');
            $table->foreignId('maison_id')->nullable()->constrained('maisons');
            $table->foreignId('appartement_id')->nullable()->constrained('appartements');
            $table->decimal('montant', 10, 2);
            $table->string('mois_paye', 20);
            $table->integer('annee_paye');
            $table->foreignId('operateur_id')->constrained('operateurs_gsm');
            $table->string('reference_transaction', 100);
            $table->timestamp('date_paiement')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
