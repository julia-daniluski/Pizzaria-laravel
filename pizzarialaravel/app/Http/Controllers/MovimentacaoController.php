<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Movimentacao;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MovimentacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function index(Request $request)
    {
        $pizzas = Pizza::orderBy('nome')->get();
        $movimentacoes = Movimentacao::with(['pizza', 'usuario'])
            ->latest('data_hora')
            ->paginate(20);

        $debug = (bool) $request->query('debug');

        // Log para debug
        if ($debug) {
            Log::info('Debug Movimentações', [
                'post' => $request->all(),
                'session' => session()->all()
            ]);
        }

        return view('movimentacoes.index', compact('pizzas', 'movimentacoes', 'debug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pizza_id' => 'required|exists:pizzas,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'observacoes' => 'nullable|string',
        ]);

        $pizza = Pizza::findOrFail($data['pizza_id']);

        // Validar se há estoque suficiente para saída
        if ($data['tipo'] === 'saida') {
            $estoqueAtual = $pizza->estoque_atual;
            if ($data['quantidade'] > $estoqueAtual) {
                return back()
                    ->withErrors(['quantidade' => "Quantidade maior que o estoque atual ({$estoqueAtual})."])
                    ->withInput();
            }
        }

        try {
            Movimentacao::create([
                'pizza_id' => $pizza->id,
                'usuario_id' => auth('usuarios')->id(),
                'data_hora' => now(),
                'tipo' => $data['tipo'],
                'quantidade' => $data['quantidade'],
                'observacoes' => $data['observacoes'] ?? null,
            ]);

            Log::info('Movimentação registrada', [
                'pizza' => $pizza->nome,
                'tipo' => $data['tipo'],
                'quantidade' => $data['quantidade']
            ]);

            return redirect()->route('movimentacoes.index')
                ->with('success', 'Movimentação registrada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao registrar movimentação', ['erro' => $e->getMessage()]);

            return redirect()->route('movimentacoes.index')
                ->with('error', 'Erro ao registrar movimentação: ' . $e->getMessage());
        }
    }
}
