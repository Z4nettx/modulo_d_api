<?php

namespace App\Http\Controllers;

use App\Helpers\JsonDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $users = JsonDB::read('usuarios');
        return view('tarefas', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jwt = $request->bearerToken();
        $tokens = JsonDB::read('token');
        foreach ($tokens as $token) {
            if ($token['jwt'] === $jwt) {
                $user = json_decode(base64_decode($token['jwt']), true);
                break;
            }    
        }
        if ($user['equipe'] !== 'Gerente de Projeto') {
            return response()->json(['message' => 'Você não tem privilégio para incluir tarefas'], 422);
        }
        $users = JsonDB::read('usuarios');
        return view('create_tarefa', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jwt = $request->bearerToken();
        $tokens = JsonDB::read('token');
        foreach ($tokens as $token) {
            if ($token['jwt'] === $jwt) {
                $user = json_decode(base64_decode($token['jwt']), true);
            }
        }
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'prazo' => 'required|date_format:Y-m-d',
            'equipe' => 'required|string|max:255',
            'prioridade' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'projeto' => 'required|string|max:255',
            'responsavel' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $failedRules = $validator->failed();

            foreach ($failedRules as $rules) {
                if (isset($rules['Required'])) {
                    return response()->json(['message' => 'Verifique e tente novamente, campos faltando'], 422);
                }
            }
            return response()->json(['message' => "Verifique e tente novamente, dados incorretos."], 422);
        }
        $tarefa = $request->all();
        if (JsonDB::write('tarefas', $tarefa)) {
            return response()->json(['message' => 'Nova tarefa registrada com sucesso!'], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
