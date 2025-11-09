<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ativo;
use App\Models\Colaborador;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AtivoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TESTE UNITÁRIO 4: Verificar se ativo pode ser criado
     */
    public function test_ativo_pode_ser_criado(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'pedro_teste',
            'password' => bcrypt('senha123'),
            'nome' => 'Pedro Teste',
            'idade' => 32,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'token789',
        ]);

        $ativo = Ativo::create([
            'nome' => 'Serra Circular',
            'valor_contabil' => 750.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $colaborador->id,
            'status' => 'NOVO',
        ]);

        $this->assertDatabaseHas('ativos', [
            'nome' => 'Serra Circular',
            'valor_contabil' => 750.00,
        ]);
    }

    /**
     * TESTE UNITÁRIO 5: Verificar relacionamento ativo -> colaborador
     */
    public function test_ativo_pertence_a_colaborador(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'ana_teste',
            'password' => bcrypt('senha123'),
            'nome' => 'Ana Teste',
            'idade' => 27,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'tokenABC',
        ]);

        $ativo = Ativo::create([
            'nome' => 'Martelo',
            'valor_contabil' => 100.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $colaborador->id,
            'status' => 'EM_USO',
        ]);

        $this->assertEquals($colaborador->id, $ativo->colaborador->id);
        $this->assertEquals($colaborador->nome, $ativo->colaborador->nome);
    }

    /**
     * TESTE UNITÁRIO 6: Verificar validação de status do ativo
     */
    public function test_status_ativo_deve_ser_valido(): void
    {
        $statusValidos = ['NOVO', 'EM_USO', 'MANUTENCAO'];
        
        foreach ($statusValidos as $index => $status) {
            $colaborador = Colaborador::create([
                'username' => "user_status_{$index}",
                'password' => bcrypt('senha123'),
                'nome' => "Colaborador {$index}",
                'idade' => 25,
                'latitude_inicial' => -23.5505,
                'longitude_inicial' => -46.6333,
                'latitude_atual' => -23.5505,
                'longitude_atual' => -46.6333,
                'token' => "token_{$index}",
            ]);
            
            $ativo = Ativo::create([
                'nome' => "Ativo {$status}",
                'valor_contabil' => 100.00,
                'latitude_distribuicao' => -23.5505,
                'longitude_distribuicao' => -46.6333,
                'colaborador_id' => $colaborador->id,
                'status' => $status,
            ]);

            $this->assertEquals($status, $ativo->status);
        }
    }
}
