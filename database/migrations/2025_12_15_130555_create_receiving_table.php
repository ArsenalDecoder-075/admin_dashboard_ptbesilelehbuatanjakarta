<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receiving', function (Blueprint $table) {
            $table->id('receiving_id');
            $table->foreignId('po_id')
                  ->constrained('purchase_orders', 'po_id')
                  ->cascadeOnDelete();
            $table->string('receiving_number', 50)->unique();
            $table->date('receiving_date');
            $table->string('received_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving');
    }
};
