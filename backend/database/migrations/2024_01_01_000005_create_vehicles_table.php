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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plate_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->integer('power_fiscal');
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('fuel_type');
            $table->string('color');
            $table->integer('mileage');
            $table->text('additional_features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
