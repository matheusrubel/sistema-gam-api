<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executar a migração.
     * Esta tabela armazena os dados dos colaboradores de campo.
     */
    public function up(): void
    {
        Schema::create('colaboradors', function (Blueprint $table) {
            // ID único do colaborador
            $table->id();
            
            // Dados do usuário para autenticação
            $table->string('username')->unique(); // Nome de usuário único
            $table->string('password'); // Senha criptografada
            
            // Dados pessoais do colaborador
            $table->string('nome'); // Nome completo
            $table->integer('idade'); // Idade do colaborador
            
            // Localização inicial (onde foi registrado)
            $table->decimal('latitude_inicial', 10, 8); // Latitude em decimal
            $table->decimal('longitude_inicial', 11, 8); // Longitude em decimal
            
            // Localização atual (será atualizada em tempo real)
            $table->decimal('latitude_atual', 10, 8)->nullable(); // Latitude atual
            $table->decimal('longitude_atual', 11, 8)->nullable(); // Longitude atual
            
            // Token para autenticação na API
            $table->string('token', 80)->nullable()->unique();
            
            // Timestamps automáticos (created_at, updated_at)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaboradors');
    }
};
