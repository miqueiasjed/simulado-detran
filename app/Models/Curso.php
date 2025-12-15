<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'imagem_capa',
        'ativo',
        'ordem',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ==================== RELACIONAMENTOS ====================

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('inscrito_em')
                    ->withTimestamps();
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
        return $this->modulos->sum(function ($modulo) {
            return $modulo->aulas()->where('ativo', true)->count();
        });
    }

    public function getDuracaoTotal(): int
    {
        return $this->modulos->sum(function ($modulo) {
            return $modulo->aulas()->where('ativo', true)->sum('duracao_minutos');
        });
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

    public function getProgressoUsuario(int $userId): array
    {
        $totalAulas = $this->getTotalAulas();
        $aulasAssistidas = 0;

        foreach ($this->modulos as $modulo) {
            $aulasAssistidas += $modulo->aulas()
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('assistida', true);
                })
                ->count();
        }

        $percentual = $totalAulas > 0 ? round(($aulasAssistidas / $totalAulas) * 100, 1) : 0;

        return [
            'total' => $totalAulas,
            'assistidas' => $aulasAssistidas,
            'percentual' => $percentual,
            'concluido' => $percentual >= 100,
        ];
    }

    public function getImagemUrl(): ?string
    {
        return $this->imagem_capa ? asset('storage/' . $this->imagem_capa) : null;
    }
}
