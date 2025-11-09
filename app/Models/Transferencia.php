<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transferencia extends Model
{
    // Nome da tabela
    protected $table = 'transferencias';

    // Campos preenchíveis
    protected $fillable = [
        'colaborador_cedente_id',      // Quem cedeu
        'colaborador_receptora_id',    // Quem recebeu
        'valor_total',                 // Valor total transferido
        'status',                      // PENDENTE, CONCLUIDA, CANCELADA
    ];

    /**
     * Relacionamento: Colaborador que cedeu os ativos
     * Uso: $transferencia->colaboradorCedente->nome
     */
    public function colaboradorCedente(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_cedente_id');
    }

    /**
     * Relacionamento: Colaborador que recebeu os ativos
     * Uso: $transferencia->colaboradorReceptora->nome
     */
    public function colaboradorReceptora(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_receptora_id');
    }
}
