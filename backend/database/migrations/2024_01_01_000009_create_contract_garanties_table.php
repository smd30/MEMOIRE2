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
        Schema::create('contract_garanties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('garantie_id')->constrained()->onDelete('cascade');
            $table->decimal('coefficient', 5, 2);
            $table->decimal('premium', 10, 2);
            $table->timestamps();
            
            $table->unique(['contract_id', 'garantie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_garanties');
    }
};
