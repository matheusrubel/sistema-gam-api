<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Colaborador;
use App\Models\Ativo;
use App\Models\HistoricoLocalizacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColaboradorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TESTE UNITÁRIO 1: Verificar se colaborador pode ser criado
     */
    public function test_colaborador_pode_ser_criado(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'teste_user',
            'password' => bcrypt('senha123'),
            'nome' => 'Teste Silva',
            'idade' => 25,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'abc123',
        ]);

        $this->assertDatabaseHas('colaboradors', [
            'username' => 'teste_user',
            'nome' => 'Teste Silva',
        ]);

        $this->assertEquals('Teste Silva', $colaborador->nome);
    }

    /**
     * TESTE UNITÁRIO 2: Verificar relacionamento colaborador -> ativos
     */
    public function test_colaborador_tem_relacionamento_com_ativos(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'joao_teste',
            'password' => bcrypt('senha123'),
            'nome' => 'João Teste',
            'idade' => 30,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'token123',
        ]);

        $ativo = Ativo::create([
            'nome' => 'Furadeira Teste',
            'valor_contabil' => 500.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $colaborador->id,
            'status' => 'NOVO',
        ]);

        $this->assertTrue($colaborador->ativos->contains($ativo));
        $this->assertEquals(1, $colaborador->ativos->count());
    }

    /**
     * TESTE UNITÁRIO 3: Verificar relacionamento colaborador -> histórico
     */
    public function test_colaborador_tem_relacionamento_com_historico(): void
    {
        $colaborador = Colaborador::create([
            'username' => 'maria_teste',
            'password' => bcrypt('senha123'),
            'nome' => 'Maria Teste',
            'idade' => 28,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => 'token456',
        ]);

        $historico = HistoricoLocalizacao::create([
            'colaborador_id' => $colaborador->id,
            'latitude' => -23.5520,
            'longitude' => -46.6340,
        ]);

        $this->assertTrue($colaborador->historicos->contains($historico));
        $this->assertEquals(1, $colaborador->historicos->count());
    }
}
