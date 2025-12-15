<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso_id',
        'titulo',
        'descricao',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ==================== RELACIONAMENTOS ====================

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(Aula::class)->orderBy('ordem');
    }

    // ==================== SCOPES ====================

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem');
    }

    // ==================== MÉTODOS ====================

    public function getTotalAulas(): int
    {
        return $this->aulas()->where('ativo', true)->count();
    }

    public function getDuracaoTotal(): int
    {
        return $this->aulas()->where('ativo', true)->sum('duracao_minutos') ?? 0;
    }

    public function getDuracaoFormatada(): string
    {
        $minutos = $this->getDuracaoTotal();
        $horas = floor($minutos / 60);
        $mins = $minutos % 60;
        
        if ($horas > 0) {
            return "{$horas}h {$mins}min";
        }
        return "{$mins}min";
    }
}
