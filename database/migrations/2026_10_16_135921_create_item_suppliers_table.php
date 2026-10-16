<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('supplier_code', 100)->nullable();
            $table->decimal('price', 14, 2)->nullable();
            $table->char('currency', 3)->default('COP');
            $table->unsignedSmallInteger('lead_time_days')->nullable();
            $table->boolean('preferred')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['item_id', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_suppliers');
    }
};
