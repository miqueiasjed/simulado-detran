<?php

namespace App\Livewire\Aluno;

use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CursosDisponiveis extends Component
{
    public $cursosInscritos = [];

    public function mount()
    {
        // Carregar IDs dos cursos em que o aluno está inscrito
        $this->cursosInscritos = Auth::user()->cursos()->pluck('curso_id')->toArray();
    }

    public function inscrever($cursoId)
    {
        $curso = Curso::findOrFail($cursoId);
        
        // Verificar se já está inscrito
        if (in_array($cursoId, $this->cursosInscritos)) {
            session()->flash('message', 'Você já está inscrito neste curso.');
            return;
        }

        // Inscrever o aluno
        Auth::user()->cursos()->attach($cursoId, [
            'inscrito_em' => now(),
        ]);

        $this->cursosInscritos[] = $cursoId;
        
        session()->flash('success', "Você se inscreveu no curso: {$curso->titulo}");
    }

    public function render()
    {
        $cursos = Curso::ativos()
            ->ordenados()
            ->with(['modulos' => function ($query) {
                $query->orderBy('ordem')->withCount('aulas');
            }])
            ->get();

        // Para admins, mostrar todos os cursos como "meus cursos" para visualização
        if (Auth::user()->isAdmin()) {
            $meusCursos = $cursos->map(function ($curso) {
                $curso->progresso = [
                    'total' => $curso->getTotalAulas(),
                    'assistidas' => 0,
                    'percentual' => 0,
                    'concluido' => false,
                ];
                return $curso;
            });
        } else {
            $meusCursos = Auth::user()->cursos()
                ->with(['modulos' => function ($query) {
                    $query->orderBy('ordem')->withCount('aulas');
                }])
                ->get()
                ->map(function ($curso) {
                    $progresso = $curso->getProgressoUsuario(Auth::id());
                    $curso->progresso = $progresso;
                    return $curso;
                });
        }

        return view('livewire.aluno.cursos-disponiveis', [
            'cursos' => $cursos,
            'meusCursos' => $meusCursos,
        ]);
    }
}


