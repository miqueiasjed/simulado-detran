# 📚 Roadmap: Sistema de Gerenciamento de Cursos

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Fase 1: Estrutura do Banco de Dados](#fase-1-estrutura-do-banco-de-dados)
3. [Fase 2: Models](#fase-2-models)
4. [Fase 3: Painel Administrativo (Filament)](#fase-3-painel-administrativo-filament)
5. [Fase 4: Área do Aluno (Frontend)](#fase-4-área-do-aluno-frontend)
6. [Fase 5: Funcionalidades Extras](#fase-5-funcionalidades-extras-opcional)
7. [Ordem de Implementação](#ordem-de-implementação-sugerida)
8. [Estrutura de Arquivos](#estrutura-de-arquivos)

---

## Visão Geral

### Estrutura de Relacionamentos

```
┌─────────┐       ┌─────────┐       ┌─────────┐
│  Curso  │ 1───N │ Módulo  │ 1───N │  Aula   │
└─────────┘       └─────────┘       └─────────┘
     │                                   │
     │ N:N                          N:N │
     │                                   │
     └───────────┐       ┌───────────────┘
                 │       │
                 ▼       ▼
              ┌─────────────┐
              │    User     │
              │  (Aluno)    │
              └─────────────┘
```

### Descrição das Entidades

| Entidade | Descrição |
|----------|-----------|
| **Curso** | Agrupamento principal de conteúdo educacional |
| **Módulo** | Subdivisão do curso por temas ou capítulos |
| **Aula** | Conteúdo individual com vídeo (YouTube/Vimeo) |
| **User** | Aluno que acessa e progride nos cursos |

---

## Fase 1: Estrutura do Banco de Dados

### 1.1 Migration - Tabela `cursos`

**Arquivo:** `database/migrations/xxxx_create_cursos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('imagem_capa')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
```

**Campos:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | bigint | Chave primária |
| `titulo` | string(255) | Nome do curso |
| `descricao` | text | Descrição detalhada (opcional) |
| `imagem_capa` | string | Caminho da imagem de capa (opcional) |
| `ativo` | boolean | Se o curso está disponível |
| `ordem` | integer | Ordem de exibição na listagem |
| `timestamps` | datetime | created_at e updated_at |

---

### 1.2 Migration - Tabela `modulos`

**Arquivo:** `database/migrations/xxxx_create_modulos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
```

**Campos:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | bigint | Chave primária |
| `curso_id` | foreignId | Referência ao curso |
| `titulo` | string(255) | Nome do módulo |
| `descricao` | text | Descrição do módulo (opcional) |
| `ordem` | integer | Ordem dentro do curso |
| `ativo` | boolean | Se o módulo está disponível |
| `timestamps` | datetime | created_at e updated_at |

---

### 1.3 Migration - Tabela `aulas`

**Arquivo:** `database/migrations/xxxx_create_aulas_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('video_url'); // YouTube ou Vimeo
            $table->enum('video_plataforma', ['youtube', 'vimeo'])->default('youtube');
            $table->integer('duracao_minutos')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
```

**Campos:**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | bigint | Chave primária |
| `modulo_id` | foreignId | Referência ao módulo |
| `titulo` | string(255) | Nome da aula |
| `descricao` | text | Descrição da aula (opcional) |
| `video_url` | string | URL do vídeo (YouTube/Vimeo) |
| `video_plataforma` | enum | Plataforma do vídeo |
| `duracao_minutos` | integer | Duração em minutos (opcional) |
| `ordem` | integer | Ordem dentro do módulo |
| `ativo` | boolean | Se a aula está disponível |
| `timestamps` | datetime | created_at e updated_at |

---

### 1.4 Migration - Tabela Pivot `curso_user`

**Arquivo:** `database/migrations/xxxx_create_curso_user_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('inscrito_em')->nullable();
            $table->timestamps();
            
            $table->unique(['curso_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_user');
    }
};
```

**Propósito:** Controlar quais alunos têm acesso a quais cursos.

---

### 1.5 Migration - Tabela `aula_user`

**Arquivo:** `database/migrations/xxxx_create_aula_user_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aula_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('assistida')->default(false);
            $table->datetime('assistida_em')->nullable();
            $table->timestamps();
            
            $table->unique(['aula_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_user');
    }
};
```

**Propósito:** Rastrear o progresso do aluno em cada aula.

---

## Fase 2: Models

### 2.1 Model `Curso`

**Arquivo:** `app/Models/Curso.php`

```php
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
```

---

### 2.2 Model `Modulo`

**Arquivo:** `app/Models/Modulo.php`

```php
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
```

---

### 2.3 Model `Aula`

**Arquivo:** `app/Models/Aula.php`

```php
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
```

---

### 2.4 Atualizar Model `User`

**Arquivo:** `app/Models/User.php` (adicionar os relacionamentos)

```php
// Adicionar estes relacionamentos ao User.php existente:

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
```

---

## Fase 3: Painel Administrativo (Filament)

### 3.1 CursoResource

**Arquivo:** `app/Filament/Resources/CursoResource.php`

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoResource\Pages;
use App\Filament\Resources\CursoResource\RelationManagers;
use App\Models\Curso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Cursos';
    protected static ?string $modelLabel = 'Curso';
    protected static ?string $pluralModelLabel = 'Cursos';
    protected static ?string $navigationGroup = 'Conteúdo Educacional';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Curso')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Curso Completo de Legislação'),
                        
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(4)
                            ->placeholder('Descreva o conteúdo e objetivos do curso'),
                        
                        Forms\Components\FileUpload::make('imagem_capa')
                            ->label('Imagem de Capa')
                            ->image()
                            ->directory('cursos')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->helperText('Tamanho recomendado: 800x450px'),
                    ]),
                
                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('ativo')
                            ->label('Curso Ativo')
                            ->default(true)
                            ->helperText('Cursos inativos não aparecem para os alunos'),
                        
                        Forms\Components\TextInput::make('ordem')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(0)
                            ->helperText('Números menores aparecem primeiro'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem_capa')
                    ->label('Capa')
                    ->square()
                    ->size(60),
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('modulos_count')
                    ->label('Módulos')
                    ->counts('modulos')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Ativos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ])
            ->defaultSort('ordem', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCursos::route('/'),
            'create' => Pages\CreateCurso::route('/create'),
            'edit' => Pages\EditCurso::route('/{record}/edit'),
        ];
    }
}
```

---

### 3.2 ModulosRelationManager

**Arquivo:** `app/Filament/Resources/CursoResource/RelationManagers/ModulosRelationManager.php`

```php
<?php

namespace App\Filament\Resources\CursoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ModulosRelationManager extends RelationManager
{
    protected static string $relationship = 'modulos';
    protected static ?string $recordTitleAttribute = 'titulo';
    protected static ?string $title = 'Módulos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título do Módulo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Módulo 1 - Introdução'),
                
                Forms\Components\Textarea::make('descricao')
                    ->label('Descrição')
                    ->rows(3)
                    ->placeholder('Descreva o conteúdo deste módulo'),
                
                Forms\Components\TextInput::make('ordem')
                    ->label('Ordem')
                    ->numeric()
                    ->default(0)
                    ->required(),
                
                Forms\Components\Toggle::make('ativo')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ordem')
                    ->label('#')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('aulas_count')
                    ->label('Aulas')
                    ->counts('aulas')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo Módulo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
                Tables\Actions\Action::make('gerenciarAulas')
                    ->label('Aulas')
                    ->icon('heroicon-o-play')
                    ->url(fn ($record) => route('filament.admin.resources.modulos.edit', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ])
            ->defaultSort('ordem', 'asc')
            ->reorderable('ordem');
    }
}
```

---

### 3.3 ModuloResource (Opcional - para gerenciar aulas)

**Arquivo:** `app/Filament/Resources/ModuloResource.php`

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuloResource\Pages;
use App\Filament\Resources\ModuloResource\RelationManagers;
use App\Models\Modulo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModuloResource extends Resource
{
    protected static ?string $model = Modulo::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Módulos';
    protected static ?string $modelLabel = 'Módulo';
    protected static ?string $pluralModelLabel = 'Módulos';
    protected static ?string $navigationGroup = 'Conteúdo Educacional';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Módulo')
                    ->schema([
                        Forms\Components\Select::make('curso_id')
                            ->label('Curso')
                            ->relationship('curso', 'titulo')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('ordem')
                                    ->label('Ordem')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                
                                Forms\Components\Toggle::make('ativo')
                                    ->label('Ativo')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curso.titulo')
                    ->label('Curso')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Módulo')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('aulas_count')
                    ->label('Aulas')
                    ->counts('aulas')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('curso_id')
                    ->label('Curso')
                    ->relationship('curso', 'titulo')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ])
            ->defaultSort('ordem', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AulasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModulos::route('/'),
            'create' => Pages\CreateModulo::route('/create'),
            'edit' => Pages\EditModulo::route('/{record}/edit'),
        ];
    }
}
```

---

### 3.4 AulasRelationManager

**Arquivo:** `app/Filament/Resources/ModuloResource/RelationManagers/AulasRelationManager.php`

```php
<?php

namespace App\Filament\Resources\ModuloResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AulasRelationManager extends RelationManager
{
    protected static string $relationship = 'aulas';
    protected static ?string $recordTitleAttribute = 'titulo';
    protected static ?string $title = 'Aulas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título da Aula')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Aula 1 - Introdução ao tema'),
                
                Forms\Components\Textarea::make('descricao')
                    ->label('Descrição')
                    ->rows(3)
                    ->placeholder('Descreva o conteúdo desta aula'),
                
                Forms\Components\Section::make('Vídeo')
                    ->schema([
                        Forms\Components\Select::make('video_plataforma')
                            ->label('Plataforma')
                            ->options([
                                'youtube' => 'YouTube',
                                'vimeo' => 'Vimeo',
                            ])
                            ->default('youtube')
                            ->required()
                            ->live(),
                        
                        Forms\Components\TextInput::make('video_url')
                            ->label('URL do Vídeo')
                            ->required()
                            ->url()
                            ->placeholder(fn ($get) => $get('video_plataforma') === 'youtube' 
                                ? 'https://www.youtube.com/watch?v=...' 
                                : 'https://vimeo.com/...')
                            ->helperText('Cole a URL completa do vídeo'),
                        
                        Forms\Components\TextInput::make('duracao_minutos')
                            ->label('Duração (minutos)')
                            ->numeric()
                            ->placeholder('Ex: 15')
                            ->helperText('Duração aproximada em minutos'),
                    ])->columns(3),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('ordem')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        
                        Forms\Components\Toggle::make('ativo')
                            ->label('Ativo')
                            ->default(true),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ordem')
                    ->label('#')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('video_plataforma')
                    ->label('Plataforma')
                    ->badge()
                    ->color(fn ($state) => $state === 'youtube' ? 'danger' : 'info')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('duracao_minutos')
                    ->label('Duração')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : '--')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nova Aula'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-play')
                    ->url(fn ($record) => $record->video_url, shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionadas'),
                ]),
            ])
            ->defaultSort('ordem', 'asc')
            ->reorderable('ordem');
    }
}
```

---

## Fase 4: Área do Aluno (Frontend)

### 4.1 Rotas

**Arquivo:** `routes/web.php` (adicionar)

```php
// Rotas de Cursos para Alunos
Route::middleware(['auth', 'aluno'])->prefix('aluno')->group(function () {
    // ... rotas existentes ...
    
    // Cursos
    Route::get('/cursos', App\Livewire\Aluno\CursosDisponiveis::class)
        ->name('aluno.cursos');
    
    Route::get('/cursos/{curso}', App\Livewire\Aluno\CursoDetalhes::class)
        ->name('aluno.curso.show');
    
    Route::get('/cursos/{curso}/aula/{aula}', App\Livewire\Aluno\PlayerAula::class)
        ->name('aluno.curso.aula');
});
```

---

### 4.2 Livewire - CursosDisponiveis

**Arquivo:** `app/Livewire/Aluno/CursosDisponiveis.php`

```php
<?php

namespace App\Livewire\Aluno;

use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CursosDisponiveis extends Component
{
    public $cursos = [];

    public function mount()
    {
        $this->carregarCursos();
    }

    public function carregarCursos()
    {
        $this->cursos = Curso::ativos()
            ->ordenados()
            ->withCount('modulos')
            ->get()
            ->map(function ($curso) {
                $curso->progresso = $curso->getProgressoUsuario(Auth::id());
                $curso->total_aulas = $curso->getTotalAulas();
                $curso->duracao_formatada = $curso->getDuracaoFormatada();
                return $curso;
            });
    }

    public function render()
    {
        return view('livewire.aluno.cursos-disponiveis', [
            'cursos' => $this->cursos,
        ]);
    }
}
```

---

### 4.3 Livewire - CursoDetalhes

**Arquivo:** `app/Livewire/Aluno/CursoDetalhes.php`

```php
<?php

namespace App\Livewire\Aluno;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CursoDetalhes extends Component
{
    public $cursoId;
    public $curso;
    public $modulos = [];
    public $progresso;
    public $moduloExpandido = null;

    public function mount($curso)
    {
        $this->cursoId = $curso;
        $this->carregarCurso();
    }

    public function carregarCurso()
    {
        $this->curso = Curso::with(['modulos' => function ($query) {
            $query->where('ativo', true)
                  ->orderBy('ordem')
                  ->with(['aulas' => function ($q) {
                      $q->where('ativo', true)->orderBy('ordem');
                  }]);
        }])->findOrFail($this->cursoId);

        $this->modulos = $this->curso->modulos->map(function ($modulo) {
            $modulo->aulas_com_status = $modulo->aulas->map(function ($aula) {
                $aula->assistida = $aula->foiAssistidaPor(Auth::id());
                return $aula;
            });
            return $modulo;
        });

        $this->progresso = $this->curso->getProgressoUsuario(Auth::id());
        
        // Expandir primeiro módulo por padrão
        if ($this->modulos->isNotEmpty() && $this->moduloExpandido === null) {
            $this->moduloExpandido = $this->modulos->first()->id;
        }
    }

    public function toggleModulo($moduloId)
    {
        $this->moduloExpandido = $this->moduloExpandido === $moduloId ? null : $moduloId;
    }

    public function render()
    {
        return view('livewire.aluno.curso-detalhes', [
            'curso' => $this->curso,
            'modulos' => $this->modulos,
            'progresso' => $this->progresso,
        ]);
    }
}
```

---

### 4.4 Livewire - PlayerAula

**Arquivo:** `app/Livewire/Aluno/PlayerAula.php`

```php
<?php

namespace App\Livewire\Aluno;

use App\Models\Aula;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlayerAula extends Component
{
    public $cursoId;
    public $aulaId;
    public $curso;
    public $aula;
    public $modulo;
    public $aulaAnterior;
    public $proximaAula;
    public $assistida = false;

    public function mount($curso, $aula)
    {
        $this->cursoId = $curso;
        $this->aulaId = $aula;
        $this->carregarAula();
    }

    public function carregarAula()
    {
        $this->curso = Curso::findOrFail($this->cursoId);
        $this->aula = Aula::with('modulo.curso')->findOrFail($this->aulaId);
        $this->modulo = $this->aula->modulo;
        
        // Verificar se a aula pertence ao curso
        if ($this->aula->modulo->curso_id !== $this->curso->id) {
            abort(404);
        }

        $this->assistida = $this->aula->foiAssistidaPor(Auth::id());
        
        // Encontrar aula anterior e próxima
        $this->encontrarNavegacao();
    }

    public function encontrarNavegacao()
    {
        $todasAulas = collect();
        
        foreach ($this->curso->modulos()->orderBy('ordem')->get() as $modulo) {
            foreach ($modulo->aulas()->where('ativo', true)->orderBy('ordem')->get() as $aula) {
                $todasAulas->push($aula);
            }
        }
        
        $indiceAtual = $todasAulas->search(function ($aula) {
            return $aula->id === $this->aula->id;
        });
        
        $this->aulaAnterior = $indiceAtual > 0 ? $todasAulas[$indiceAtual - 1] : null;
        $this->proximaAula = $indiceAtual < $todasAulas->count() - 1 ? $todasAulas[$indiceAtual + 1] : null;
    }

    public function marcarComoAssistida()
    {
        $this->aula->marcarComoAssistida(Auth::id());
        $this->assistida = true;
        
        $this->dispatch('aula-assistida', aulaId: $this->aula->id);
    }

    public function desmarcarComoAssistida()
    {
        $this->aula->desmarcarComoAssistida(Auth::id());
        $this->assistida = false;
    }

    public function irParaProxima()
    {
        if ($this->proximaAula) {
            return redirect()->route('aluno.curso.aula', [
                'curso' => $this->cursoId,
                'aula' => $this->proximaAula->id,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.aluno.player-aula', [
            'curso' => $this->curso,
            'aula' => $this->aula,
            'modulo' => $this->modulo,
            'aulaAnterior' => $this->aulaAnterior,
            'proximaAula' => $this->proximaAula,
            'assistida' => $this->assistida,
            'embedUrl' => $this->aula->getEmbedUrl(),
        ]);
    }
}
```

---

### 4.5 View - cursos-disponiveis.blade.php

**Arquivo:** `resources/views/livewire/aluno/cursos-disponiveis.blade.php`

```blade
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Meus Cursos</h1>
    
    @if($cursos->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum curso disponível</h3>
            <p class="mt-2 text-gray-500">Não há cursos disponíveis no momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cursos as $curso)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Imagem de Capa --}}
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-blue-600 relative">
                        @if($curso->imagem_capa)
                            <img src="{{ $curso->getImagemUrl() }}" alt="{{ $curso->titulo }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="h-16 w-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Badge de Progresso --}}
                        @if($curso->progresso['percentual'] > 0)
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $curso->progresso['percentual'] }}% concluído
                            </div>
                        @endif
                    </div>
                    
                    {{-- Conteúdo --}}
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $curso->titulo }}</h3>
                        
                        @if($curso->descricao)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $curso->descricao }}</p>
                        @endif
                        
                        {{-- Informações --}}
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                {{ $curso->modulos_count }} módulos
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                {{ $curso->total_aulas }} aulas
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $curso->duracao_formatada }}
                            </span>
                        </div>
                        
                        {{-- Barra de Progresso --}}
                        @if($curso->progresso['percentual'] > 0)
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $curso->progresso['percentual'] }}%"></div>
                            </div>
                        @endif
                        
                        {{-- Botão --}}
                        <a href="{{ route('aluno.curso.show', $curso->id) }}" 
                           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            @if($curso->progresso['percentual'] == 0)
                                Começar Curso
                            @elseif($curso->progresso['percentual'] >= 100)
                                Revisar Curso
                            @else
                                Continuar
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
```

---

### 4.6 View - curso-detalhes.blade.php

**Arquivo:** `resources/views/livewire/aluno/curso-detalhes.blade.php`

```blade
<div class="container mx-auto px-4 py-8">
    {{-- Header do Curso --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-start gap-6">
            @if($curso->imagem_capa)
                <img src="{{ $curso->getImagemUrl() }}" alt="{{ $curso->titulo }}" 
                     class="w-48 h-32 object-cover rounded-lg hidden md:block">
            @endif
            
            <div class="flex-1">
                <a href="{{ route('aluno.cursos') }}" class="text-blue-600 hover:underline text-sm mb-2 inline-block">
                    ← Voltar aos cursos
                </a>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $curso->titulo }}</h1>
                
                @if($curso->descricao)
                    <p class="text-gray-600 mb-4">{{ $curso->descricao }}</p>
                @endif
                
                {{-- Progresso --}}
                <div class="flex items-center gap-4">
                    <div class="flex-1 max-w-xs">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progresso</span>
                            <span>{{ $progresso['assistidas'] }}/{{ $progresso['total'] }} aulas</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $progresso['percentual'] }}%"></div>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-green-600">{{ $progresso['percentual'] }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Lista de Módulos --}}
    <div class="space-y-4">
        @foreach($modulos as $modulo)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- Header do Módulo --}}
                <button wire:click="toggleModulo({{ $modulo->id }})" 
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-10 h-10 flex items-center justify-center font-bold">
                            {{ $loop->iteration }}
                        </div>
                        <div class="text-left">
                            <h3 class="font-semibold text-gray-800">{{ $modulo->titulo }}</h3>
                            <p class="text-sm text-gray-500">{{ $modulo->aulas->count() }} aulas • {{ $modulo->getDuracaoFormatada() }}</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ $moduloExpandido === $modulo->id ? 'rotate-180' : '' }}" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                {{-- Lista de Aulas --}}
                @if($moduloExpandido === $modulo->id)
                    <div class="border-t divide-y">
                        @foreach($modulo->aulas_com_status as $aula)
                            <a href="{{ route('aluno.curso.aula', ['curso' => $curso->id, 'aula' => $aula->id]) }}"
                               class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition-colors">
                                {{-- Ícone de Status --}}
                                <div class="flex-shrink-0">
                                    @if($aula->assistida)
                                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Info da Aula --}}
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">{{ $aula->titulo }}</h4>
                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $aula->getDuracaoFormatada() }}
                                        </span>
                                        <span class="capitalize px-2 py-0.5 rounded text-xs {{ $aula->video_plataforma === 'youtube' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $aula->video_plataforma }}
                                        </span>
                                    </div>
                                </div>
                                
                                {{-- Seta --}}
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
```

---

### 4.7 View - player-aula.blade.php

**Arquivo:** `resources/views/livewire/aluno/player-aula.blade.php`

```blade
<div class="min-h-screen bg-gray-100">
    {{-- Header --}}
    <div class="bg-white shadow">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('aluno.curso.show', $curso->id) }}" class="text-blue-600 hover:underline text-sm">
                        ← Voltar para {{ $curso->titulo }}
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">{{ $aula->titulo }}</h1>
                    <p class="text-sm text-gray-500">{{ $modulo->titulo }}</p>
                </div>
                
                {{-- Botão Marcar como Assistida --}}
                <button wire:click="{{ $assistida ? 'desmarcarComoAssistida' : 'marcarComoAssistida' }}"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors
                               {{ $assistida ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    @if($assistida)
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Aula Concluída
                    @else
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Marcar como Concluída
                    @endif
                </button>
            </div>
        </div>
    </div>
    
    {{-- Player de Vídeo --}}
    <div class="container mx-auto px-4 py-6">
        <div class="bg-black rounded-lg overflow-hidden shadow-lg">
            <div class="relative w-full" style="padding-bottom: 56.25%;">
                @if($embedUrl)
                    <iframe src="{{ $embedUrl }}" 
                            class="absolute inset-0 w-full h-full"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-800 text-white">
                        <p>Não foi possível carregar o vídeo</p>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Descrição da Aula --}}
        @if($aula->descricao)
            <div class="bg-white rounded-lg shadow mt-6 p-6">
                <h3 class="font-semibold text-gray-800 mb-2">Sobre esta aula</h3>
                <p class="text-gray-600">{{ $aula->descricao }}</p>
            </div>
        @endif
        
        {{-- Navegação --}}
        <div class="flex items-center justify-between mt-6">
            @if($aulaAnterior)
                <a href="{{ route('aluno.curso.aula', ['curso' => $curso->id, 'aula' => $aulaAnterior->id]) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <div class="text-left">
                        <p class="text-xs text-gray-500">Anterior</p>
                        <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $aulaAnterior->titulo }}</p>
                    </div>
                </a>
            @else
                <div></div>
            @endif
            
            @if($proximaAula)
                <a href="{{ route('aluno.curso.aula', ['curso' => $curso->id, 'aula' => $proximaAula->id]) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors">
                    <div class="text-right">
                        <p class="text-xs text-blue-200">Próxima</p>
                        <p class="text-sm font-medium line-clamp-1">{{ $proximaAula->titulo }}</p>
                    </div>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <a href="{{ route('aluno.curso.show', $curso->id) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Concluir Curso
                </a>
            @endif
        </div>
    </div>
</div>
```

---

## Fase 5: Funcionalidades Extras (Opcional)

### 5.1 Controle de Acesso a Cursos

Adicionar campo `gratuito` na tabela `cursos` e verificar inscrição:

```php
// Migration adicional
$table->boolean('gratuito')->default(true);
$table->decimal('preco', 10, 2)->nullable();

// Middleware ou verificação no Livewire
if (!$curso->gratuito && !$user->cursos->contains($curso->id)) {
    // Redirecionar para página de compra/inscrição
}
```

### 5.2 Certificado de Conclusão

- Criar tabela `certificados`
- Gerar PDF com dados do aluno e curso
- Verificar 100% de conclusão

### 5.3 Widgets no Dashboard Admin

```php
// app/Filament/Widgets/CursosStatsWidget.php
- Total de cursos ativos
- Total de alunos inscritos
- Aulas mais assistidas
- Cursos com maior taxa de conclusão
```

---

## Ordem de Implementação Sugerida

| # | Etapa | Descrição | Prioridade | Tempo Est. |
|---|-------|-----------|------------|------------|
| 1 | Migrations | Criar todas as tabelas | 🔴 Alta | 30min |
| 2 | Models | Curso, Modulo, Aula + relacionamentos | 🔴 Alta | 1h |
| 3 | CursoResource | CRUD de cursos no Filament | 🔴 Alta | 1h |
| 4 | ModulosRelationManager | Gerenciar módulos dentro do curso | 🔴 Alta | 45min |
| 5 | ModuloResource | CRUD de módulos (opcional) | 🟡 Média | 30min |
| 6 | AulasRelationManager | Gerenciar aulas dentro do módulo | 🔴 Alta | 45min |
| 7 | Livewire - CursosDisponiveis | Lista de cursos para aluno | 🟡 Média | 1h |
| 8 | Livewire - CursoDetalhes | Módulos e aulas do curso | 🟡 Média | 1h30 |
| 9 | Livewire - PlayerAula | Player de vídeo + progresso | 🟡 Média | 1h30 |
| 10 | Sistema de Progresso | Rastreamento de aulas assistidas | 🟢 Baixa | 1h |
| 11 | Controle de Acesso | Cursos gratuitos/pagos | 🟢 Baixa | 2h |
| 12 | Certificados | Geração de certificados | 🟢 Baixa | 3h |

**Tempo total estimado:** ~14 horas

---

## Estrutura de Arquivos

```
📦 projeto/
├── 📁 database/migrations/
│   ├── xxxx_create_cursos_table.php
│   ├── xxxx_create_modulos_table.php
│   ├── xxxx_create_aulas_table.php
│   ├── xxxx_create_curso_user_table.php
│   └── xxxx_create_aula_user_table.php
│
├── 📁 app/Models/
│   ├── Curso.php
│   ├── Modulo.php
│   ├── Aula.php
│   └── User.php (atualizar)
│
├── 📁 app/Filament/Resources/
│   ├── CursoResource.php
│   ├── 📁 CursoResource/
│   │   ├── 📁 Pages/
│   │   │   ├── ListCursos.php
│   │   │   ├── CreateCurso.php
│   │   │   └── EditCurso.php
│   │   └── 📁 RelationManagers/
│   │       └── ModulosRelationManager.php
│   │
│   ├── ModuloResource.php (opcional)
│   └── 📁 ModuloResource/
│       ├── 📁 Pages/
│       │   ├── ListModulos.php
│       │   ├── CreateModulo.php
│       │   └── EditModulo.php
│       └── 📁 RelationManagers/
│           └── AulasRelationManager.php
│
├── 📁 app/Livewire/Aluno/
│   ├── CursosDisponiveis.php
│   ├── CursoDetalhes.php
│   └── PlayerAula.php
│
├── 📁 resources/views/livewire/aluno/
│   ├── cursos-disponiveis.blade.php
│   ├── curso-detalhes.blade.php
│   └── player-aula.blade.php
│
└── 📁 routes/
    └── web.php (adicionar rotas)
```

---

## Comandos para Criar a Estrutura

```bash
# Criar migrations
php artisan make:migration create_cursos_table
php artisan make:migration create_modulos_table
php artisan make:migration create_aulas_table
php artisan make:migration create_curso_user_table
php artisan make:migration create_aula_user_table

# Criar models
php artisan make:model Curso
php artisan make:model Modulo
php artisan make:model Aula

# Criar Filament Resources
php artisan make:filament-resource Curso --generate
php artisan make:filament-resource Modulo --generate

# Criar RelationManagers
php artisan make:filament-relation-manager CursoResource modulos titulo
php artisan make:filament-relation-manager ModuloResource aulas titulo

# Criar Livewire Components
php artisan make:livewire Aluno/CursosDisponiveis
php artisan make:livewire Aluno/CursoDetalhes
php artisan make:livewire Aluno/PlayerAula

# Executar migrations
php artisan migrate
```

---

## Considerações Finais

### Vantagens desta Arquitetura

1. **Modular**: Cada componente tem responsabilidade única
2. **Escalável**: Fácil adicionar novas funcionalidades
3. **Reutilizável**: Models e métodos podem ser usados em outros contextos
4. **Consistente**: Segue o padrão do projeto existente (Filament + Livewire)

### Próximos Passos Sugeridos

1. Implementar as migrations e models primeiro
2. Testar CRUD no Filament
3. Criar interface do aluno
4. Adicionar funcionalidades extras conforme necessidade

---

*Documento criado em: {{ date('d/m/Y') }}*
*Versão: 1.0*