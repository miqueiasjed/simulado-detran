<?php

namespace App\Livewire\Aluno;

use App\Models\Simulado;
use App\Models\Questao;
use App\Models\Tentativa;
use App\Models\Resposta;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSimulado extends Component
{
    public $simuladoId;
    public $questoes = [];
    public $respostas = [];
    public $indice = 0;
    public $finalizado = false;
    public $tentativaId;
    public $resultado = null;
    public $statusQuestoes = [];
    public $tempoInicio;
    public $tempoLimite;
    public $tempoRestante;

    public $simulado;
    
    public function mount($simuladoId)
    {
        $this->simuladoId = $simuladoId;
        $this->simulado = Simulado::with('questoes')->findOrFail($simuladoId);
        $this->questoes = $this->simulado->questoes->toArray();
        $this->statusQuestoes = array_fill(0, count($this->questoes), 'nao_respondida');
        $this->tempoLimite = (int) ($this->simulado->tempo_limite * 60);
        
        // Verificar se já existe um resultado em andamento
        $resultadoExistente = Tentativa::where('user_id', Auth::id())
            ->where('simulado_id', $this->simuladoId)
            ->where('status', 'em_andamento')
            ->first();

        if ($resultadoExistente) {
            // Carregar resultado existente
            $this->tentativaId = $resultadoExistente->id;
            // Garantir que tempoInicio seja sempre um objeto Carbon para evitar problemas de serialização do Livewire
            $this->tempoInicio = Carbon::parse($resultadoExistente->iniciado_em);
            
            $respostasSalvas = Resposta::where('tentativa_id', $resultadoExistente->id)->get();
            foreach ($respostasSalvas as $resposta) {
                $this->respostas[$resposta->questao_id] = $resposta->resposta_escolhida;
                
                // Atualizar status das questões
                $indiceQuestao = array_search($resposta->questao_id, array_column($this->questoes, 'id'));
                if ($indiceQuestao !== false) {
                    if ($resposta->resposta_escolhida === 'pulado') {
                        $this->statusQuestoes[$indiceQuestao] = 'pulado';
                    } elseif ($resposta->resposta_escolhida === 'nao_respondida') {
                        $this->statusQuestoes[$indiceQuestao] = 'nao_respondida';
                    } else {
                        $this->statusQuestoes[$indiceQuestao] = 'respondida';
                    }
                }
            }
            
            // Encontrar próxima questão não respondida
            $this->indice = array_search('nao_respondida', $this->statusQuestoes);
            if ($this->indice === false) {
                $this->indice = 0; // Se todas foram respondidas, voltar ao início
            }
        } else {
            // Criar novo resultado
            // Capturar timestamp antes de qualquer operação de banco para evitar perda de tempo
            $tempoInicioExato = Carbon::now();
            $this->tempoInicio = $tempoInicioExato;
            $this->tentativaId = Tentativa::create([
                'user_id' => Auth::id(),
                'simulado_id' => $this->simuladoId,
                'status' => 'em_andamento',
                'iniciado_em' => $this->tempoInicio,
            ])->id;
        }
        
        // Calcular tempo restante após todas as operações de banco
        // Para novas tentativas, usar o timestamp capturado antes das operações de banco
        // Para tentativas existentes, usar o timestamp do banco convertido para Carbon
        $tempoInicioCarbon = Carbon::parse($this->tempoInicio);
        $agora = Carbon::now();
        $this->tempoRestante = (int) max(0, $this->tempoLimite - $agora->diffInSeconds($tempoInicioCarbon));
    }

    public function responder($resposta)
    {
        $questao = $this->questoes[$this->indice];
        $this->respostas[$questao['id']] = $resposta;
        $this->statusQuestoes[$this->indice] = 'respondida';
        
        // Salvar resposta no banco de dados
        $questaoModel = Questao::find($questao['id']);
        $correta = $questaoModel->resposta_correta === $resposta;
        
        // Verificar se já existe uma resposta para esta questão
        $respostaExistente = Resposta::where('tentativa_id', $this->tentativaId)
            ->where('questao_id', $questao['id'])
            ->first();
        
        if ($respostaExistente) {
            // Atualizar resposta existente
            $respostaExistente->update([
                'resposta_escolhida' => $resposta,
                'correta' => $correta,
            ]);
        } else {
            // Criar nova resposta
            Resposta::create([
                'tentativa_id' => $this->tentativaId,
                'questao_id' => $questao['id'],
                'resposta_escolhida' => $resposta,
                'correta' => $correta,
            ]);
        }
        
        // Progressão automática para próxima questão
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        } else {
            // Se for a última questão, finalizar automaticamente
            $this->finalizar();
        }
    }

    public function proxima()
    {
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        }
    }

    public function anterior()
    {
        if ($this->indice > 0) {
            $this->indice--;
        }
    }

    public function irParaQuestao($indice)
    {
        if ($indice >= 0 && $indice < count($this->questoes)) {
            $this->indice = $indice;
        }
    }

    public function pular()
    {
        $this->statusQuestoes[$this->indice] = 'pulado';
        
        // Salvar status de "pulado" no banco
        $questao = $this->questoes[$this->indice];
        $respostaExistente = Resposta::where('tentativa_id', $this->tentativaId)
            ->where('questao_id', $questao['id'])
            ->first();
        
        if (!$respostaExistente) {
            // Criar registro para questão pulada
            Resposta::create([
                'tentativa_id' => $this->tentativaId,
                'questao_id' => $questao['id'],
                'resposta_escolhida' => 'pulado', // Questão pulada
                'correta' => false,
            ]);
        }
        
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        }
    }

    public function finalizar()
    {
        $tentativa = Tentativa::find($this->tentativaId);
        $acertos = 0;
        $total = count($this->questoes);
        $respostasDetalhadas = [];
        
        // Buscar todas as respostas já salvas
        $respostasSalvas = Resposta::where('tentativa_id', $tentativa->id)->get();
        $respostasSalvasIds = $respostasSalvas->pluck('questao_id')->toArray();
        
        // Processar todas as questões do simulado
        foreach ($this->questoes as $questao) {
            $respostaSalva = $respostasSalvas->where('questao_id', $questao['id'])->first();
            
            if ($respostaSalva) {
                // Questão já foi respondida ou pulada
                $questaoModel = Questao::find($questao['id']);
                $correta = $respostaSalva->correta;
                if ($correta) $acertos++;
                
                // Guardar detalhes para revisão
                $respostasDetalhadas[] = [
                    'questao' => $questaoModel,
                    'resposta_escolhida' => $respostaSalva->resposta_escolhida,
                    'correta' => $correta,
                    'resposta_correta' => $questaoModel->resposta_correta,
                    'explicacao' => $questaoModel->explicacao,
                ];
            } else {
                // Questão não foi respondida - criar registro
                $questaoModel = Questao::find($questao['id']);
                Resposta::create([
                    'tentativa_id' => $this->tentativaId,
                    'questao_id' => $questao['id'],
                    'resposta_escolhida' => 'nao_respondida',
                    'correta' => false,
                ]);
                
                // Guardar detalhes para revisão
                $respostasDetalhadas[] = [
                    'questao' => $questaoModel,
                    'resposta_escolhida' => 'nao_respondida',
                    'correta' => false,
                    'resposta_correta' => $questaoModel->resposta_correta,
                    'explicacao' => $questaoModel->explicacao,
                ];
            }
        }
        
        $percentual = $total > 0 ? round(($acertos / $total) * 100, 2) : 0;
        $nota = $total > 0 ? round(($acertos / $total) * 10, 1) : 0; // Nota de 0 a 10
        
        $tentativa->status = 'finalizada';
        $tentativa->finalizado_em = now();
        $tentativa->acertos = $acertos;
        $tentativa->erros = $total - $acertos;
        $tentativa->pontuacao = $percentual;
        $tentativa->save();
        
        $this->resultado = [
            'acertos' => $acertos,
            'total' => $total,
            'erros' => $total - $acertos,
            'percentual' => $percentual,
            'nota' => $nota,
            'respostas_detalhadas' => $respostasDetalhadas,
        ];
        $this->finalizado = true;
    }

    public function render()
    {
        // Garantir que tempoInicio seja sempre Carbon antes de calcular diffInSeconds
        // Isso evita problemas quando Livewire deserializa DateTime como string
        $tempoInicioCarbon = Carbon::parse($this->tempoInicio);
        $this->tempoRestante = (int) max(0, $this->tempoLimite - Carbon::now()->diffInSeconds($tempoInicioCarbon));
        
        if ($this->tempoRestante <= 0 && !$this->finalizado) {
            $this->finalizar();
        }
        
        return view('livewire.aluno.quiz-simulado', [
            'questaoAtual' => $this->questoes[$this->indice] ?? null,
            'indice' => $this->indice,
            'total' => count($this->questoes),
            'finalizado' => $this->finalizado,
            'resultado' => $this->resultado,
            'statusQuestoes' => $this->statusQuestoes,
            'tempoRestante' => $this->tempoRestante,
            'tempoLimite' => $this->tempoLimite,
            'simulado' => $this->simulado,
        ]);
    }
}
