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
        Schema::create('maisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs');
            $table->string('nom', 100);
            $table->text('adresse');
            $table->string('ville', 100);
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->integer('nombre_chambres');
            $table->boolean('salon')->default(true);
            $table->enum('type', ['simple', 'sanitaires']);
            $table->boolean('disponible')->default(true);
            $table->text('photos')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maisons');
    }
};
