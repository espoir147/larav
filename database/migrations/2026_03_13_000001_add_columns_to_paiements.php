<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            if (!Schema::hasColumn('paiements', 'statut')) {
                $table->string('statut')->default('en_attente')->after('reference_transaction');
            }
            if (!Schema::hasColumn('paiements', 'fedapay_id')) {
                $table->string('fedapay_id')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('paiements', 'date_paiement')) {
                $table->timestamp('date_paiement')->nullable()->after('fedapay_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(
                array_filter(
                    ['statut', 'fedapay_id', 'date_paiement'],
                    fn($col) => Schema::hasColumn('paiements', $col)
                )
            );
        });
    }
};
