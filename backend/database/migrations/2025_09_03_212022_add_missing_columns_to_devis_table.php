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
        Schema::table('devis', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('devis', 'montant')) {
                $table->decimal('montant', 15, 2);
            }
            if (!Schema::hasColumn('devis', 'statut')) {
                $table->enum('statut', ['en_attente', 'accepte', 'rejete', 'expire'])->default('en_attente');
            }
            if (!Schema::hasColumn('devis', 'client_id')) {
                $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('devis', 'compagnie_id')) {
                $table->foreignId('compagnie_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('devis', 'vehicule_id')) {
                $table->foreignId('vehicule_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('devis', 'periode_police')) {
                $table->integer('periode_police'); // 1, 3, 6, 12 mois
            }
            if (!Schema::hasColumn('devis', 'date_debut')) {
                $table->date('date_debut');
            }
            if (!Schema::hasColumn('devis', 'garanties_selectionnees')) {
                $table->json('garanties_selectionnees');
            }
            if (!Schema::hasColumn('devis', 'calcul_details')) {
                $table->json('calcul_details');
            }
            if (!Schema::hasColumn('devis', 'date_creation')) {
                $table->datetime('date_creation');
            }
            if (!Schema::hasColumn('devis', 'date_expiration')) {
                $table->datetime('date_expiration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['client_id', 'compagnie_id', 'vehicule_id']);
            $table->dropColumn([
                'montant', 'statut', 'client_id', 'compagnie_id', 'vehicule_id',
                'periode_police', 'date_debut', 'garanties_selectionnees',
                'calcul_details', 'date_creation', 'date_expiration'
            ]);
        });
    }
};
