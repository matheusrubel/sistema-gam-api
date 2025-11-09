<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Colaborador;
use App\Models\Ativo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TESTE DE INTEGRAÇÃO 1: Registrar colaborador via API
     */
    public function test_pode_registrar_colaborador_via_api(): void
    {
        $response = $this->postJson('/api/auth/registrar', [
            'username' => 'joao_teste',
            'password' => 'senha123',
            'nome' => 'João Teste',
            'idade' => 30,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'mensagem',
                     'colaborador' => ['id', 'username', 'nome', 'token'],
                     'token',
                 ]);

        $this->assertDatabaseHas('colaboradors', [
            'username' => 'joao_teste',
            'nome' => 'João Teste',
        ]);
    }

    /**
     * TESTE DE INTEGRAÇÃO 2: Atualizar localização do colaborador
     */
    public function test_pode_atualizar_localizacao_do_colaborador(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'maria_teste',
            'password' => Hash::make('senha123'),
            'nome' => 'Maria Teste',
            'idade' => 28,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'token123',
        ]);

        $response = $this->putJson("/api/colaboradores/{$colaborador->id}/localizacao", [
            'latitude' => -23.5530,
            'longitude' => -46.6350,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensagem' => 'Localização atualizada com sucesso',
                 ]);

        $colaborador->refresh();
        $this->assertEquals(-23.5530, $colaborador->latitude_atual);
        $this->assertEquals(-46.6350, $colaborador->longitude_atual);

        // Verifica se salvou no histórico
        $this->assertDatabaseHas('historico_localizacaos', [
            'colaborador_id' => $colaborador->id,
            'latitude' => -23.5530,
            'longitude' => -46.6350,
        ]);
    }

    /**
     * TESTE DE INTEGRAÇÃO 3: Criar ativo via API
     */
    public function test_pode_criar_ativo_via_api(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'pedro_teste',
            'password' => Hash::make('senha123'),
            'nome' => 'Pedro Teste',
            'idade' => 32,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'token456',
        ]);

        $response = $this->postJson('/api/ativos', [
            'nome' => 'Furadeira DeWalt',
            'valor_contabil' => 850.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $colaborador->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'mensagem',
                     'ativo' => ['id', 'nome', 'valor_contabil', 'status'],
                 ]);

        $this->assertDatabaseHas('ativos', [
            'nome' => 'Furadeira DeWalt',
            'valor_contabil' => 850.00,
            'colaborador_id' => $colaborador->id,
        ]);
    }

    /**
     * TESTE DE INTEGRAÇÃO 4: Transferir ativos entre colaboradores
     */
    public function test_pode_transferir_ativos_entre_colaboradores(): void
    {
        $joao = Colaborador::create([
            'username' => 'joao_transferencia',
            'password' => Hash::make('senha123'),
            'nome' => 'João',
            'idade' => 30,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'tokenJoao',
        ]);

        $maria = Colaborador::create([
            'username' => 'maria_transferencia',
            'password' => Hash::make('senha123'),
            'nome' => 'Maria',
            'idade' => 28,
            'latitude_inicial' => -23.5600,
            'longitude_inicial' => -46.6400,
            'latitude_atual' => -23.5600,
            'longitude_atual' => -46.6400,
            'token' => 'tokenMaria',
        ]);

        $ativo1 = Ativo::create([
            'nome' => 'Martelo',
            'valor_contabil' => 120.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $joao->id,
            'status' => 'EM_USO',
        ]);

        $ativo2 = Ativo::create([
            'nome' => 'Chave',
            'valor_contabil' => 80.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $joao->id,
            'status' => 'EM_USO',
        ]);

        $response = $this->postJson('/api/transferencias', [
            'colaborador_cedente_id' => $joao->id,
            'colaborador_receptora_id' => $maria->id,
            'ativos_ids' => [$ativo1->id, $ativo2->id],
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'mensagem' => 'Transferência realizada com sucesso',
                 ]);

        // Verifica se os ativos agora pertencem à Maria
        $ativo1->refresh();
        $ativo2->refresh();
        $this->assertEquals($maria->id, $ativo1->colaborador_id);
        $this->assertEquals($maria->id, $ativo2->colaborador_id);

        // Verifica se a transferência foi registrada
        $this->assertDatabaseHas('transferencias', [
            'colaborador_cedente_id' => $joao->id,
            'colaborador_receptora_id' => $maria->id,
            'valor_total' => 200.00,
        ]);
    }
}
