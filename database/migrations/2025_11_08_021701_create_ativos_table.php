<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executar a migração.
     * Esta tabela armazena todos os ativos de alto valor
     * (equipamentos, ferramentas especializadas) da empresa.
     */
    public function up(): void
    {
        Schema::create('ativos', function (Blueprint $table) {
            // ID único do ativo
            $table->id();
            
            // Informações do ativo
            $table->string('nome'); // Nome do equipamento/ferramenta
            $table->decimal('valor_contabil', 12, 2); // Valor em reais (ex: 1000.50)
            
            // Local onde foi distribuído inicialmente
            $table->decimal('latitude_distribuicao', 10, 8); // Latitude
            $table->decimal('longitude_distribuicao', 11, 8); // Longitude
            
            // Qual colaborador é o proprietário atual deste ativo
            $table->foreignId('colaborador_id')->constrained('colaboradors')->onDelete('cascade');
            
            // Status do ativo (NOVO, EM_USO, MANUTENCAO)
            $table->enum('status', ['NOVO', 'EM_USO', 'MANUTENCAO'])->default('NOVO');
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ativos');
    }
};
