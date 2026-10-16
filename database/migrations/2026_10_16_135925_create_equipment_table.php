<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->nullable()->constrained('equipment_types')->nullOnDelete();
            $table->string('code', 100);                 // campo "equipo" del JSON, ej. ESP32_ATP_SIM
            $table->string('name', 150)->nullable();
            $table->string('location', 150)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('last_reading_at')->nullable();
            $table->timestamps();

            $table->unique(['enterprice_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
