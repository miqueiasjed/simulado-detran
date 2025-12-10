<?php

namespace App\Filament\Resources\SimuladoResource\RelationManagers;

use App\Models\Questao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class QuestoesRelationManager extends RelationManager
{
    protected static string $relationship = 'questoes';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Garantir que os checkboxes sejam marcados corretamente ao editar
        $respostaCorreta = $data['resposta_correta'] ?? 'a';
        
        $data['resposta_correta_a'] = $respostaCorreta === 'a';
        $data['resposta_correta_b'] = $respostaCorreta === 'b';
        $data['resposta_correta_c'] = $respostaCorreta === 'c';
        $data['resposta_correta_d'] = $respostaCorreta === 'd';
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remover campos virtuais dos checkboxes antes de salvar
        // Apenas o campo 'resposta_correta' deve ser salvo no banco
        unset($data['resposta_correta_a']);
        unset($data['resposta_correta_b']);
        unset($data['resposta_correta_c']);
        unset($data['resposta_correta_d']);
        
        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('pergunta')
                    ->label('Pergunta')
                    ->placeholder('Digite a pergunta da questão')
                    ->required()
                    ->rows(3),
                Forms\Components\Section::make('Alternativas')
                    ->description('Marque a checkbox ao lado da alternativa correta')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('alternativa_a')
                                    ->label('Alternativa A')
                                    ->placeholder('Digite a alternativa A')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_a')
                                    ->label('Correta')
                                    ->dehydrated(false)
                                    ->live()
                                    ->default(false)
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        // Carregar valor original do banco quando editar
                                        // afterStateHydrated é chamado após a hidratação e tem acesso ao $record
                                        if ($record && $record->resposta_correta === 'a') {
                                            $component->state(true);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Quando marcado: desmarca as outras opções e atualiza o campo Hidden
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'a');
                                        } else {
                                            // Quando desmarcado: verifica qual checkbox está marcado e atualiza o campo Hidden
                                            if ($get('resposta_correta_b')) {
                                                $set('resposta_correta', 'b');
                                            } elseif ($get('resposta_correta_c')) {
                                                $set('resposta_correta', 'c');
                                            } elseif ($get('resposta_correta_d')) {
                                                $set('resposta_correta', 'd');
                                            } else {
                                                // Se nenhum estiver marcado, garante que sempre haja um checkbox marcado
                                                // Marca o checkbox 'a' e atualiza o campo Hidden para manter consistência visual
                                                $set('resposta_correta_a', true);
                                                $set('resposta_correta', 'a');
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_b')
                                    ->label('Alternativa B')
                                    ->placeholder('Digite a alternativa B')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_b')
                                    ->label('Correta')
                                    ->dehydrated(false)
                                    ->live()
                                    ->default(false)
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        // Carregar valor original do banco quando editar
                                        // afterStateHydrated é chamado após a hidratação e tem acesso ao $record
                                        if ($record && $record->resposta_correta === 'b') {
                                            $component->state(true);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Quando marcado: desmarca as outras opções e atualiza o campo Hidden
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'b');
                                        } else {
                                            // Quando desmarcado: verifica qual checkbox está marcado e atualiza o campo Hidden
                                            if ($get('resposta_correta_a')) {
                                                $set('resposta_correta', 'a');
                                            } elseif ($get('resposta_correta_c')) {
                                                $set('resposta_correta', 'c');
                                            } elseif ($get('resposta_correta_d')) {
                                                $set('resposta_correta', 'd');
                                            } else {
                                                // Se nenhum estiver marcado, garante que sempre haja um checkbox marcado
                                                // Marca o checkbox 'b' e atualiza o campo Hidden para manter consistência visual
                                                $set('resposta_correta_b', true);
                                                $set('resposta_correta', 'b');
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_c')
                                    ->label('Alternativa C')
                                    ->placeholder('Digite a alternativa C')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_c')
                                    ->label('Correta')
                                    ->dehydrated(false)
                                    ->live()
                                    ->default(false)
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        // Carregar valor original do banco quando editar
                                        // afterStateHydrated é chamado após a hidratação e tem acesso ao $record
                                        if ($record && $record->resposta_correta === 'c') {
                                            $component->state(true);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Quando marcado: desmarca as outras opções e atualiza o campo Hidden
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'c');
                                        } else {
                                            // Quando desmarcado: verifica qual checkbox está marcado e atualiza o campo Hidden
                                            if ($get('resposta_correta_a')) {
                                                $set('resposta_correta', 'a');
                                            } elseif ($get('resposta_correta_b')) {
                                                $set('resposta_correta', 'b');
                                            } elseif ($get('resposta_correta_d')) {
                                                $set('resposta_correta', 'd');
                                            } else {
                                                // Se nenhum estiver marcado, garante que sempre haja um checkbox marcado
                                                // Marca o checkbox 'c' e atualiza o campo Hidden para manter consistência visual
                                                $set('resposta_correta_c', true);
                                                $set('resposta_correta', 'c');
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_d')
                                    ->label('Alternativa D')
                                    ->placeholder('Digite a alternativa D')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_d')
                                    ->label('Correta')
                                    ->dehydrated(false)
                                    ->live()
                                    ->default(false)
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        // Carregar valor original do banco quando editar
                                        // afterStateHydrated é chamado após a hidratação e tem acesso ao $record
                                        if ($record && $record->resposta_correta === 'd') {
                                            $component->state(true);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Quando marcado: desmarca as outras opções e atualiza o campo Hidden
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta', 'd');
                                        } else {
                                            // Quando desmarcado: verifica qual checkbox está marcado e atualiza o campo Hidden
                                            if ($get('resposta_correta_a')) {
                                                $set('resposta_correta', 'a');
                                            } elseif ($get('resposta_correta_b')) {
                                                $set('resposta_correta', 'b');
                                            } elseif ($get('resposta_correta_c')) {
                                                $set('resposta_correta', 'c');
                                            } else {
                                                // Se nenhum estiver marcado, garante que sempre haja um checkbox marcado
                                                // Marca o checkbox 'd' e atualiza o campo Hidden para manter consistência visual
                                                $set('resposta_correta_d', true);
                                                $set('resposta_correta', 'd');
                                            }
                                        }
                                    }),
                            ])
                    ]),
                Forms\Components\Hidden::make('resposta_correta')
                    ->default('a')
                    ->live(),
                Forms\Components\Textarea::make('explicacao')
                    ->label('Explicação (opcional)')
                    ->placeholder('Explique por que esta é a resposta correta')
                    ->rows(2),
                Forms\Components\Toggle::make('ativo')
                    ->label('Ativa')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pergunta')
                    ->label('Pergunta')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('alternativa_a')->label('A'),
                Tables\Columns\TextColumn::make('alternativa_b')->label('B'),
                Tables\Columns\TextColumn::make('alternativa_c')->label('C'),
                Tables\Columns\TextColumn::make('alternativa_d')->label('D'),
                Tables\Columns\TextColumn::make('resposta_correta')
                    ->label('Correta')
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativa')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nova Questão'),
                Tables\Actions\Action::make('adicionarQuestoesExistentes')
                    ->label('Adicionar Questões Existentes')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->url(fn () => route('filament.admin.resources.simulados.adicionar-questoes-modal', ['record' => $this->getOwnerRecord()]))
                    ->openUrlInNewTab(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionadas'),
                ]),
            ]);
    }
}
