<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hourly_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->dateTime('hour');                    // inicio de la hora
            $table->unsignedSmallInteger('samples');
            $table->decimal('temperature_min', 6, 2)->nullable();
            $table->decimal('temperature_max', 6, 2)->nullable();
            $table->decimal('temperature_avg', 6, 2)->nullable();
            $table->decimal('ads_voltage_avg', 6, 3)->nullable();
            $table->decimal('dac_voltage_avg', 6, 2)->nullable();
            $table->smallInteger('rssi_avg')->nullable();
            $table->unsignedBigInteger('uptime_max')->nullable();
            $table->timestamps();

            $table->unique(['equipment_id', 'hour']);
            $table->index(['enterprice_id', 'hour']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hourly_readings');
    }
};
