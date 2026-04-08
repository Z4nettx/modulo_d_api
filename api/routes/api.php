<?php

/* use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/* Route::get('/', function () {
    return view('welcome');
}); */


/* login */

Route::get('/signup', [UserController::class, 'register'])->name('register.form');
Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::get('/login', [UserController::class, 'index'])->name('login.index');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/add_tarefa', [TaskController::class, 'create'])->name('tarefa.create');
Route::post('/add_tarefa', [TaskController::class, 'store'])->name('tarefa.store');

Route::get('/altera_tarefa/{id}', [TaskController::class, 'edit'])->name('tarefa.edit');
Route::put('/altera_tarefa/{id}', [TaskController::class, 'update'])->name('tarefa.update');

Route::delete('/delete_tarefa/{id}', [TaskController::class, 'destroy']);

Route::get('lista_tarefas', [TaskController::class, 'index'])->name('listatarefa');

Route::get('tarefa/{id}', [TaskController::class, 'show']);

Route::post('/add_subtarefa', [SubtaskController::class, 'store']); 
Route::put('/altera_subtarefa/{id}', [SubtaskController::class, 'update']); 
Route::delete('/delete_subtarefa', [SubtaskController::class, 'destroy']); 

