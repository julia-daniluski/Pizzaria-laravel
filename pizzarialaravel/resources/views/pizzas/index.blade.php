@extends('layouts.app')

@section('title', 'Pizzas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-pizza-slice"></i> Pizzas</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastrarModal">
                <i class="fas fa-plus"></i> Nova Pizza
            </button>
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

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ingredientes</th>
                                <th>Preço</th>
                                <th>Tamanho</th>
                                <th>Categoria</th>
                                <th>Estoque Atual</th>
                                <th>Estoque Mínimo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pizzas as $pizza)
                                <tr>
                                    <td><strong>{{ $pizza->nome }}</strong></td>
                                    <td>{{ Str::limit($pizza->ingredientes, 50) }}</td>
                                    <td>R$ {{ number_format($pizza->preco, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $pizza->tamanho }}</span>
                                    </td>
                                    <td>{{ $pizza->categoria }}</td>
                                    <td>
                                        <span class="badge {{ $pizza->estoque_baixo ? 'bg-danger' : 'bg-success' }}">
                                            {{ $pizza->estoque_atual }}
                                        </span>
                                    </td>
                                    <td>{{ $pizza->estoque_minimo }}</td>
                                    <td>
                                        @if($pizza->ativo)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editarModal"
                                                    data-pizza='@json($pizza)'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('pizzas.destroy', $pizza) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir esta pizza?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="fas fa-pizza-slice fa-2x mb-2"></i><br>
                                        Nenhuma pizza cadastrada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $pizzas->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar -->
<div class="modal fade" id="cadastrarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('pizzas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Nova Pizza</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço *</label>
                                <input type="text" class="form-control" id="preco" name="preco"
                                       placeholder="25,90" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tamanho" class="form-label">Tamanho *</label>
                                <select class="form-select" id="tamanho" name="tamanho" required>
                                    <option value="">Selecione...</option>
                                    <option value="Pequena">Pequena</option>
                                    <option value="Media">Média</option>
                                    <option value="Grande">Grande</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria *</label>
                                <input type="text" class="form-control" id="categoria" name="categoria" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ingredientes" class="form-label">Ingredientes *</label>
                        <textarea class="form-control" id="ingredientes" name="ingredientes" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estoque_minimo" class="form-label">Estoque Mínimo *</label>
                                <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo"
                                       min="0" value="5" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" checked>
                                    <label class="form-check-label" for="ativo">
                                        Pizza ativa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editarForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Pizza</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="edit_nome" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_preco" class="form-label">Preço *</label>
                                <input type="text" class="form-control" id="edit_preco" name="preco" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tamanho" class="form-label">Tamanho *</label>
                                <select class="form-select" id="edit_tamanho" name="tamanho" required>
                                    <option value="Pequena">Pequena</option>
                                    <option value="Media">Média</option>
                                    <option value="Grande">Grande</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_categoria" class="form-label">Categoria *</label>
                                <input type="text" class="form-control" id="edit_categoria" name="categoria" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ingredientes" class="form-label">Ingredientes *</label>
                        <textarea class="form-control" id="edit_ingredientes" name="ingredientes" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_estoque_minimo" class="form-label">Estoque Mínimo *</label>
                                <input type="number" class="form-control" id="edit_estoque_minimo" name="estoque_minimo"
                                       min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="edit_ativo" name="ativo" value="1">
                                    <label class="form-check-label" for="edit_ativo">
                                        Pizza ativa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preencher modal de edição
    document.getElementById('editarModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const pizza = JSON.parse(button.getAttribute('data-pizza'));

        document.getElementById('editarForm').action = `/pizzas/${pizza.id}`;
        document.getElementById('edit_nome').value = pizza.nome;
        document.getElementById('edit_preco').value = pizza.preco;
        document.getElementById('edit_tamanho').value = pizza.tamanho;
        document.getElementById('edit_categoria').value = pizza.categoria;
        document.getElementById('edit_ingredientes').value = pizza.ingredientes;
        document.getElementById('edit_estoque_minimo').value = pizza.estoque_minimo;
        document.getElementById('edit_ativo').checked = pizza.ativo;
    });
});
</script>
@endsection


