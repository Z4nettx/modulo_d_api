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
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'equipe' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255'
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
                    return response()->json(['errors' => ['email' => 'Usuário já cadastrado']], 422);
                }
                return back()->withErrors(['email' => 'Usuário já cadastrado']);
            }
        }
        $users[] = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'equipe' => $request->equipe,
            'password' => bcrypt($request->password)
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
            'password' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator->errors());
        }
        $users = JsonDB::read('Incluir_usuarios');
        $foundUser = null;
        foreach ($users as $user) {
            if ($user['username'] === $request->username) {
                $foundUser = $user;
                break;  
            }
        }
        if (!$foundUser || !Hash::check($request->password, $foundUser['password'])) {
            return response()->json(['message' => 'Login Inválido, tente novamente'], 401);
        }
        $jwt = bin2hex(random_bytes(16));
        session([
            'JWT' => $jwt,
            'user' => [
                'name' => $foundUser['name'],
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
