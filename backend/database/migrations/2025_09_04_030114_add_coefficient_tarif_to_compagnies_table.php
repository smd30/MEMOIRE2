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
            $table->decimal('coefficient_tarif', 3, 2)->default(1.00)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compagnies', function (Blueprint $table) {
            $table->dropColumn('coefficient_tarif');
        });
    }
};
