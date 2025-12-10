<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimuladoResource\Pages;
use App\Filament\Resources\SimuladoResource\RelationManagers;
use App\Models\Simulado;
use App\Services\SimuladoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SimuladoResource extends Resource
{
    protected static ?string $model = Simulado::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Simulados';
    
    protected static ?string $modelLabel = 'Simulado';
    
    protected static ?string $pluralModelLabel = 'Simulados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Simulado')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->placeholder('Ex: Simulado Completo DETRAN')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Descreva o conteúdo e objetivo deste simulado')
                            ->rows(3)
                            ->maxLength(1000),
                        
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('tempo_limite')
                                    ->label('Tempo Limite (minutos)')
                                    ->numeric()
                                    ->default(30)
                                    ->minValue(1)
                                    ->required(),
                                
                                Forms\Components\TextInput::make('numero_questoes')
                                    ->label('Número de Questões')
                                    ->numeric()
                                    ->default(30)
                                    ->minValue(1)
                                    ->required(),
                                
                                Forms\Components\TextInput::make('nota_minima_aprovacao')
                                    ->label('Nota Mínima para Aprovação')
                                    ->numeric()
                                    ->default(7.0)
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->step(0.1)
                                    ->suffix('/10')
                                    ->helperText('Nota mínima (0-10) que o aluno precisa atingir para ser aprovado')
                                    ->rules([
                                        'required',
                                        'numeric',
                                        'min:0',
                                        'max:10',
                                        'regex:/^\d+(\.\d)?$/',
                                    ])
                                    ->required(),
                            ]),
                        
                        Forms\Components\Toggle::make('ativo')
                            ->label('Simulado Ativo')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Adicionar subqueries para calcular média e total de tentativas de uma vez
                // Isso evita N+1 queries ao calcular a média para cada simulado
                $query->withCount([
                    'tentativas as total_tentativas' => function ($q) {
                        $q->where('status', 'finalizada')
                          ->whereNotNull('finalizado_em');
                    }
                ])->addSelect(DB::raw('(
                    SELECT COALESCE(ROUND(AVG(pontuacao / 10.0), 1), 0)
                    FROM tentativas
                    WHERE tentativas.simulado_id = simulados.id
                    AND tentativas.status = \'finalizada\'
                    AND tentativas.finalizado_em IS NOT NULL
                ) as media_notas_calculada'));
            })
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempo_limite')
                    ->label('Tempo (min)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_questoes')
                    ->label('Questões')
                    ->sortable(),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('questoes_count')
                    ->label('Questões Configuradas')
                    ->counts('questoes'),
                Tables\Columns\TextColumn::make('nota_minima_aprovacao')
                    ->label('Nota Mínima')
                    ->formatStateUsing(function ($state) {
                        // Proteção contra NULL: usar a mesma lógica de isAprovado() para consistência
                        $notaMinima = $state !== null ? (float) $state : 7.0;
                        return number_format($notaMinima, 1, ',', '.') . '/10';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('media_notas')
                    ->label('Média Geral')
                    ->state(function (Simulado $record): string {
                        $total = $record->total_tentativas ?? 0;
                        $media = $record->media_notas_calculada ?? 0.0;
                        
                        if ($total === 0) {
                            return 'Sem tentativas';
                        }
                        return number_format($media, 1, ',', '.') . '/10 (' . $total . ' tentativas)';
                    })
                    ->sortable(false),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Ativos'),
            ])
            ->actions([
                Tables\Actions\Action::make('gerarQuestoes')
                    ->label('Gerar Questões')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->disabled()
                    ->tooltip('Funcionalidade em desenvolvimento - depende da configuração de categorias')
                    ->visible(false), // Temporariamente oculto
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestoesRelationManager::class,
            RelationManagers\TentativasRelationManager::class, // Resultados dos Alunos
            RelationManagers\CategoriasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSimulados::route('/'),
            'create' => Pages\CreateSimulado::route('/create'),
            'edit' => Pages\EditSimulado::route('/{record}/edit'),
            'adicionar-questoes-modal' => Pages\AdicionarQuestoesModal::route('/{record}/adicionar-questoes-modal'),
        ];
    }
}
