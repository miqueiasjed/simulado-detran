<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
        'modulo_id',
        'titulo',
        'descricao',
        'video_url',
        'video_plataforma',
        'duracao_minutos',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ==================== RELACIONAMENTOS ====================

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot(['assistida', 'assistida_em'])
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

    public function isYoutube(): bool
    {
        return $this->video_plataforma === 'youtube' || 
               str_contains($this->video_url, 'youtube.com') || 
               str_contains($this->video_url, 'youtu.be');
    }

    public function isVimeo(): bool
    {
        return $this->video_plataforma === 'vimeo' || 
               str_contains($this->video_url, 'vimeo.com');
    }

    public function getVideoId(): ?string
    {
        if ($this->isYoutube()) {
            // Padrões de URL do YouTube
            // https://www.youtube.com/watch?v=VIDEO_ID
            // https://youtu.be/VIDEO_ID
            // https://www.youtube.com/embed/VIDEO_ID
            
            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
                return $matches[1];
            }
        }
        
        if ($this->isVimeo()) {
            // Padrões de URL do Vimeo
            // https://vimeo.com/VIDEO_ID
            // https://player.vimeo.com/video/VIDEO_ID
            
            if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->video_url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    public function getEmbedUrl(): ?string
    {
        $videoId = $this->getVideoId();
        
        if (!$videoId) {
            return null;
        }
        
        if ($this->isYoutube()) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        
        if ($this->isVimeo()) {
            return "https://player.vimeo.com/video/{$videoId}";
        }
        
        return null;
    }

    public function getDuracaoFormatada(): string
    {
        if (!$this->duracao_minutos) {
            return '--:--';
        }
        
        $horas = floor($this->duracao_minutos / 60);
        $minutos = $this->duracao_minutos % 60;
        
        if ($horas > 0) {
            return sprintf('%d:%02d:00', $horas, $minutos);
        }
        
        return sprintf('%d:00', $minutos);
    }

    public function foiAssistidaPor(int $userId): bool
    {
        return $this->users()
                    ->where('user_id', $userId)
                    ->where('assistida', true)
                    ->exists();
    }

    public function marcarComoAssistida(int $userId): void
    {
        $this->users()->syncWithoutDetaching([
            $userId => [
                'assistida' => true,
                'assistida_em' => now(),
            ]
        ]);
    }

    public function desmarcarComoAssistida(int $userId): void
    {
        $this->users()->syncWithoutDetaching([
            $userId => [
                'assistida' => false,
                'assistida_em' => null,
            ]
        ]);
    }
}
