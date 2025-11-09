<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executar a migração.
     * Esta tabela registra todas as transferências de ativos entre colaboradores.
     * É essencial para manter auditoria e rastreabilidade.
     */
    public function up(): void
    {
        Schema::create('transferencias', function (Blueprint $table) {
            // ID único da transferência
            $table->id();
            
            // Quem está cedendo os ativos (origem)
            $table->foreignId('colaborador_cedente_id')->constrained('colaboradors')->onDelete('cascade');
            
            // Quem está recebendo os ativos (destino)
            $table->foreignId('colaborador_receptora_id')->constrained('colaboradors')->onDelete('cascade');
            
            // Valor total transferido (deve ser equilibrado)
            $table->decimal('valor_total', 12, 2);
            
            // Status da transferência (PENDENTE, CONCLUIDA, CANCELADA)
            $table->enum('status', ['PENDENTE', 'CONCLUIDA', 'CANCELADA'])->default('CONCLUIDA');
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};
