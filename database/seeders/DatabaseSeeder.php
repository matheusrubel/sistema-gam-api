<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colaborador;
use App\Models\Ativo;
use App\Models\HistoricoLocalizacao;
use App\Models\Transferencia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Criando colaboradores...');
        
        $joao = Colaborador::create([
            'username' => 'joao_silva',
            'password' => Hash::make('senha123'),
            'nome' => 'João Silva',
            'idade' => 30,
            'latitude_inicial' => -23.5505,
            'longitude_inicial' => -46.6333,
            'latitude_atual' => -23.5505,
            'longitude_atual' => -46.6333,
            'token' => Str::random(60),
        ]);
        
        $maria = Colaborador::create([
            'username' => 'maria_santos',
            'password' => Hash::make('senha456'),
            'nome' => 'Maria Santos',
            'idade' => 28,
            'latitude_inicial' => -23.5600,
            'longitude_inicial' => -46.6400,
            'latitude_atual' => -23.5600,
            'longitude_atual' => -46.6400,
            'token' => Str::random(60),
        ]);
        
        $pedro = Colaborador::create([
            'username' => 'pedro_costa',
            'password' => Hash::make('senha789'),
            'nome' => 'Pedro Costa',
            'idade' => 35,
            'latitude_inicial' => -23.5450,
            'longitude_inicial' => -46.6250,
            'latitude_atual' => -23.5450,
            'longitude_atual' => -46.6250,
            'token' => Str::random(60),
        ]);

        $this->command->info('3 colaboradores criados!');
        $this->command->info('Criando ativos...');
        
        $furadeira = Ativo::create([
            'nome' => 'Furadeira DeWalt DCD791',
            'valor_contabil' => 850.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $joao->id,
            'status' => 'EM_USO',
        ]);
        
        $martelo = Ativo::create([
            'nome' => 'Martelo Stanley',
            'valor_contabil' => 120.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $joao->id,
            'status' => 'EM_USO',
        ]);
        
        $chave = Ativo::create([
            'nome' => 'Chave de Impacto Bosch',
            'valor_contabil' => 680.00,
            'latitude_distribuicao' => -23.5505,
            'longitude_distribuicao' => -46.6333,
            'colaborador_id' => $joao->id,
            'status' => 'EM_USO',
        ]);
        
        $parafusadeira = Ativo::create([
            'nome' => 'Parafusadeira Makita',
            'valor_contabil' => 450.00,
            'latitude_distribuicao' => -23.5600,
            'longitude_distribuicao' => -46.6400,
            'colaborador_id' => $maria->id,
            'status' => 'NOVO',
        ]);
        
        $multimetro = Ativo::create([
            'nome' => 'Multimetro Fluke',
            'valor_contabil' => 1200.00,
            'latitude_distribuicao' => -23.5450,
            'longitude_distribuicao' => -46.6250,
            'colaborador_id' => $pedro->id,
            'status' => 'EM_USO',
        ]);

        $this->command->info('5 ativos criados!');
        $this->command->info('Criando historico de localizacoes...');
        
        HistoricoLocalizacao::create(['colaborador_id' => $joao->id, 'latitude' => -23.5505, 'longitude' => -46.6333]);
        HistoricoLocalizacao::create(['colaborador_id' => $joao->id, 'latitude' => -23.5515, 'longitude' => -46.6343]);
        HistoricoLocalizacao::create(['colaborador_id' => $joao->id, 'latitude' => -23.5525, 'longitude' => -46.6353]);
        HistoricoLocalizacao::create(['colaborador_id' => $maria->id, 'latitude' => -23.5600, 'longitude' => -46.6400]);
        HistoricoLocalizacao::create(['colaborador_id' => $maria->id, 'latitude' => -23.5610, 'longitude' => -46.6410]);
        HistoricoLocalizacao::create(['colaborador_id' => $pedro->id, 'latitude' => -23.5450, 'longitude' => -46.6250]);

        $this->command->info('6 localizacoes registradas!');
        $this->command->info('Criando transferencias...');
        
        Transferencia::create([
            'colaborador_cedente_id' => $joao->id,
            'colaborador_receptora_id' => $maria->id,
            'valor_total' => 120.00,
            'status' => 'CONCLUIDA',
        ]);
        $martelo->update(['colaborador_id' => $maria->id]);
        
        Transferencia::create([
            'colaborador_cedente_id' => $pedro->id,
            'colaborador_receptora_id' => $joao->id,
            'valor_total' => 1200.00,
            'status' => 'CONCLUIDA',
        ]);
        $multimetro->update(['colaborador_id' => $joao->id]);

        $this->command->info('2 transferencias criadas!');
        $this->command->warn('BANCO POPULADO COM SUCESSO!');
        $this->command->info('Credenciais: joao_silva/senha123, maria_santos/senha456, pedro_costa/senha789');
    }
}
