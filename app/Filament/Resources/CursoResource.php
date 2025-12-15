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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Cursos';
    protected static ?string $modelLabel = 'Curso';
    protected static ?string $pluralModelLabel = 'Cursos';
    protected static ?string $navigationGroup = 'Conteúdo Educacional';
    protected static ?int $navigationSort = 1;

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
                Tables\Actions\Action::make('manage')
                    ->label('Gerenciar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('primary')
                    ->url(fn ($record) => static::getUrl('manage', ['record' => $record])),
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
            'manage' => Pages\GerenciarCurso::route('/{record}/manage'),
        ];
    }
}
