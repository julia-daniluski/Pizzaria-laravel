<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\PizzaController;
use Illuminate\Support\Facades\Route;

// Rota principal redireciona para pizzas
Route::get('/', function () {
    return redirect()->route('pizzas.index');
});

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware('auth:usuarios')->group(function () {

    // Pizzas
    Route::get('/pizzas', [PizzaController::class, 'index'])->name('pizzas.index');
    Route::post('/pizzas', [PizzaController::class, 'store'])->name('pizzas.store');
    Route::get('/pizzas/{pizza}/edit', [PizzaController::class, 'edit'])->name('pizzas.edit');
    Route::put('/pizzas/{pizza}', [PizzaController::class, 'update'])->name('pizzas.update');
    Route::delete('/pizzas/{pizza}', [PizzaController::class, 'destroy'])->name('pizzas.destroy');

    // Movimentações
    Route::get('/movimentacoes', [MovimentacaoController::class, 'index'])->name('movimentacoes.index');
    Route::post('/movimentacoes', [MovimentacaoController::class, 'store'])->name('movimentacoes.store');

    // Histórico
    Route::get('/historico', [HistoricoController::class, 'index'])->name('historico.index');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

