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
