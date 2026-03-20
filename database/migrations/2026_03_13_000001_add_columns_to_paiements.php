<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('statut')->default('en_attente')->after('reference_transaction');
            $table->string('fedapay_id')->nullable()->after('statut');
            $table->timestamp('date_paiement')->nullable()->after('fedapay_id');
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['statut', 'fedapay_id', 'date_paiement']);
        });
    }
};
