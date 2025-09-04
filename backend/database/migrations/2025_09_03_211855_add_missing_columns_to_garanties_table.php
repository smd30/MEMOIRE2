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
        Schema::table('garanties', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('garanties', 'obligatoire')) {
                $table->boolean('obligatoire')->default(false);
            }
            if (!Schema::hasColumn('garanties', 'tarification_type')) {
                $table->enum('tarification_type', ['fixe', 'pourcentage', 'forfait'])->default('fixe');
            }
            if (!Schema::hasColumn('garanties', 'tarification_config')) {
                $table->json('tarification_config')->nullable();
            }
            if (!Schema::hasColumn('garanties', 'statut')) {
                $table->enum('statut', ['active', 'inactive'])->default('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garanties', function (Blueprint $table) {
            $table->dropColumn(['obligatoire', 'tarification_type', 'tarification_config', 'statut']);
        });
    }
};
