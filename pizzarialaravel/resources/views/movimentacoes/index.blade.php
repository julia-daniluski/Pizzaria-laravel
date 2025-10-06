@extends('layouts.app')

@section('title', 'Movimentações')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-exchange-alt"></i> Movimentações de Estoque</h2>
            <a href="{{ route('historico.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-history"></i> Ver Histórico Completo
            </a>
        </div>

        @if(isset($debug) && $debug)
            <div class="alert alert-info">
                <h5>Debug Mode</h5>
                <strong>POST:</strong>
                <pre>{{ print_r(request()->all(), true) }}</pre>
                <strong>SESSION:</strong>
                <pre>{{ print_r(session()->all(), true) }}</pre>
            </div>
        @endif

        <!-- Formulário de Movimentação -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Nova Movimentação</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('movimentacoes.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pizza_id" class="form-label">Pizza *</label>
                                <select class="form-select @error('pizza_id') is-invalid @enderror"
                                        id="pizza_id" name="pizza_id" required>
                                    <option value="">Selecione uma pizza...</option>
                                    @foreach($pizzas as $pizza)
                                        <option value="{{ $pizza->id }}"
                                                data-estoque="{{ $pizza->estoque_atual }}"
                                                {{ old('pizza_id') == $pizza->id ? 'selected' : '' }}>
                                            {{ $pizza->nome }} (Estoque: {{ $pizza->estoque_atual }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pizza_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select class="form-select @error('tipo') is-invalid @enderror"
                                        id="tipo" name="tipo" required>
                                    <option value="">Selecione...</option>
                                    <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>
                                        Entrada
                                    </option>
                                    <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>
                                        Saída
                                    </option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade *</label>
                                <input type="number" class="form-control @error('quantidade') is-invalid @enderror"
                                       id="quantidade" name="quantidade" min="1"
                                       value="{{ old('quantidade') }}" required>
                                @error('quantidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <input type="text" class="form-control" id="observacoes" name="observacoes"
                                       value="{{ old('observacoes') }}"
                                       placeholder="Ex: Reposição de estoque">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Registrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Movimentações Recentes -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Movimentações Recentes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Pizza</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Usuário</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimentacoes as $movimentacao)
                                <tr>
                                    <td>{{ $movimentacao->data_hora->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ $movimentacao->pizza->nome }}</strong></td>
                                    <td>
                                        @if($movimentacao->tipo === 'entrada')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up"></i> Entrada
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-down"></i> Saída
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $movimentacao->quantidade }}</strong>
                                    </td>
                                    <td>{{ $movimentacao->usuario->nome }}</td>
                                    <td>{{ $movimentacao->observacoes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-exchange-alt fa-2x mb-2"></i><br>
                                        Nenhuma movimentação registrada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $movimentacoes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pizzaSelect = document.getElementById('pizza_id');
    const tipoSelect = document.getElementById('tipo');
    const quantidadeInput = document.getElementById('quantidade');

    // Atualizar validação quando pizza ou tipo mudarem
    function updateValidation() {
        const selectedOption = pizzaSelect.options[pizzaSelect.selectedIndex];
        const estoqueAtual = selectedOption ? parseInt(selectedOption.dataset.estoque) : 0;
        const tipo = tipoSelect.value;
        const quantidade = parseInt(quantidadeInput.value) || 0;

        if (tipo === 'saida' && quantidade > estoqueAtual) {
            quantidadeInput.setCustomValidity(`Quantidade maior que o estoque atual (${estoqueAtual})`);
        } else {
            quantidadeInput.setCustomValidity('');
        }
    }

    pizzaSelect.addEventListener('change', updateValidation);
    tipoSelect.addEventListener('change', updateValidation);
    quantidadeInput.addEventListener('input', updateValidation);
});
</script>
@endsection
