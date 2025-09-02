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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->json('vehicle_info');
            $table->json('selected_garanties');
            $table->integer('duration_months');
            $table->decimal('base_premium', 10, 2);
            $table->decimal('total_premium', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->enum('status', ['draft', 'sent', 'accepted', 'expired'])->default('draft');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
