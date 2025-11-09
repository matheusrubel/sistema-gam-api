<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    /**
     * Listar todos os colaboradores registrados.
     * Inclui os ativos que cada colaborador possui.
     * 
     * GET /api/colaboradores
     * Retorna: lista de todos os colaboradores com seus ativos
     */
    public function index()
    {
        // Buscar todos os colaboradores e incluir relacionamento com ativos
        $colaboradores = Colaborador::with('ativos')->get();

        return response()->json($colaboradores);
    }

    /**
     * Mostrar detalhes de um colaborador específico.
     * Inclui histórico de localizações e ativos.
     * 
     * GET /api/colaboradores/{id}
     * Retorna: dados completos do colaborador
     */
    public function show($id)
    {
        // Buscar colaborador com históricos e ativos
        $colaborador = Colaborador::with(['historicos', 'ativos'])->find($id);

        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        return response()->json($colaborador);
    }

    /**
     * Atualizar localização atual de um colaborador.
     * Este é um dos endpoints mais críticos do sistema.
     * Toda vez que o colaborador muda de posição, este endpoint é chamado.
     * 
     * PUT /api/colaboradores/{id}/localizacao
     * Recebe: latitude, longitude
     * Retorna: dados atualizados do colaborador
     */
    public function atualizarLocalizacao(Request $request, $id)
    {
        // Validar nova localização
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Buscar colaborador
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        // Atualizar a localização atual na tabela colaboradors
        $colaborador->update([
            'latitude_atual' => $validated['latitude'],
            'longitude_atual' => $validated['longitude'],
        ]);

        // Registrar no histórico para manter auditoria completa
        // Isso permite rastrear todas as movimentações do colaborador
        $colaborador->historicos()->create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json([
            'mensagem' => 'Localização atualizada com sucesso',
            'colaborador' => $colaborador,
        ]);
    }

    /**
     * Obter histórico completo de localizações de um colaborador.
     * Útil para auditoria e visualização de trajetos.
     * 
     * GET /api/colaboradores/{id}/historico-localizacoes
     * Retorna: lista de todas as localizações registradas
     */
    public function historicoLocalizacoes($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        // Retornar histórico ordenado por data (mais recente primeiro)
        $historicos = $colaborador->historicos()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'colaborador_id' => $id,
            'colaborador_nome' => $colaborador->nome,
            'total_registros' => count($historicos),
            'historico' => $historicos,
        ]);
    }
}
