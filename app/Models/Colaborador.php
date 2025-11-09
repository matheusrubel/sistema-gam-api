<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colaborador extends Model
{
    // Nome da tabela no banco de dados
    protected $table = 'colaboradors';

    // Campos que podem ser preenchidos em massa (mass assignment)
    // Isso permite usar Colaborador::create([...])
    protected $fillable = [
        'username',           // Nome de usuário para login
        'password',           // Senha (será criptografada)
        'nome',               // Nome completo
        'idade',              // Idade
        'latitude_inicial',   // Onde foi registrado
        'longitude_inicial',  // Onde foi registrado
        'latitude_atual',     // Onde está agora
        'longitude_atual',    // Onde está agora
        'token',              // Token de autenticação
    ];

    // Campos que NÃO devem aparecer ao serializar (converter para JSON)
    // Importante para segurança
    protected $hidden = [
        'password',  // Nunca expor senha
    ];

    /**
     * Relacionamento: Um colaborador tem muitos históricos de localização
     * Quando buscar um colaborador, pode incluir: with('historicos')
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(HistoricoLocalizacao::class, 'colaborador_id');
    }

    /**
     * Relacionamento: Um colaborador pode ter muitos ativos
     */
    public function ativos(): HasMany
    {
        return $this->hasMany(Ativo::class, 'colaborador_id');
    }

    /**
     * Relacionamento: Transferências que este colaborador fez (cedeu ativos)
     */
    public function transferenciasFeitas(): HasMany
    {
        return $this->hasMany(Transferencia::class, 'colaborador_cedente_id');
    }

    /**
     * Relacionamento: Transferências que este colaborador recebeu
     */
    public function transferenciasRecebidas(): HasMany
    {
        return $this->hasMany(Transferencia::class, 'colaborador_receptora_id');
    }
}
