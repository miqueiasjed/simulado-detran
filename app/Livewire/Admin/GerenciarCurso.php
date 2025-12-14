<?php

namespace App\Livewire\Admin;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;

class GerenciarCurso extends Component
{
    use WithFileUploads;

    // Curso
    public $cursoId;
    public $curso;
    
    // Módulos expandidos
    public $modulosExpandidos = [];
    
    // Modais
    public $showModalCurso = false;
    public $showModalModulo = false;
    public $showModalAula = false;
    public $showModalConfirmacao = false;
    
    // Dados do formulário do Curso
    public $cursoTitulo;
    public $cursoDescricao;
    public $cursoImagem;
    public $cursoImagemAtual;
    public $cursoAtivo = true;
    public $cursoOrdem = 0;
    
    // Dados do formulário do Módulo
    public $moduloId = null;
    public $moduloTitulo;
    public $moduloDescricao;
    public $moduloAtivo = true;
    
    // Dados do formulário da Aula
    public $aulaId = null;
    public $aulaModuloId;
    public $aulaTitulo;
    public $aulaDescricao;
    public $aulaVideoUrl;
    public $aulaVideoPlataforma = 'youtube';
    public $aulaDuracao;
    public $aulaAtivo = true;
    
    // Confirmação de exclusão
    public $confirmacaoTipo; // 'modulo' ou 'aula'
    public $confirmacaoId;
    public $confirmacaoNome;
    public $confirmacaoExtra;

    public function mount($curso)
    {
        $this->cursoId = $curso;
        $this->carregarCurso();
        
        // Expandir primeiro módulo por padrão
        if ($this->curso->modulos->isNotEmpty()) {
            $this->modulosExpandidos[] = $this->curso->modulos->first()->id;
        }
    }

    public function carregarCurso()
    {
        $this->curso = Curso::with(['modulos' => function ($query) {
            $query->orderBy('ordem')->with(['aulas' => function ($q) {
                $q->orderBy('ordem');
            }]);
        }])->findOrFail($this->cursoId);
    }

    // ==================== TOGGLE MÓDULO ====================
    
    public function toggleModulo($moduloId)
    {
        if (in_array($moduloId, $this->modulosExpandidos)) {
            $this->modulosExpandidos = array_diff($this->modulosExpandidos, [$moduloId]);
        } else {
            $this->modulosExpandidos[] = $moduloId;
        }
    }

    // ==================== MODAL CURSO ====================
    
    public function abrirModalCurso()
    {
        $this->cursoTitulo = $this->curso->titulo;
        $this->cursoDescricao = $this->curso->descricao;
        $this->cursoImagemAtual = $this->curso->imagem_capa;
        $this->cursoAtivo = $this->curso->ativo;
        $this->cursoOrdem = $this->curso->ordem;
        $this->cursoImagem = null;
        
        $this->showModalCurso = true;
    }
    
    public function salvarCurso()
    {
        $this->validate([
            'cursoTitulo' => 'required|string|max:255',
            'cursoDescricao' => 'nullable|string',
            'cursoImagem' => 'nullable|image|max:2048',
            'cursoOrdem' => 'required|integer|min:0',
        ]);
        
        $dados = [
            'titulo' => $this->cursoTitulo,
            'descricao' => $this->cursoDescricao,
            'ativo' => $this->cursoAtivo,
            'ordem' => $this->cursoOrdem,
        ];
        
        if ($this->cursoImagem) {
            $dados['imagem_capa'] = $this->cursoImagem->store('cursos', 'public');
        }
        
        $this->curso->update($dados);
        
        $this->showModalCurso = false;
        $this->carregarCurso();
        
        Notification::make()
            ->title('Curso atualizado com sucesso!')
            ->success()
            ->send();
    }

    // ==================== MODAL MÓDULO ====================
    
    public function abrirModalNovoModulo()
    {
        $this->resetModuloForm();
        $this->showModalModulo = true;
    }
    
    public function abrirModalEditarModulo($moduloId)
    {
        $modulo = Modulo::findOrFail($moduloId);
        
        $this->moduloId = $modulo->id;
        $this->moduloTitulo = $modulo->titulo;
        $this->moduloDescricao = $modulo->descricao;
        $this->moduloAtivo = $modulo->ativo;
        
        $this->showModalModulo = true;
    }
    
    public function salvarModulo()
    {
        $this->validate([
            'moduloTitulo' => 'required|string|max:255',
            'moduloDescricao' => 'nullable|string',
        ]);
        
        if ($this->moduloId) {
            // Editar
            $modulo = Modulo::findOrFail($this->moduloId);
            $modulo->update([
                'titulo' => $this->moduloTitulo,
                'descricao' => $this->moduloDescricao,
                'ativo' => $this->moduloAtivo,
            ]);
            $mensagem = 'Módulo atualizado com sucesso!';
        } else {
            // Criar
            $ultimaOrdem = $this->curso->modulos()->max('ordem') ?? 0;
            
            Modulo::create([
                'curso_id' => $this->cursoId,
                'titulo' => $this->moduloTitulo,
                'descricao' => $this->moduloDescricao,
                'ativo' => $this->moduloAtivo,
                'ordem' => $ultimaOrdem + 1,
            ]);
            $mensagem = 'Módulo criado com sucesso!';
        }
        
        $this->showModalModulo = false;
        $this->resetModuloForm();
        $this->carregarCurso();
        
        Notification::make()
            ->title($mensagem)
            ->success()
            ->send();
    }
    
    public function resetModuloForm()
    {
        $this->moduloId = null;
        $this->moduloTitulo = '';
        $this->moduloDescricao = '';
        $this->moduloAtivo = true;
    }

    // ==================== MODAL AULA ====================
    
    public function abrirModalNovaAula($moduloId)
    {
        $this->resetAulaForm();
        $this->aulaModuloId = $moduloId;
        $this->showModalAula = true;
    }
    
    public function abrirModalEditarAula($aulaId)
    {
        $aula = Aula::findOrFail($aulaId);
        
        $this->aulaId = $aula->id;
        $this->aulaModuloId = $aula->modulo_id;
        $this->aulaTitulo = $aula->titulo;
        $this->aulaDescricao = $aula->descricao;
        $this->aulaVideoUrl = $aula->video_url;
        $this->aulaVideoPlataforma = $aula->video_plataforma;
        $this->aulaDuracao = $aula->duracao_minutos;
        $this->aulaAtivo = $aula->ativo;
        
        $this->showModalAula = true;
    }
    
    public function salvarAula()
    {
        $this->validate([
            'aulaTitulo' => 'required|string|max:255',
            'aulaDescricao' => 'nullable|string',
            'aulaVideoUrl' => 'required|url',
            'aulaVideoPlataforma' => 'required|in:youtube,vimeo',
            'aulaDuracao' => 'nullable|integer|min:1',
        ]);
        
        if ($this->aulaId) {
            // Editar
            $aula = Aula::findOrFail($this->aulaId);
            $aula->update([
                'titulo' => $this->aulaTitulo,
                'descricao' => $this->aulaDescricao,
                'video_url' => $this->aulaVideoUrl,
                'video_plataforma' => $this->aulaVideoPlataforma,
                'duracao_minutos' => $this->aulaDuracao,
                'ativo' => $this->aulaAtivo,
            ]);
            $mensagem = 'Aula atualizada com sucesso!';
        } else {
            // Criar
            $modulo = Modulo::findOrFail($this->aulaModuloId);
            $ultimaOrdem = $modulo->aulas()->max('ordem') ?? 0;
            
            Aula::create([
                'modulo_id' => $this->aulaModuloId,
                'titulo' => $this->aulaTitulo,
                'descricao' => $this->aulaDescricao,
                'video_url' => $this->aulaVideoUrl,
                'video_plataforma' => $this->aulaVideoPlataforma,
                'duracao_minutos' => $this->aulaDuracao,
                'ativo' => $this->aulaAtivo,
                'ordem' => $ultimaOrdem + 1,
            ]);
            $mensagem = 'Aula criada com sucesso!';
        }
        
        $this->showModalAula = false;
        $this->resetAulaForm();
        $this->carregarCurso();
        
        Notification::make()
            ->title($mensagem)
            ->success()
            ->send();
    }
    
    public function resetAulaForm()
    {
        $this->aulaId = null;
        $this->aulaModuloId = null;
        $this->aulaTitulo = '';
        $this->aulaDescricao = '';
        $this->aulaVideoUrl = '';
        $this->aulaVideoPlataforma = 'youtube';
        $this->aulaDuracao = null;
        $this->aulaAtivo = true;
    }

    // ==================== EXCLUSÃO ====================
    
    public function confirmarExclusaoModulo($moduloId)
    {
        $modulo = Modulo::withCount('aulas')->findOrFail($moduloId);
        
        $this->confirmacaoTipo = 'modulo';
        $this->confirmacaoId = $moduloId;
        $this->confirmacaoNome = $modulo->titulo;
        $this->confirmacaoExtra = $modulo->aulas_count;
        
        $this->showModalConfirmacao = true;
    }
    
    public function confirmarExclusaoAula($aulaId)
    {
        $aula = Aula::findOrFail($aulaId);
        
        $this->confirmacaoTipo = 'aula';
        $this->confirmacaoId = $aulaId;
        $this->confirmacaoNome = $aula->titulo;
        
        $this->showModalConfirmacao = true;
    }
    
    public function executarExclusao()
    {
        if ($this->confirmacaoTipo === 'modulo') {
            Modulo::destroy($this->confirmacaoId);
            $mensagem = 'Módulo excluído com sucesso!';
        } else {
            Aula::destroy($this->confirmacaoId);
            $mensagem = 'Aula excluída com sucesso!';
        }
        
        $this->showModalConfirmacao = false;
        $this->carregarCurso();
        
        Notification::make()
            ->title($mensagem)
            ->success()
            ->send();
    }

    // ==================== REORDENAÇÃO ====================
    
    public function reordenarModulo($moduloId, $novaOrdem)
    {
        $modulo = Modulo::findOrFail($moduloId);
        $ordemAntiga = $modulo->ordem;
        
        if ($novaOrdem > $ordemAntiga) {
            // Movendo para baixo
            Modulo::where('curso_id', $this->cursoId)
                ->where('ordem', '>', $ordemAntiga)
                ->where('ordem', '<=', $novaOrdem)
                ->decrement('ordem');
        } else {
            // Movendo para cima
            Modulo::where('curso_id', $this->cursoId)
                ->where('ordem', '>=', $novaOrdem)
                ->where('ordem', '<', $ordemAntiga)
                ->increment('ordem');
        }
        
        $modulo->update(['ordem' => $novaOrdem]);
        $this->carregarCurso();
    }
    
    public function reordenarAula($aulaId, $novaOrdem, $novoModuloId = null)
    {
        $aula = Aula::findOrFail($aulaId);
        $moduloAntigoId = $aula->modulo_id;
        $ordemAntiga = $aula->ordem;
        
        // Se mudou de módulo
        if ($novoModuloId && $novoModuloId != $moduloAntigoId) {
            // Reordenar módulo antigo
            Aula::where('modulo_id', $moduloAntigoId)
                ->where('ordem', '>', $ordemAntiga)
                ->decrement('ordem');
            
            // Abrir espaço no novo módulo
            Aula::where('modulo_id', $novoModuloId)
                ->where('ordem', '>=', $novaOrdem)
                ->increment('ordem');
            
            $aula->update([
                'modulo_id' => $novoModuloId,
                'ordem' => $novaOrdem,
            ]);
        } else {
            // Mesmo módulo, só reordenar
            if ($novaOrdem > $ordemAntiga) {
                Aula::where('modulo_id', $moduloAntigoId)
                    ->where('ordem', '>', $ordemAntiga)
                    ->where('ordem', '<=', $novaOrdem)
                    ->decrement('ordem');
            } else {
                Aula::where('modulo_id', $moduloAntigoId)
                    ->where('ordem', '>=', $novaOrdem)
                    ->where('ordem', '<', $ordemAntiga)
                    ->increment('ordem');
            }
            
            $aula->update(['ordem' => $novaOrdem]);
        }
        
        $this->carregarCurso();
    }

    // ==================== HELPERS ====================
    
    public function getTotalAulas()
    {
        return $this->curso->modulos->sum(function ($modulo) {
            return $modulo->aulas->count();
        });
    }
    
    public function getDuracaoTotal()
    {
        $minutos = $this->curso->modulos->sum(function ($modulo) {
            return $modulo->aulas->sum('duracao_minutos');
        });
        
        $horas = floor($minutos / 60);
        $mins = $minutos % 60;
        
        if ($horas > 0) {
            return "{$horas}h {$mins}min";
        }
        return "{$mins}min";
    }

    public function render()
    {
        return view('livewire.admin.gerenciar-curso');
    }
}
