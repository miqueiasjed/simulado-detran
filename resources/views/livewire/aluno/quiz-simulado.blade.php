<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800">
    <div class="container mx-auto px-3 sm:px-4 py-3 sm:py-6 max-w-4xl">
        
        {{-- Feedback da Média Mínima --}}
        @if(!$finalizado && $total > 0 && isset($simulado))
            @php
                // Usar !== null para tratar 0.0 como valor válido
                $notaMinima = $simulado->nota_minima_aprovacao !== null ? (float) $simulado->nota_minima_aprovacao : 7.0;
            @endphp
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-4 sm:p-5 mb-4 sm:mb-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-100 mb-1">Meta para Aprovação</h3>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                            Você precisa atingir nota mínima de <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">{{ number_format($notaMinima, 1, ',', '.') }}</span> para ser aprovado neste simulado
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Cronômetro na parte superior --}}
        @if(!$finalizado && $total > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-3 sm:p-4 mb-4 sm:mb-6 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-base">
                            {{ $indice + 1 }}
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-100">Questão {{ $indice + 1 }} de {{ $total }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                @php
                                    $respondidas = count(array_filter($statusQuestoes ?? [], fn($status) => $status === 'respondida'));
                                    $puladas = count(array_filter($statusQuestoes ?? [], fn($status) => $status === 'pulado'));
                                @endphp
                                {{ $respondidas }} respondidas • {{ $puladas }} puladas • {{ round((($indice + 1) / $total) * 100, 1) }}% concluído
                                @if($respondidas > 0)
                                    <span class="inline-flex items-center gap-1 ml-2 px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Continuando...
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    {{-- Cronômetro --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="text-right">
                            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Tempo Restante</div>
                            <div class="text-lg sm:text-2xl font-bold {{ $tempoRestante <= 300 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }}" data-cronometro>
                                {{ floor($tempoRestante / 60) }}:{{ str_pad(floor($tempoRestante % 60), 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="w-2 h-2 sm:w-3 sm:h-3 {{ $tempoRestante <= 300 ? 'bg-red-500' : 'bg-green-500' }} rounded-full animate-pulse"></div>
                    </div>
                </div>
                
                {{-- Barra de progresso sutil --}}
                <div class="mt-2 sm:mt-3">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-1.5 sm:h-2 rounded-full transition-all duration-500 ease-out" 
                             style="width: {{ (($indice + 1) / $total) * 100 }}%">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Conteúdo Principal --}}
        @if($finalizado)
            {{-- Tela de Resultado --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-center text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Simulado Concluído!</h2>
                    <p class="text-xl opacity-90">Parabéns pela dedicação nos estudos</p>
                </div>
                
                @if($resultado)
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 text-center border border-emerald-200 dark:border-emerald-800">
                                <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">{{ $resultado['acertos'] }}</div>
                                <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Acertos</div>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center border border-red-200 dark:border-red-800">
                                <div class="text-3xl font-bold text-red-600 dark:text-red-400 mb-2">{{ $resultado['erros'] }}</div>
                                <div class="text-sm font-medium text-red-700 dark:text-red-300">Erros</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 text-center border border-blue-200 dark:border-blue-800">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $resultado['percentual'] }}%</div>
                                <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Aproveitamento</div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-6 text-center border border-purple-200 dark:border-purple-800">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $resultado['nota'] }}</div>
                                <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Nota (0-10)</div>
                            </div>
                        </div>
                        
                        {{-- Seção de Revisão das Questões --}}
                        @if(count($resultado['respostas_detalhadas']) > 0)
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Revisão do Simulado</h3>
                                
                                <div class="space-y-6">
                                    @foreach($resultado['respostas_detalhadas'] as $index => $resposta)
                                        @php
                                            $questao = $resposta['questao'];
                                            $correta = $resposta['correta'];
                                            $respostaEscolhida = $resposta['resposta_escolhida'];
                                            $respostaCorreta = $resposta['resposta_correta'];
                                        @endphp
                                        
                                        <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 border-2 {{ $correta ? 'border-emerald-200 dark:border-emerald-800' : 'border-red-200 dark:border-red-800' }}">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $correta ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                                                        {{ $index + 1 }}
                                                    </div>
<div>
                                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                            Questão {{ $index + 1 }}
                                                        </h4>
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $correta ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                                                            @if($correta)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Correta
                                                            @else
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Incorreta
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $questao->pergunta }}</p>
                                            </div>
                                            
                                            <div class="space-y-2 mb-4">
                                                @foreach(['a', 'b', 'c', 'd'] as $alt)
                                                    @php
                                                        $isEscolhida = $respostaEscolhida === $alt;
                                                        $isCorreta = $respostaCorreta === $alt;
                                                        $baseClasses = 'flex items-center gap-3 p-3 rounded-lg border-2 ';
                                                        
                                                        if ($isCorreta) 
                                                            $classes = $baseClasses . 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
                                                        elseif ($isEscolhida && !$correta) 
                                                            $classes = $baseClasses . 'border-red-500 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300';
                                                        else 
                                                            $classes = $baseClasses . 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400';
                                                    @endphp
                                                    <div class="{{ $classes }}">
                                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center font-bold text-sm {{ $isCorreta ? 'border-emerald-500 bg-emerald-500 text-white' : ($isEscolhida && !$correta ? 'border-red-500 bg-red-500 text-white' : 'border-gray-300 dark:border-gray-500 text-gray-500 dark:text-gray-400') }}">
                                                            {{ strtoupper($alt) }}
                                                        </div>
                                                        <span class="flex-1">{{ $questao->{'alternativa_' . $alt} }}</span>
                                                        @if($isCorreta)
                                                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            @if($questao->explicacao)
                                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                                    <h5 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Explicação:</h5>
                                                    <p class="text-blue-700 dark:text-blue-300 text-sm">{{ $questao->explicacao }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="text-center">
                            <a href="/aluno/simulados" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Voltar para Simulados
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        @elseif($questaoAtual)
            {{-- Questão Atual --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-3xl shadow-lg sm:shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                {{-- Status da Questão --}}
                <div class="bg-gradient-to-r from-slate-100 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-4 sm:px-8 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        @php
                            $status = $statusQuestoes[$indice] ?? 'nao_respondida';
                        @endphp
                        @if($status === 'respondida')
                            <span class="inline-flex items-center gap-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2 sm:px-3 py-1 rounded-full text-xs font-medium">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Respondida
                            </span>
                        @elseif($status === 'pulado')
                            <span class="inline-flex items-center gap-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-2 sm:px-3 py-1 rounded-full text-xs font-medium">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Pulada
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 sm:px-3 py-1 rounded-full text-xs font-medium">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Não respondida
                            </span>
                        @endif
                        
                        {{-- Navegação rápida --}}
                        <div class="flex gap-1 sm:gap-2 overflow-x-auto pb-2 sm:pb-0">
                            @foreach(range(0, $total - 1) as $i)
                                @php
                                    $status = $statusQuestoes[$i] ?? 'nao_respondida';
                                    $isAtual = $i === $indice;
                                    $baseClasses = 'w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg font-semibold text-xs border-2 transition-all duration-300 flex-shrink-0 ';
                                    
                                    if ($isAtual) 
                                        $classes = $baseClasses . 'border-blue-500 bg-blue-500 text-white ';
                                    elseif ($status === 'respondida') 
                                        $classes = $baseClasses . 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 ';
                                    elseif ($status === 'pulado') 
                                        $classes = $baseClasses . 'border-amber-400 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 ';
                                    else 
                                        $classes = $baseClasses . 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 ';
                                @endphp
                                <button wire:click="irParaQuestao({{ $i }})" 
                                        class="{{ $classes }}"
                                        title="Questão {{ $i+1 }}">
                                    {{ $i + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pergunta --}}
                <div class="p-4 sm:p-8">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6 sm:mb-8 leading-relaxed">
                        {{ $questaoAtual['pergunta'] }}
                    </h2>

                    {{-- Alternativas --}}
                    <div class="space-y-3 sm:space-y-4 mb-6 sm:mb-8">
                        @foreach(['a', 'b', 'c', 'd'] as $alt)
                            @php
                                $marcada = isset($respostas[$questaoAtual['id']]) && $respostas[$questaoAtual['id']] === $alt;
                                $baseClasses = 'group relative block w-full text-left p-4 sm:p-6 rounded-xl sm:rounded-2xl border-2 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer ';
                                
                                if($marcada) 
                                    $altClasses = $baseClasses . 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 shadow-lg shadow-blue-200/50 dark:shadow-blue-900/50 ring-2 ring-blue-200 dark:ring-blue-800 ';
                                else 
                                    $altClasses = $baseClasses . 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md ';
                            @endphp
                            <button wire:click="responder('{{ $alt }}')" class="{{ $altClasses }}">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 rounded-full border-2 {{ $marcada ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-500 group-hover:border-gray-400' }} flex items-center justify-center font-bold text-sm {{ $marcada ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ strtoupper($alt) }}
                                    </div>
                                    <div class="flex-1 text-sm sm:text-base leading-relaxed">
                                        {{ $questaoAtual['alternativa_' . $alt] }}
                                    </div>
                                    @if($marcada)
                                        <div class="flex-shrink-0 text-blue-500">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>

                    {{-- Botões de Navegação (parte inferior) --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-600">
                        {{-- Botão Voltar --}}
                        <button wire:click="anterior" 
                                @if($indice === 0) disabled @endif 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transform hover:scale-105">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Voltar
                        </button>
                        
                        {{-- Botões da direita --}}
                        <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                            @if($indice < $total - 1)
                                <button wire:click="pular" 
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 bg-amber-500 text-white hover:bg-amber-600 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50" 
                                        @if(($statusQuestoes[$indice] ?? null) === 'respondida') disabled @endif>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M3.293 15.707a1 1 0 010-1.414L7.586 10 3.293 5.707a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Pular
                                </button>
                                <button wire:click="proxima" 
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    Próxima
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @else
                                <button wire:click="finalizar" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold text-base sm:text-lg transition-all duration-300 bg-gradient-to-r from-emerald-600 to-green-600 text-white hover:from-emerald-700 hover:to-green-700 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Finalizar Simulado
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- Estado Vazio --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-12 text-center border border-gray-100 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Nenhuma questão encontrada</h3>
                <p class="text-gray-500 dark:text-gray-400">Verifique se o simulado foi carregado corretamente.</p>
            </div>
        @endif
    </div>
</div>

@if(!$finalizado && $total > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let tempoRestante = Math.floor({{ $tempoRestante }});
    const cronometroElement = document.querySelector('[data-cronometro]');
    
    if (cronometroElement && tempoRestante > 0) {
        const atualizarCronometro = () => {
            if (tempoRestante <= 0) {
                // Finalizar automaticamente quando o tempo acabar
                @this.finalizar();
                return;
            }
            
            const minutos = Math.floor(tempoRestante / 60);
            const segundos = Math.floor(tempoRestante % 60);
            const tempoFormatado = `${minutos}:${segundos.toString().padStart(2, '0')}`;
            
            cronometroElement.textContent = tempoFormatado;
            
            // Mudar cor quando restar menos de 5 minutos
            if (tempoRestante <= 300) {
                cronometroElement.classList.add('text-red-600', 'dark:text-red-400');
                cronometroElement.classList.remove('text-gray-800', 'dark:text-gray-100');
            }
            
            tempoRestante--;
        };
        
        // Atualizar a cada segundo
        setInterval(atualizarCronometro, 1000);
        
        // Executar imediatamente
        atualizarCronometro();
    }
});
</script>
@endif