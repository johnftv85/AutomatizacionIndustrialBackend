<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->foreignId('component_id')->nullable()->constrained('components')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('origin', 20)->default('manual');     // manual, time, reading
            $table->string('status', 20)->default('proposed');   // proposed, approved, in_progress, done, cancelled
            $table->date('tentative_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->string('erp_reference', 100)->nullable();    // requisición / orden en el ERP
            $table->timestamps();

            $table->index(['enterprice_id', 'status', 'tentative_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
