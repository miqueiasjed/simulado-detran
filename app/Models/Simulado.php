<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Simulado extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'tempo_limite',
        'numero_questoes',
        'nota_minima_aprovacao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'nota_minima_aprovacao' => 'decimal:1',
    ];

    public function questoes(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'questao_simulado')
                    ->withTimestamps();
    }

    public function tentativas(): HasMany
    {
        // Relacionamento com os resultados dos alunos neste simulado
        return $this->hasMany(Tentativa::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'simulado_categorias')
                    ->withPivot('quantidade_questoes')
                    ->withTimestamps();
    }

    public function questoesAtivas(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'questao_simulado')
                    ->where('ativo', true)
                    ->withTimestamps();
    }

    public function gerarQuestoesAleatorias(): Collection
    {
        $questoes = collect();
        
        foreach ($this->categorias as $categoria) {
            $quantidade = $categoria->pivot->quantidade_questoes;
            $questoesCategoria = Questao::where('categoria_id', $categoria->id)
                                       ->where('ativo', true)
                                       ->inRandomOrder()
                                       ->limit($quantidade)
                                       ->get();
            
            $questoes = $questoes->merge($questoesCategoria);
        }
        
        return $questoes->shuffle();
    }

    /**
     * Calcula a média de notas de todas as tentativas finalizadas deste simulado
     */
    public function getMediaNotas(): float
    {
        $tentativas = $this->tentativas()
            ->where('status', 'finalizada')
            ->whereNotNull('finalizado_em')
            ->get();

        if ($tentativas->isEmpty()) {
            return 0.0;
        }

        $somaNotas = $tentativas->sum(function ($tentativa) {
            return $tentativa->getNota();
        });

        return round($somaNotas / $tentativas->count(), 1);
    }

    /**
     * Retorna a quantidade de tentativas finalizadas
     */
    public function getTotalTentativas(): int
    {
        return $this->tentativas()
            ->where('status', 'finalizada')
            ->whereNotNull('finalizado_em')
            ->count();
    }

    /**
     * Verifica se uma nota é suficiente para aprovação
     */
    public function isAprovado(float $nota): bool
    {
        // Proteção contra NULL: se nota_minima_aprovacao for NULL, usar padrão de 7.0
        $notaMinima = $this->nota_minima_aprovacao !== null ? (float) $this->nota_minima_aprovacao : 7.0;
        return $nota >= $notaMinima;
    }

    /**
     * Retorna a nota mínima formatada
     */
    public function getNotaMinimaFormatada(): string
    {
        // Proteção contra NULL: usar a mesma lógica de isAprovado() para consistência
        $notaMinima = $this->nota_minima_aprovacao !== null ? (float) $this->nota_minima_aprovacao : 7.0;
        return number_format($notaMinima, 1, ',', '.');
    }
}
