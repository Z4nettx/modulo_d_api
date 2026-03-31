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
        $users = JsonDB::read('Incluir_usuarios');
        return view('tarefas');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jwt = session('jwt');
        if (!$jwt) {
            return dd(session()->all());
        }
        $user = session('user');
        if ($user['equipe'] != 'Gerente') {
            return response()->json(['message' => 'Você não tem privilégio para incluir tarefas']);
        }
        return view('create_tarefa', compact('user', 'jwt'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'prazo' => 'required|string|max:255',
            'equipe' => 'required|string|max:255',
            'prioridade' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'projeto' => 'required|string|max:255',
            'responsavel' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $failedRules = $validator->failed();

            foreach ($failedRules as $field => $rules) {
                if (isset($rules['Required'])) {
                    return response()->json(['message' => 'Verifique e tente novamente, campos faltando', 422]);
                }
            }
            return response()->json(['message' => 'Verifique e tente novamente, dados incorretos.']);
        }
        $tarefa = $request->all();
        if (JsonDB::write('Incluir-Tarefas', $tarefa)) {
            return response()->json(['message' => 'Nova tarefa registrada com sucesso!', 201]);
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
