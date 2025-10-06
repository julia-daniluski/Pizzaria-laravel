<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pizza extends Model
{
    public $timestamps = false; 
    use HasFactory;

    protected $fillable = [
        'nome',
        'ingredientes',
        'preco',
        'tamanho',
        'categoria',
        'estoque_minimo',
        'ativo',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'ativo' => 'boolean',
        'estoque_minimo' => 'integer',
    ];

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }

    // Calcula estoque atual (entradas - saídas)
    public function getEstoqueAtualAttribute(): int
    {
        $entradas = $this->movimentacoes()->where('tipo', 'entrada')->sum('quantidade');
        $saidas = $this->movimentacoes()->where('tipo', 'saida')->sum('quantidade');
        return (int) $entradas - (int) $saidas;
    }

    // Verifica se estoque está baixo
    public function getEstoqueBaixoAttribute(): bool
    {
        return $this->estoque_atual <= $this->estoque_minimo;
    }

    // Scope para pizzas ativas
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
}
