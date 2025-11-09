<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar um novo colaborador no sistema.
     * Este endpoint permite criar uma nova conta de colaborador.
     * 
     * Recebe: username, password, nome, idade, latitude_inicial, longitude_inicial
     * Retorna: dados do colaborador + token de autenticação
     */
    public function registrar(Request $request)
    {
        // Validar os dados recebidos do cliente
        $validated = $request->validate([
            'username' => 'required|string|unique:colaboradors|min:3',
            'password' => 'required|string|min:6',
            'nome' => 'required|string',
            'idade' => 'required|integer|min:18|max:80',
            'latitude_inicial' => 'required|numeric|between:-90,90',
            'longitude_inicial' => 'required|numeric|between:-180,180',
        ]);

        // Criar novo colaborador no banco de dados
        $colaborador = Colaborador::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']), // Criptografar senha com bcrypt
            'nome' => $validated['nome'],
            'idade' => $validated['idade'],
            'latitude_inicial' => $validated['latitude_inicial'],
            'longitude_inicial' => $validated['longitude_inicial'],
            'latitude_atual' => $validated['latitude_inicial'],  // Inicialmente na mesma posição
            'longitude_atual' => $validated['longitude_inicial'],
            'token' => Str::random(60), // Gerar token aleatório de 60 caracteres
        ]);

        // Retornar resposta de sucesso com status 201 (Created)
        return response()->json([
            'mensagem' => 'Colaborador registrado com sucesso',
            'colaborador' => $colaborador,
            'token' => $colaborador->token,
        ], 201);
    }

    /**
     * Fazer login e obter token de autenticação.
     * O token deve ser enviado no header das próximas requisições.
     * 
     * Recebe: username, password
     * Retorna: dados do colaborador + token
     */
    public function login(Request $request)
    {
        // Validar credenciais recebidas
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Procurar o colaborador pelo username
        $colaborador = Colaborador::where('username', $validated['username'])->first();

        // Verificar se existe e se a senha está correta
        if (!$colaborador || !Hash::check($validated['password'], $colaborador->password)) {
            throw ValidationException::withMessages([
                'username' => 'Credenciais inválidas. Verifique username e senha.',
            ]);
        }

        // Retornar dados do colaborador e token
        return response()->json([
            'mensagem' => 'Login realizado com sucesso',
            'colaborador' => $colaborador,
            'token' => $colaborador->token,
        ]);
    }

    /**
     * Fazer logout (opcional).
     * Em uma implementação real, poderia invalidar o token aqui.
     */
    public function logout(Request $request)
    {
        return response()->json([
            'mensagem' => 'Logout realizado com sucesso',
        ]);
    }
}
