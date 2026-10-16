<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['enterprice_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_types');
    }
};
