<?php

namespace App\Livewire\Aluno;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssistirCurso extends Component
{
    public $cursoId;
    public $curso;
    public $moduloSelecionadoId = null;
    public $aulaSelecionadaId = null;
    public $moduloSelecionado = null;
    public $aulaSelecionada = null;
    public $progresso = [];

    public function mount($cursoId)
    {
        $this->cursoId = $cursoId;
        $this->carregarCurso();
        
        // Verificar se o aluno está inscrito (admins podem acessar sem inscrição)
        $isAdmin = Auth::user()->isAdmin();
        $isInscrito = Auth::user()->cursos()->where('curso_id', $cursoId)->exists();
        
        if (!$isAdmin && !$isInscrito) {
            session()->flash('error', 'Você não está inscrito neste curso.');
            return redirect()->route('aluno.cursos');
        }

        // Selecionar primeiro módulo e primeira aula por padrão
        $primeiroModulo = $this->curso->modulos->first();
        if ($primeiroModulo) {
            $this->moduloSelecionadoId = $primeiroModulo->id;
            $this->selecionarModulo($primeiroModulo->id);
        }
    }

    public function carregarCurso()
    {
        $this->curso = Curso::with([
            'modulos' => function ($query) {
                $query->where('ativo', true)->orderBy('ordem')->with([
                    'aulas' => function ($q) {
                        $q->where('ativo', true)->orderBy('ordem');
                    }
                ]);
            }
        ])->findOrFail($this->cursoId);

        // Calcular progresso apenas se não for admin (admins não têm progresso)
        if (Auth::user()->isAdmin()) {
            $this->progresso = [
                'total' => $this->curso->getTotalAulas(),
                'assistidas' => 0,
                'percentual' => 0,
                'concluido' => false,
            ];
        } else {
            $this->progresso = $this->curso->getProgressoUsuario(Auth::id());
        }
    }

    public function selecionarModulo($moduloId)
    {
        $this->moduloSelecionadoId = $moduloId;
        $this->moduloSelecionado = Modulo::with(['aulas' => function ($query) {
            $query->where('ativo', true)->orderBy('ordem');
        }])->findOrFail($moduloId);

        // Selecionar primeira aula do módulo
        $primeiraAula = $this->moduloSelecionado->aulas->where('ativo', true)->first();
        if ($primeiraAula) {
            $this->selecionarAula($primeiraAula->id);
        } else {
            $this->aulaSelecionadaId = null;
            $this->aulaSelecionada = null;
        }
    }

    public function selecionarAula($aulaId)
    {
        $this->aulaSelecionadaId = $aulaId;
        $this->aulaSelecionada = Aula::findOrFail($aulaId);
        
        // Verificar se a aula pertence ao módulo selecionado
        if ($this->aulaSelecionada->modulo_id !== $this->moduloSelecionadoId) {
            // Se não, selecionar o módulo correto
            $this->selecionarModulo($this->aulaSelecionada->modulo_id);
        }
    }

    public function marcarComoAssistida($aulaId)
    {
        // Admins não podem marcar aulas como assistidas (apenas visualização)
        if (Auth::user()->isAdmin()) {
            session()->flash('message', 'Como administrador, você está apenas visualizando o curso. A funcionalidade de marcar como assistida está desabilitada.');
            return;
        }

        $aula = Aula::findOrFail($aulaId);
        
        // Verificar se a aula pertence ao curso
        if ($aula->modulo->curso_id !== $this->cursoId) {
            session()->flash('error', 'Aula não pertence a este curso.');
            return;
        }

        $aula->marcarComoAssistida(Auth::id());
        
        // Recarregar progresso
        $this->carregarCurso();
        
        session()->flash('success', 'Aula marcada como assistida!');
    }

    public function desmarcarComoAssistida($aulaId)
    {
        // Admins não podem marcar aulas como assistidas (apenas visualização)
        if (Auth::user()->isAdmin()) {
            session()->flash('message', 'Como administrador, você está apenas visualizando o curso. A funcionalidade de marcar como assistida está desabilitada.');
            return;
        }

        $aula = Aula::findOrFail($aulaId);
        
        if ($aula->modulo->curso_id !== $this->cursoId) {
            session()->flash('error', 'Aula não pertence a este curso.');
            return;
        }

        $aula->desmarcarComoAssistida(Auth::id());
        
        // Recarregar progresso
        $this->carregarCurso();
        
        session()->flash('success', 'Aula desmarcada.');
    }

    public function render()
    {
        return view('livewire.aluno.assistir-curso');
    }
}


