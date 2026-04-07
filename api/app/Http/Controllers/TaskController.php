<?php

namespace App\Http\Controllers;

use App\Helpers\JsonDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\JsonDecoder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['Message' => 'Atenção, token não informado'], 422);
        }
        $jwt = JsonDB::read('token');
        if ($token !== $jwt[0]['jwt']) {
            return response()->json(['Message' => 'Atenção, token inválido'], 401);
        }
        $tarefas = JsonDB::read('tarefas');
        return response()->json($tarefas, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jwt = $request->bearerToken();
        $token = JsonDB::read('token');
        if ($token['jwt'] === $jwt) {
            $user = json_decode(base64_decode($token['jwt']), true);
            if ($user['equipe'] !== 'Gerente de Projeto') {
                return response()->json(['message' => 'Você não tem privilégio para incluir tarefas'], 422);
            }
        }

        $users = JsonDB::read('usuarios');
        foreach ($users as $index => $user) {
            $user;// [] de usuario (nome, email, equipe)
            $index; // indice desse elemento dentro do array

            // 1
            /* {
                'nome' => 'eduardo',
                'idade' => 16,
                'profissao' => 'desempregado'
                } */
            // 1
        }
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
                if ($user['equipe'] !== 'Gerente de Projeto') {
                    return response()->json(['message' => 'Você não tem privilégio para incluir tarefas'], 422);
                }
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
        $tarefas = JsonDB::read('tarefas');
        $tarefa = [
            'id' => count($tarefas) + 1,
            ...$request->all(),
            'subtarefas' => [(object) []]
        ];
        if (JsonDB::store('tarefas', $tarefa)) {
            return response()->json(['message' => 'Nova tarefa registrada com sucesso!'], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['Message' => 'Atenção, token não informado'], 422);
        }
        $jwt = JsonDB::read('token');
        if ($token !== $jwt[0]['jwt']) {
            return response()->json(['Message' => 'Atenção, token inválido'], 401);
        }
        if (!$id) {
            return response()->json(['message' => 'ID da tarefa não informado']);
        }
        $tarefas = JsonDB::read('tarefas');

        foreach ($tarefas as $tarefa) {
            // return response()->json($tarefa);
            if ($tarefa['id'] == $id) {
                return response()->json($tarefa, 200);
            }
        }
        return response()->json(['message' => 'ID da tarefa inválido'], 422);
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
    public function update(Request $request, $id)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['Message' => 'Atenção, token não informado'], 422);
        }
        $jwt = JsonDB::read('token');
        if ($token !== $jwt[0]['jwt']) {
            return response()->json(['Message' => 'Atenção, token inválido'], 401);
        }
        if (!empty($request->except(['descricao', 'prazo', 'status', 'prioridade', 'responsavel']))) {
            return response()->json(['message' => 'Somente descrição, prazo, status, prioridade e responsável podem ser editados!'], 422);
        }
        $tarefaAtualizada = $request->only(['descricao', 'prazo', 'status', 'prioridade', 'responsavel']);

        if (JsonDB::update('tarefas', 'id', $id, $tarefaAtualizada)) {
            return response()->json(['message' => 'Tarefa atualizada com sucesso'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['Message' => 'Atenção, token não informado'], 422);
        }
        $jwt = JsonDB::read('token');
        if ($token !== $jwt[0]['jwt']) {
            return response()->json(['Message' => 'Atenção, token inválido'], 401);
        }
        $user = json_decode(base64_decode($token));
        if ($user->equipe !== "Gerente de Projeto") {
            return response()->json(['message' => 'Você não tem permissão para excluir uma tarefa'], 401);
        }
        $tarefas = JsonDB::read('tarefas');
        foreach ($tarefas as $tarefa) {
            // return response()->json($tarefa);    
            if ($tarefa["id"] == $id) {
                if (isset($tarefa['subtarefas'])) {
                    return response()->json(['message' => 'Essa tarefa possui subtarefas, você não pode excluí-la'], 401);
                }
            }
        }
        if (JsonDB::delete('tarefas', 'id', $id)) {
            return response()->json(['message' => 'Tarefa excluida com sucesso'], 200);
        }
    }
}
