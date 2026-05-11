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
        Schema::create('bodega_producto_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bodega_id')->constrained('bodegas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            
            // Parámetros de inventario
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->decimal('stock_maximo', 12, 2)->default(0);
            $table->decimal('punto_reorden', 12, 2)->default(0)->comment('Nivel para disparar alertas preventivas');
            
            // Control operativo
            $table->integer('estado')->default(1)->comment('1: Activo, 0: Insumo deshabilitado para esta bodega');
            
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            // Índice único: Un producto solo puede tener una configuración por bodega
            $table->unique(['bodega_id', 'producto_id'], 'idx_bodega_producto_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodega_producto_config');
    }
};
