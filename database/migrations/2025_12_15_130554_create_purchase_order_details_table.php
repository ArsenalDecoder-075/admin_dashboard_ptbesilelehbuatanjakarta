<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id('pod_id');

            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('material_id');

            $table->decimal('quantity', 12, 2);
            $table->decimal('price', 12, 2);

            $table->foreign('po_id')
                  ->references('po_id')
                  ->on('purchase_orders')
                  ->onDelete('cascade');

            $table->foreign('material_id')
                  ->references('material_id')
                  ->on('raw_materials');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
