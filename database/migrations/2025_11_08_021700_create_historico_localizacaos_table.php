<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executar a migração.
     * Esta tabela mantém o histórico completo de todas as localizações.
     * Cada vez que um colaborador atualiza sua posição, cria-se um registro aqui.
     */
    public function up(): void
    {
        Schema::create('historico_localizacaos', function (Blueprint $table) {
            // ID único do registro de histórico
            $table->id();
            
            // Referência para qual colaborador registrou essa localização
            $table->foreignId('colaborador_id')->constrained('colaboradors')->onDelete('cascade');
            
            // Coordenadas registradas neste momento
            $table->decimal('latitude', 10, 8); // Latitude
            $table->decimal('longitude', 11, 8); // Longitude
            
            // Timestamp automático (quando foi registrado)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_localizacaos');
    }
};
