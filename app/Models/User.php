<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
        'cpf',
        'telefone',
        'auto_escola',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tentativas(): HasMany
    {
        // Relacionamento com os resultados dos simulados do aluno
        return $this->hasMany(Tentativa::class);
    }

    public function avisos(): BelongsToMany
    {
        return $this->belongsToMany(Aviso::class, 'aviso_user')
                    ->withPivot('lido_em')
                    ->withTimestamps();
    }

    public function avisosNaoLidos()
    {
        return $this->avisos()
                    ->wherePivot('lido_em', null)
                    ->where('ativo', true)
                    ->where(function ($query) {
                        $query->whereJsonContains('destinatarios', $this->tipo)
                              ->orWhereJsonContains('destinatarios', 'todos');
                    });
    }

    public function avisosPopup()
    {
        return $this->avisos()
                    ->where('mostrar_popup', true)
                    ->where('ativo', true)
                    ->where(function ($query) {
                        $query->whereJsonContains('destinatarios', $this->tipo)
                              ->orWhereJsonContains('destinatarios', 'todos');
                    })
                    ->where(function ($query) {
                        $query->whereNull('data_inicio')
                              ->orWhere('data_inicio', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('data_fim')
                              ->orWhere('data_fim', '>=', now());
                    });
    }

    public function isAdmin(): bool
    {
        return $this->tipo === 'admin';
    }

    public function isAluno(): bool
    {
        return $this->tipo === 'aluno';
    }

    // ==================== RELACIONAMENTOS COM CURSOS ====================

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class)
                    ->withPivot('inscrito_em')
                    ->withTimestamps();
    }

    public function aulasAssistidas(): BelongsToMany
    {
        return $this->belongsToMany(Aula::class)
                    ->withPivot(['assistida', 'assistida_em'])
                    ->withTimestamps();
    }

    public function getProgressoCurso(int $cursoId): array
    {
        $curso = Curso::find($cursoId);
        if (!$curso) {
            return ['total' => 0, 'assistidas' => 0, 'percentual' => 0];
        }
        
        return $curso->getProgressoUsuario($this->id);
    }
}
