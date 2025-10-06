<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'movimentacoes';  // Corrigindo o nome da tabela

    protected $fillable = [
        'pizza_id',
        'usuario_id',
        'data_hora',
        'tipo',
        'quantidade',
        'observacoes',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'quantidade' => 'integer',
    ];

    public function pizza(): BelongsTo
    {
        return $this->belongsTo(Pizza::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scope para entradas
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    // Scope para saídas
    public function scopeSaidas($query)
    {
        return $query->where('tipo', 'saida');
    }
}
