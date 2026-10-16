<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('components')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('name', 150);
            $table->date('installed_at')->nullable();
            $table->unsignedInteger('lifespan_days')->nullable();
            $table->unsignedInteger('lifespan_hours')->nullable();
            $table->decimal('max_temperature', 6, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['enterprice_id', 'equipment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
