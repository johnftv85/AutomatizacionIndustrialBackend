<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->unique()->constrained('enterprice')->cascadeOnDelete();
            $table->string('type', 50);                  // odoo, sap, siesa, csv...
            $table->string('base_url')->nullable();
            $table->text('credentials')->nullable();     // cifradas desde el modelo
            $table->boolean('active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_connections');
    }
};
