<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inv_saldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bodega_id')->constrained('bodegas');
            $table->foreignId('producto_id')->constrained('productos');
            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            // Índice único para evitar duplicidad de registros por bodega/artículo
            $table->unique(['bodega_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('inv_saldos');
    }
};
