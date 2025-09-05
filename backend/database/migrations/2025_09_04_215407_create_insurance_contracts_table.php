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
        Schema::create('insurance_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicule_id')->constrained('vehicules')->onDelete('cascade');
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade');
            $table->string('numero_police')->unique();
            $table->string('numero_attestation')->unique();
            $table->string('cle_securite');
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->integer('periode_police');
            $table->json('garanties_selectionnees');
            $table->decimal('prime_rc', 10, 2)->default(0);
            $table->decimal('garanties_optionnelles', 10, 2)->default(0);
            $table->decimal('accessoires_police', 10, 2)->default(0);
            $table->decimal('prime_nette', 10, 2)->default(0);
            $table->decimal('taxes_tuca', 10, 2)->default(0);
            $table->decimal('prime_ttc', 10, 2)->default(0);
            $table->enum('statut', ['actif', 'expire', 'resilie'])->default('actif');
            $table->datetime('date_souscription');
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['user_id']);
            $table->index(['statut']);
            $table->index(['numero_police']);
            $table->index(['numero_attestation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_contracts');
    }
};
