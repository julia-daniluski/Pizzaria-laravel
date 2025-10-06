<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movimentacao;
use App\Models\Pizza;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function index(Request $request)
    {
        $query = Movimentacao::with(['pizza', 'usuario'])->orderByDesc('data_hora');

        // Filtros
        if ($request->filled('pizza_id')) {
            $query->where('pizza_id', $request->pizza_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('de')) {
            $query->where('data_hora', '>=', $request->de . ' 00:00:00');
        }

        if ($request->filled('ate')) {
            $query->where('data_hora', '<=', $request->ate . ' 23:59:59');
        }

        $movimentacoes = $query->paginate(50);
        $pizzas = Pizza::orderBy('nome')->get();

        return view('historico.index', compact('movimentacoes', 'pizzas'));
    }
}

