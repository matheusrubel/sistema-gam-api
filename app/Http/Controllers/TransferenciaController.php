<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use App\Models\Ativo;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    /**
     * Listar todas as transferências realizadas.
     * Inclui informações de quem cedeu e quem recebeu.
     * 
     * GET /api/transferencias
     * Retorna: lista de todas as transferências
     */
    public function index()
    {
        $transferencias = Transferencia::with([
            'colaboradorCedente',
            'colaboradorReceptora',
        ])->get();

        return response()->json($transferencias);
    }

    /**
     * Criar uma transferência de ativos entre colaboradores.
     * Este é o endpoint mais complexo do sistema.
     * Valida se os ativos pertencem ao cedente e calcula o valor total.
     * 
     * POST /api/transferencias
     * Recebe: colaborador_cedente_id, colaborador_receptora_id, ativos_ids[]
     * Retorna: transferência criada + ativos transferidos
     */
    public function store(Request $request)
    {
        // Validar dados da transferência
        $validated = $request->validate([
            'colaborador_cedente_id' => 'required|integer|exists:colaboradors,id',
            'colaborador_receptora_id' => 'required|integer|exists:colaboradors,id',
            'ativos_ids' => 'required|array|min:1', // Array de IDs dos ativos
            'ativos_ids.*' => 'integer|exists:ativos,id',
        ]);

        // Verificar se são colaboradores diferentes
        if ($validated['colaborador_cedente_id'] === $validated['colaborador_receptora_id']) {
            return response()->json([
                'erro' => 'Não é possível transferir ativos para o mesmo colaborador',
            ], 400);
        }

        // Buscar os ativos que serão transferidos
        $ativos = Ativo::whereIn('id', $validated['ativos_ids'])->get();

        // Validar se todos os ativos pertencem ao cedente
        foreach ($ativos as $ativo) {
            if ($ativo->colaborador_id != $validated['colaborador_cedente_id']) {
                return response()->json([
                    'erro' => "O ativo '{$ativo->nome}' não pertence ao colaborador cedente",
                ], 400);
            }
        }

        // Calcular valor total transferido (soma de todos os ativos)
        $valorTotal = $ativos->sum('valor_contabil');

        // Criar registro de transferência no banco
        $transferencia = Transferencia::create([
            'colaborador_cedente_id' => $validated['colaborador_cedente_id'],
            'colaborador_receptora_id' => $validated['colaborador_receptora_id'],
            'valor_total' => $valorTotal,
            'status' => 'CONCLUIDA', // Pode ser PENDENTE se houver workflow de aprovação
        ]);

        // Atualizar o proprietário de cada ativo para o novo colaborador
        foreach ($ativos as $ativo) {
            $ativo->update([
                'colaborador_id' => $validated['colaborador_receptora_id'],
            ]);
        }

        return response()->json([
            'mensagem' => 'Transferência realizada com sucesso',
            'transferencia' => $transferencia->load([
                'colaboradorCedente',
                'colaboradorReceptora',
            ]),
            'ativos_transferidos' => $ativos,
            'valor_total' => $valorTotal,
        ], 201);
    }

    /**
     * Mostrar detalhes de uma transferência específica.
     * 
     * GET /api/transferencias/{id}
     * Retorna: dados completos da transferência
     */
    public function show($id)
    {
        $transferencia = Transferencia::with([
            'colaboradorCedente',
            'colaboradorReceptora',
        ])->find($id);

        if (!$transferencia) {
            return response()->json([
                'erro' => 'Transferência não encontrada',
            ], 404);
        }

        return response()->json($transferencia);
    }

    /**
     * Listar todas as transferências de um colaborador.
     * Mostra tanto as transferências feitas quanto as recebidas.
     * 
     * GET /api/colaboradores/{colaboradorId}/transferencias
     * Retorna: transferências feitas + transferências recebidas
     */
    public function transferenciasDoColaborador($colaboradorId)
    {
        $colaborador = Colaborador::find($colaboradorId);

        if (!$colaborador) {
            return response()->json([
                'erro' => 'Colaborador não encontrado',
            ], 404);
        }

        // Buscar transferências onde o colaborador foi cedente ou receptor
        $transferenciasFeitas = $colaborador->transferenciasFeitas()->with('colaboradorReceptora')->get();
        $transferenciasRecebidas = $colaborador->transferenciasRecebidas()->with('colaboradorCedente')->get();

        return response()->json([
            'colaborador_id' => $colaboradorId,
            'colaborador_nome' => $colaborador->nome,
            'transferencias_feitas' => $transferenciasFeitas,
            'transferencias_recebidas' => $transferenciasRecebidas,
        ]);
    }
}
