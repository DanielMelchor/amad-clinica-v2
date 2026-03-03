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
        Schema::create('auditoria_accesos', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 50);
        
            // Datos técnicos
            $table->string('ip_address', 45); // Soporta IPv4 e IPv6
            $table->string('navegador', 50)->nullable();   // Chrome, Firefox
            $table->string('version_navegador', 20)->nullable();
            $table->string('plataforma', 50)->nullable();  // Windows, OS X, Linux
            $table->string('dispositivo', 50)->nullable(); // iPhone, Desktop
            
            // Auditoría de navegación
            $table->text('url_visitada');
            $table->string('metodo', 10); // GET, POST, etc.
            
            // Registro de tiempo
            $table->timestamp('fecha_registro')->useCurrent();
        });

        Schema::table('auditoria_accesos', function (Blueprint $table) {
            $table->index('fecha_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_accesos');
    }
};
