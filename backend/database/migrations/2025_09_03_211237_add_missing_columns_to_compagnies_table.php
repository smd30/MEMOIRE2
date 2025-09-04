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
        Schema::table('compagnies', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('compagnies', 'api_url')) {
                $table->string('api_url')->nullable();
            }
            if (!Schema::hasColumn('compagnies', 'cle_api')) {
                $table->string('cle_api')->nullable();
            }
            if (!Schema::hasColumn('compagnies', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('compagnies', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('compagnies', 'tarification_config')) {
                $table->json('tarification_config')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compagnies', function (Blueprint $table) {
            $table->dropColumn(['api_url', 'cle_api', 'logo', 'description', 'tarification_config']);
        });
    }
};
