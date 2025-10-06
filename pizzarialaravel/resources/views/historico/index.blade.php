@extends('layouts.app')

@section('title', 'Histórico de Movimentações')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-history"></i> Histórico de Movimentações</h2>
            <a href="{{ route('movimentacoes.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-exchange-alt"></i> Nova Movimentação
            </a>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('historico.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pizza_id" class="form-label">Pizza</label>
                                <select class="form-select" id="pizza_id" name="pizza_id">
                                    <option value="">Todas as pizzas</option>
                                    @foreach($pizzas as $pizza)
                                        <option value="{{ $pizza->id }}"
                                                {{ request('pizza_id') == $pizza->id ? 'selected' : '' }}>
                                            {{ $pizza->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="">Todos</option>
                                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>
                                        Entrada
                                    </option>
                                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>
                                        Saída
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="de" class="form-label">De</label>
                                <input type="date" class="form-control" id="de" name="de"
                                       value="{{ request('de') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="ate" class="form-label">Até</label>
                                <input type="date" class="form-control" id="ate" name="ate"
                                       value="{{ request('ate') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                    <a href="{{ route('historico.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Movimentações -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Movimentações
                    @if(request()->hasAny(['pizza_id', 'tipo', 'de', 'ate']))
                        <small class="text-muted">(Filtradas)</small>
                    @endif
                </h5>
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
                                    <td>
                                        <strong>{{ $movimentacao->data_hora->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ $movimentacao->data_hora->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $movimentacao->pizza->nome }}</strong><br>
                                        <small class="text-muted">{{ $movimentacao->pizza->categoria }}</small>
                                    </td>
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
                                        <strong class="{{ $movimentacao->tipo === 'entrada' ? 'text-success' : 'text-danger' }}">
                                            {{ $movimentacao->tipo === 'entrada' ? '+' : '-' }}{{ $movimentacao->quantidade }}
                                        </strong>
                                    </td>
                                    <td>{{ $movimentacao->usuario->nome }}</td>
                                    <td>{{ $movimentacao->observacoes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-history fa-2x mb-2"></i><br>
                                        Nenhuma movimentação encontrada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $movimentacoes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
