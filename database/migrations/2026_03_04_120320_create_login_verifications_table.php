<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('login_verifications', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla users (ajusta si tu tabla se llama diferente)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('token', 64)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            // Estado de la verificación
            $table->boolean('is_confirmed')->default(false);
            
            // Tiempos de vida
            $table->timestamp('expires_at');
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_verifications');
    }
};
