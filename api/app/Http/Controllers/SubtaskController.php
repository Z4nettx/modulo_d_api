<?php

namespace App\Http\Controllers;

use App\Helpers\JsonDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubtaskController extends Controller
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
        $subtarefas = $tarefas['subtarefas'];
        return response()->json($subtarefas, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jwt = $request->bearerToken();

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'prazo' => 'required|date_format:Y-m-d',
            'equipe' => 'required|string|max:255',
            'prioridade' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'projeto' => 'required|string|max:255',
            'responsavel' => 'required|integer',
            'tarefa' => 'required|integer'
        ]);
        $id = $request->tarefa;
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
        foreach ($tarefas as $tarefa) {
            if ($tarefa['id'] == $id) {
                $novaSubtarefa = [
                    'id' => rand(0000, 10),
                    // $request->except('tarefa')
                    'titulo' => $request->titulo,
                    'descricao' => $request->descricao,
                    'prazo' => $request->prazo,
                    'equipe' => $request->equipe,
                    'prioridade' => $request->prioridade,
                    'status' => $request->status,
                    'projeto' => $request->projeto,
                    'responsavel' => $request->responsavel,

                ];
                $tarefa['subtarefas'][] = $novaSubtarefa;
                if (JsonDB::update('tarefas', 'id', $id, $tarefa)) { // voce parou aqui
                    return response()->json(['message' => 'Nova subtarefa registrada com sucesso!'], 201);
                }
                break;
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['Message' => 'Atenção, token não informado'], 422);
        }
        $jwt = JsonDB::read('token');
        if ($token !== $jwt[0]['jwt']) {
            return response()->json(['Message' => 'Atenção, token inválido'], 401);
        }
        
        if (!empty($request->except(['descricao', 'prazo', 'status', 'prioridade', 'responsavel', 'tarefa', 'subtarefa']))) {
            return response()->json(['message' => 'Somente descrição, prazo, status, prioridade e responsável podem ser editados!'], 422);
        }
        $tarefaId = $request['tarefa']; // id da tarefa envido pelo form
        $stId = $request['subtarefa'];
        
        $tarefas = JsonDB::read('tarefas'); // o array tarefa completo
        $camposPermitidos = [
            'descricao' => $request['descricao'],
            'prazo' => $request['prazo'],
            'status' => $request['status'],
            'prioridade' => $request['prioridade'],
            'responsavel' => $request['responsavel']
        ];

        foreach ($tarefas as $tarefa) {
            if ($tarefa['id'] == $tarefaId) {
                $subtarefas = $tarefa['subtarefas'];
                $encontrouSub = false;   
                foreach ($subtarefas as &$st) {
                    if ($st['id'] == $stId) {
                        $st = array_merge($st, $camposPermitidos);
                        $encontrouSub = true;
                        break;
                    }
                }
                if ($encontrouSub) {
                    $novosDados = [
                        "subtarefas" => $subtarefas
                    ];
                    if (JsonDB::update('tarefas', 'id', $tarefaId, $novosDados)) {
                        return response()->json(['message' => 'Subtarefa atualizada com sucesso'], 200);
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {   
        $tarefas = JsonDB::read('tarefas');
        foreach ($tarefas as $tarefa) {
            if ($tarefa)
        }
        JsonDB::delete('tarefas', $)
    }
}
