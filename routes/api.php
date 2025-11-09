<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\AtivoController;
use App\Http\Controllers\TransferenciaController;

/**
 * ===================================
 * ROTAS DE AUTENTICAÇÃO
 * ===================================
 * Endpoints públicos para registro e login
 */

// Registrar novo colaborador
// POST /api/auth/registrar
// Body: { username, password, nome, idade, latitude_inicial, longitude_inicial }
Route::post('/auth/registrar', [AuthController::class, 'registrar']);

// Fazer login
// POST /api/auth/login
// Body: { username, password }
Route::post('/auth/login', [AuthController::class, 'login']);

// Fazer logout (opcional)
// POST /api/auth/logout
Route::post('/auth/logout', [AuthController::class, 'logout']);

/**
 * ===================================
 * ROTAS DE COLABORADORES
 * ===================================
 * Endpoints para gerenciar colaboradores e suas localizações
 */

// Listar todos os colaboradores
// GET /api/colaboradores
Route::get('/colaboradores', [ColaboradorController::class, 'index']);

// Ver detalhes de um colaborador específico
// GET /api/colaboradores/{id}
Route::get('/colaboradores/{id}', [ColaboradorController::class, 'show']);

// Atualizar localização de um colaborador
// PUT /api/colaboradores/{id}/localizacao
// Body: { latitude, longitude }
Route::put('/colaboradores/{id}/localizacao', [ColaboradorController::class, 'atualizarLocalizacao']);

// Ver histórico de localizações de um colaborador
// GET /api/colaboradores/{id}/historico-localizacoes
Route::get('/colaboradores/{id}/historico-localizacoes', [ColaboradorController::class, 'historicoLocalizacoes']);

/**
 * ===================================
 * ROTAS DE ATIVOS
 * ===================================
 * Endpoints para gerenciar ativos (equipamentos e ferramentas)
 */

// Listar todos os ativos
// GET /api/ativos
Route::get('/ativos', [AtivoController::class, 'index']);

// Criar novo ativo e distribuir a um colaborador
// POST /api/ativos
// Body: { nome, valor_contabil, latitude_distribuicao, longitude_distribuicao, colaborador_id }
Route::post('/ativos', [AtivoController::class, 'store']);

// Ver detalhes de um ativo específico
// GET /api/ativos/{id}
Route::get('/ativos/{id}', [AtivoController::class, 'show']);

// Listar todos os ativos de um colaborador
// GET /api/colaboradores/{colaboradorId}/ativos
Route::get('/colaboradores/{colaboradorId}/ativos', [AtivoController::class, 'ativosDoColaborador']);

// Atualizar status de um ativo
// PUT /api/ativos/{id}/status
// Body: { status: "NOVO" | "EM_USO" | "MANUTENCAO" }
Route::put('/ativos/{id}/status', [AtivoController::class, 'atualizarStatus']);

/**
 * ===================================
 * ROTAS DE TRANSFERÊNCIAS
 * ===================================
 * Endpoints para transferir ativos entre colaboradores
 */

// Listar todas as transferências
// GET /api/transferencias
Route::get('/transferencias', [TransferenciaController::class, 'index']);

// Criar nova transferência de ativos
// POST /api/transferencias
// Body: { colaborador_cedente_id, colaborador_receptora_id, ativos_ids: [1, 2, 3] }
Route::post('/transferencias', [TransferenciaController::class, 'store']);

// Ver detalhes de uma transferência específica
// GET /api/transferencias/{id}
Route::get('/transferencias/{id}', [TransferenciaController::class, 'show']);

// Listar transferências de um colaborador (feitas e recebidas)
// GET /api/colaboradores/{colaboradorId}/transferencias
Route::get('/colaboradores/{colaboradorId}/transferencias', [TransferenciaController::class, 'transferenciasDoColaborador']);
