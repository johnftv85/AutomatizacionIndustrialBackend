<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('warehouse', 100)->default('MAIN');
            $table->decimal('quantity', 14, 3)->default(0);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['item_id', 'warehouse']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_levels');
    }
};
