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
            if (!Schema::hasColumn('garanties', 'display_name')) {
                $table->string('display_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garanties', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
};
