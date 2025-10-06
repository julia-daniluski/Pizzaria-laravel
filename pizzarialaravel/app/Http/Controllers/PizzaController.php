<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PizzaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function index(Request $request)
    {
        $pizzas = Pizza::orderBy('nome')->paginate(15);
        $debug = (bool) $request->query('debug');

        if ($debug) {
            Log::info('Debug Pizzas', [
                'post' => $request->all(),
                'session' => session()->all()
            ]);
        }

        return view('pizzas.index', compact('pizzas', 'debug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'ingredientes' => 'required|string',
            'preco' => 'required|string', // aceita qualquer valor
            'tamanho' => 'required|in:Pequena,Media,Grande',
            'categoria' => 'required|string|max:50',
            'estoque_minimo' => 'required|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        // Normalizar o preço: vírgula para ponto se for decimal
        $data['preco'] = str_replace(',', '.', $data['preco']);
        $data['ativo'] = (bool)($data['ativo'] ?? true);

        try {
            Pizza::create($data);
            Log::info('Pizza cadastrada', ['pizza' => $data['nome']]);
            return redirect()->route('pizzas.index')
                ->with('success', 'Pizza cadastrada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar pizza', ['erro' => $e->getMessage()]);
            return redirect()->route('pizzas.index')
                ->with('error', 'Erro ao cadastrar pizza: ' . $e->getMessage());
        }
    }

    public function edit(Pizza $pizza)
    {
        return view('pizzas.edit', compact('pizza'));
    }

    public function update(Request $request, Pizza $pizza)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'ingredientes' => 'required|string',
            'preco' => 'required|string', // aceita qualquer valor
            'tamanho' => 'required|in:Pequena,Media,Grande',
            'categoria' => 'required|string|max:50',
            'estoque_minimo' => 'required|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        // Normalizar o preço: vírgula para ponto se for decimal
        $data['preco'] = str_replace(',', '.', $data['preco']);
        $data['ativo'] = (bool)($data['ativo'] ?? true);

        try {
            $pizza->update($data);
            Log::info('Pizza atualizada', ['pizza' => $pizza->nome]);
            return redirect()->route('pizzas.index')
                ->with('success', 'Pizza atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pizza', ['erro' => $e->getMessage()]);
            return redirect()->route('pizzas.index')
                ->with('error', 'Erro ao atualizar pizza: ' . $e->getMessage());
        }
    }

    public function destroy(Pizza $pizza)
    {
        try {
            $nome = $pizza->nome;
            $pizza->delete();
            Log::info('Pizza excluída', ['pizza' => $nome]);
            return redirect()->route('pizzas.index')
                ->with('success', 'Pizza excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir pizza', ['erro' => $e->getMessage()]);
            return redirect()->route('pizzas.index')
                ->with('error', 'Erro ao excluir pizza: ' . $e->getMessage());
        }
    }
}
