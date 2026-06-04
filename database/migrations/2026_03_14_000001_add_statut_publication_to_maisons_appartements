<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maisons', function (Blueprint $table) {
            if (!Schema::hasColumn('maisons', 'statut_publication')) {
                $table->string('statut_publication')->default('en_attente')->after('disponible');
            }
        });

        Schema::table('appartements', function (Blueprint $table) {
            if (!Schema::hasColumn('appartements', 'statut_publication')) {
                $table->string('statut_publication')->default('en_attente')->after('disponible');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maisons', function (Blueprint $table) {
            if (Schema::hasColumn('maisons', 'statut_publication')) {
                $table->dropColumn('statut_publication');
            }
        });

        Schema::table('appartements', function (Blueprint $table) {
            if (Schema::hasColumn('appartements', 'statut_publication')) {
                $table->dropColumn('statut_publication');
            }
        });
    }
};
