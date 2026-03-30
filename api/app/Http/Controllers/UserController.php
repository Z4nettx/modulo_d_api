<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\JsonDB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{   

    public function register() {
        return view('register');
    }
    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'equipe' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);
        $users = JsonDB::read('Incluir_usuarios');

        foreach ($users as $user) {
            if ($user['email'] === $request->email) {
                return back()->withErrors(['email' => '(422) Usuário já cadastrado']);
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

    public function index() {
        return view('login');
    }
    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        $users = JsonDB::read('Incluir_usuarios');
        foreach ($users as $user) {
            if ($user['username'] === $request->username) {
                if (!Hash::check($request->password, $user['password'])) {
                    return response()->json(['message' => 'Login Inválido, tente novamente'], 401);
                }
            }
        }
        return redirect()->route('listatarefa');
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('/signup');
    }
}
