<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receiving_details', function (Blueprint $table) {
            $table->id('rd_id');
            $table->foreignId('receiving_id')
                  ->constrained('receiving', 'receiving_id')
                  ->cascadeOnDelete();
            $table->foreignId('material_id')
                  ->constrained('raw_materials', 'material_id')
                  ->restrictOnDelete();
            $table->decimal('quantity_received', 12, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_details');
    }
};
