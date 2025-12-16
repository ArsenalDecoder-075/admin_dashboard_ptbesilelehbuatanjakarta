<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id('material_id');
            $table->string('material_name', 150);
            $table->enum('material_type', [
                'Logam Ringan',
                'Baja',
                'Plastik/Resin',
                'Lainnya'
            ]);
            $table->string('unit', 50);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
