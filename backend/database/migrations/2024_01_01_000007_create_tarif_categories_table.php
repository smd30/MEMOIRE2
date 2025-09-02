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
        Schema::create('tarif_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_category')->nullable();
            $table->integer('power_fiscal_min');
            $table->integer('power_fiscal_max');
            $table->decimal('base_rate_monthly', 8, 2);
            $table->decimal('coefficient_vol', 5, 2)->default(1.20);
            $table->decimal('coefficient_incendie', 5, 2)->default(0.80);
            $table->decimal('coefficient_bris', 5, 2)->default(1.00);
            $table->decimal('coefficient_defense', 5, 2)->default(1.10);
            $table->text('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['name', 'sub_category', 'power_fiscal_min', 'power_fiscal_max'], 'tarif_cat_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_categories');
    }
};
