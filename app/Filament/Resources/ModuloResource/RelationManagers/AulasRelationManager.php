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
