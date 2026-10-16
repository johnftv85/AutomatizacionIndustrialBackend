<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprice_id')->constrained('enterprice')->cascadeOnDelete();
            $table->string('erp_code', 100);
            $table->string('description', 255);
            $table->string('unit', 20)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['enterprice_id', 'erp_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
