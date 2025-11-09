<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class AtivoController extends Controller
{
    /**
     * Listar todos os ativos registrados no sistema.
     * Inclui informações do proprietário de cada ativo.
     * 
     * GET /api/ativos
     * Retorna: lista de todos os ativos com seus proprietários
     */
    public function index()
    {
        // Buscar todos os ativos com informações do colaborador proprietário
        $ativos = Ativo::with('colaborador')->get();

        return response()->json($ativos);
    }

    /**
     * Criar um novo ativo e distribuir a um colaborador.
     * O ativo é imediatamente associado a um colaborador específico.
     * 
     * POST /api/ativos
     * Recebe: nome, valor_contabil, latitude_distribuicao, longitude_distribuicao, colaborador_id
     * Retorna: dados do ativo criado
     */
    public function store(Request $request)
    {
        // Validar dados do novo ativo
        $validated = $request->validate([
            'nome' => 'required|string',
            'valor_contabil' => 'required|numeric|min:0.01',
            'latitude_distribuicao' => 'required|numeric|between:-90,90',
            'longitude_distribuicao' => 'required|numeric|between:-180,180',
            'colaborador_id' => 'required|integer|exists:colaboradors,id',
        ]);

        // Verificar se o colaborador existe
        $colaborador = Colaborador::find($validated['colaborador_id']);
        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        // Definir status padrão se não foi informado
        $validated['status'] = $validated['status'] ?? 'NOVO';

        // Criar o novo ativo no banco de dados
        $ativo = Ativo::create($validated);

        // Retornar ativo criado com informações do proprietário
        return response()->json([
            'mensagem' => 'Ativo criado e distribuído com sucesso',
            'ativo' => $ativo->load('colaborador'),
        ], 201);
    }

    /**
     * Mostrar detalhes de um ativo específico.
     * 
     * GET /api/ativos/{id}
     * Retorna: dados completos do ativo
     */
    public function show($id)
    {
        $ativo = Ativo::with('colaborador')->find($id);

        if (!$ativo) {
            return response()->json([
                'erro' => 'Ativo não encontrado',
            ], 404);
        }

        return response()->json($ativo);
    }

    /**
     * Listar todos os ativos de um colaborador específico.
     * Útil para ver o inventário completo de um colaborador.
     * 
     * GET /api/colaboradores/{colaboradorId}/ativos
     * Retorna: lista de ativos + valor total
     */
    public function ativosDoColaborador($colaboradorId)
    {
        $colaborador = Colaborador::find($colaboradorId);

        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        $ativos = $colaborador->ativos()->get();

        return response()->json([
            'colaborador_id' => $colaboradorId,
            'colaborador_nome' => $colaborador->nome,
            'total_ativos' => count($ativos),
            'valor_total' => $ativos->sum('valor_contabil'), // Soma o valor de todos os ativos
            'ativos' => $ativos,
        ]);
    }

    /**
     * Atualizar status de um ativo.
     * Permite marcar ativo como: NOVO, EM_USO, ou MANUTENCAO
     * 
     * PUT /api/ativos/{id}/status
     * Recebe: status (NOVO, EM_USO, MANUTENCAO)
     * Retorna: ativo atualizado
     */
    public function atualizarStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:NOVO,EM_USO,MANUTENCAO',
        ]);

        $ativo = Ativo::find($id);

        if (!$ativo) {
            return response()->json([
                'erro' => 'Ativo não encontrado',
            ], 404);
        }

        $ativo->update($validated);

        return response()->json([
            'mensagem' => 'Status do ativo atualizado com sucesso',
            'ativo' => $ativo,
        ]);
    }
}
