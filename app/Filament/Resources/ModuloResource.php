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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
