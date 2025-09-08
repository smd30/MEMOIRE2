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
        Schema::create('contract_renewals', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('contrat_id')->constrained('insurance_contracts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicule_id')->constrained('vehicules')->onDelete('cascade');
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade');
            
            // Informations du contrat précédent
            $table->string('numero_police_precedent')->nullable();
            
            // Informations du nouveau contrat
            $table->string('numero_police_nouveau')->unique();
            $table->string('numero_attestation_nouveau')->unique();
            $table->string('cle_securite_nouveau');
            $table->dateTime('date_debut_nouveau');
            $table->dateTime('date_fin_nouveau');
            $table->integer('periode_police'); // 1, 3, 6, 12 mois
            
            // Garanties et primes
            $table->json('garanties_selectionnees');
            $table->decimal('prime_rc', 10, 2);
            $table->decimal('garanties_optionnelles', 10, 2);
            $table->decimal('accessoires_police', 10, 2);
            $table->decimal('prime_nette', 10, 2);
            $table->decimal('taxes_tuca', 10, 2);
            $table->decimal('prime_ttc', 10, 2);
            
            // Statut et dates
            $table->enum('statut', ['en_attente', 'approuve', 'rejete', 'renouvele', 'annule'])->default('en_attente');
            $table->dateTime('date_demande');
            $table->dateTime('date_renouvellement')->nullable();
            
            // Motif et observations
            $table->enum('motif_renouvellement', [
                'expiration_normale',
                'changement_garanties',
                'changement_vehicule',
                'changement_compagnie',
                'negociation_prime',
                'autre'
            ]);
            $table->text('observations')->nullable();
            
            // Évolution de la prime
            $table->decimal('prime_precedente', 10, 2)->nullable();
            $table->decimal('evolution_prime', 10, 2)->nullable();
            $table->decimal('pourcentage_evolution', 5, 2)->nullable();
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['user_id', 'statut']);
            $table->index(['contrat_id']);
            $table->index(['date_demande']);
            $table->index(['statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_renewals');
    }
};
