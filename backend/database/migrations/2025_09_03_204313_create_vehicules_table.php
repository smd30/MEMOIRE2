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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('marque_vehicule');
            $table->string('modele');
            $table->string('immatriculation')->unique();
            $table->enum('categorie', [
                'voiture_particuliere',
                'utilitaire_leger',
                'transport_commun',
                'poids_lourd',
                'moto',
                'vehicule_special',
                'vehicule_administratif'
            ]);
            $table->integer('puissance_fiscale');
            $table->date('date_mise_en_circulation');
            $table->decimal('valeur_vehicule', 15, 2);
            $table->decimal('valeur_venale', 15, 2);
            $table->string('carte_grise')->nullable();
            $table->string('numero_chassis')->unique();
            $table->enum('energie', ['essence', 'diesel', 'gaz', 'electricite']);
            $table->integer('places');
            $table->integer('age_vehicule')->nullable();
            $table->string('proprietaire_nom');
            $table->string('proprietaire_prenom');
            $table->text('proprietaire_adresse');
            $table->string('proprietaire_telephone');
            $table->string('proprietaire_email');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
