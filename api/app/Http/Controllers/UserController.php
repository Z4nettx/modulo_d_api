<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JsonDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register()
    {
        return view('register');
    }
    public function signup(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'equipe' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'senha' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator->errors());
        }

        $users = JsonDB::read('Incluir_usuarios');

        foreach ($users as $user) {
            if ($user['email'] === $request->email) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => ['email' => 'Usuário já cadastrado']], 422);
                }
                return back()->withErrors(['email' => 'Usuário já cadastrado']);
            }
        }
        $users[] = [
            'nome' => $request->nome,
            'email' => $request->email,
            'username' => $request->username,
            'equipe' => $request->equipe,
            'senha' => bcrypt($request->senha)
        ];
        JsonDB::write('Incluir_usuarios', $users);
        return redirect()->route('login.index');
    }

    public function index()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'senha' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator->errors());
        }
        $users = JsonDB::read('Incluir_usuarios');
        $foundUser = null;
        $foundIndex = null;
        foreach ($users as $index => $user) {
            if ($user['username'] === $request->username) {
                $foundUser = $user;
                $foundIndex = $index;
                break;  
            }
        }
        if (!$foundUser) {
            return response()->json(['message' => 'Login Inválido, tente novamente'], 401);
        }
        if (password_get_info($foundUser['senha'])['algo'] === null) {
            if($foundUser['senha'] !== $request->senha) {
                return response()->json(['message' => 'aLogin inválido, tente novamente'], 401);
            }
            $users[$foundIndex]['senha'] = Hash::make($request->senha);
            JsonDB::write('Incluir_usuarios', $users);
        } else {
            if (!Hash::check($request->senha, $foundUser['senha'])) {
                return response()->json(['message' => 'eLogin inválido, tente novamente'], 401);
            }
        }
        $jwt = bin2hex(random_bytes(16));
        session([
            'jwt' => $jwt,
            'user' => [
                'nome' => $foundUser['nome'],
                'email' => $foundUser['email'],
                'username' => $foundUser['username'],
                'equipe' => $foundUser['equipe'],
            ]
        ]);
        return redirect()->route('listatarefa');
    }
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('register.form');
    }
}
