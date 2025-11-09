<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoLocalizacao extends Model
{
    // Nome da tabela
    protected $table = 'historico_localizacaos';

    // Campos preenchíveis
    protected $fillable = [
        'colaborador_id',  // ID do colaborador
        'latitude',        // Latitude registrada
        'longitude',       // Longitude registrada
    ];

    /**
     * Relacionamento: Cada histórico pertence a um colaborador
     * Uso: $historico->colaborador->nome
     */
    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }
}
