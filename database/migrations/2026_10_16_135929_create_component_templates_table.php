<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('component_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')->constrained('equipment_types')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('component_templates')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('name', 150);
            $table->unsignedInteger('lifespan_days')->nullable();
            $table->unsignedInteger('lifespan_hours')->nullable();
            $table->decimal('max_temperature', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('component_templates');
    }
};
