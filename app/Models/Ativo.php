<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ativo extends Model
{
    // Nome da tabela
    protected $table = 'ativos';

    // Campos preenchíveis
    protected $fillable = [
        'nome',                      // Nome do ativo
        'valor_contabil',            // Valor monetário
        'latitude_distribuicao',     // Onde foi distribuído
        'longitude_distribuicao',    // Onde foi distribuído
        'colaborador_id',            // Proprietário atual
        'status',                    // NOVO, EM_USO, MANUTENCAO
    ];

    /**
     * Relacionamento: Cada ativo pertence a um colaborador
     * Uso: $ativo->colaborador->nome
     */
    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}
