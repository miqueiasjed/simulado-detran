<?php

namespace App\Filament\Resources\CursoResource\Pages;

use App\Filament\Resources\CursoResource;
use App\Models\Curso;
use Filament\Resources\Pages\Page;

class GerenciarCurso extends Page
{
    protected static string $resource = CursoResource::class;
    
    protected static string $view = 'filament.resources.curso-resource.pages.gerenciar-curso';
    
    public function getViewData(): array
    {
        // Obter o curso da rota
        $cursoId = request()->route('record');
        $curso = Curso::findOrFail($cursoId);
        
        return [
            'curso' => $curso,
        ];
    }
    
    public function getTitle(): string
    {
        return 'Gerenciar Curso';
    }
}
