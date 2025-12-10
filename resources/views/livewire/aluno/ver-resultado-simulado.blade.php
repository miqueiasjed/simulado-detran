<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800">
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            
            {{-- Header do Simulado --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $simulado->titulo }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $simulado->descricao }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                            @if($tentativa->finalizado_em)
                                Finalizado em {{ $tentativa->finalizado_em->format('d/m/Y H:i') }}
                            @else
                                <span class="text-yellow-600 dark:text-yellow-400">Em andamento</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Tempo Utilizado</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                            @php
                                if ($tentativa->iniciado_em && $tentativa->finalizado_em) {
                                    $totalSegundos = $tentativa->iniciado_em->diffInSeconds($tentativa->finalizado_em);
                                    $minutos = floor($totalSegundos / 60);
                                    $segundos = $totalSegundos % 60;
                                    $tempoFormatado = $minutos . ':' . str_pad($segundos, 2, '0', STR_PAD_LEFT);
                                } else {
                                    $tempoFormatado = '--:--';
                                }
                            @endphp
                            {{ $tempoFormatado }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feedback da Média do Simulado --}}
            @if($totalTentativas > 0)
                <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-6 mb-6 border border-indigo-200 dark:border-indigo-700 overflow-hidden relative">
                    {{-- Decorative elements --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white/90 mb-1">Média Geral do Simulado</h3>
                                    <p class="text-white/70 text-sm">Baseada em {{ $totalTentativas }} {{ $totalTentativas == 1 ? 'tentativa' : 'tentativas' }} realizadas</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-bold text-white mb-1">{{ number_format($mediaSimulado, 1, ',', '.') }}</div>
                                <div class="text-white/80 text-sm font-medium">de 10,0</div>
                            </div>
                        </div>
                        
                        {{-- Barra de progresso visual --}}
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white/80 text-sm font-medium">Comparação com sua nota</span>
                                <span class="text-white/80 text-sm font-medium">
                                    @if($resultado['nota'] >= $mediaSimulado)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acima da média
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Abaixo da média
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-3 overflow-hidden">
                                <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ ($mediaSimulado / 10) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-white/70">
                                <span>Sua nota: {{ number_format($resultado['nota'], 1, ',', '.') }}</span>
                                <span>Média: {{ number_format($mediaSimulado, 1, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Mensagem quando não há tentativas ainda --}}
                <div class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Média do Simulado</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">A média será calculada após outras tentativas serem realizadas</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Resultados --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r {{ $resultado['aprovado'] ? 'from-emerald-500 to-teal-600' : 'from-red-500 to-orange-600' }} p-8 text-center text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        @if($resultado['aprovado'])
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <h2 class="text-3xl font-bold mb-2">
                        @if($resultado['aprovado'])
                            Parabéns! Você foi Aprovado!
                        @else
                            Você não atingiu a nota mínima
                        @endif
                    </h2>
                    <p class="text-xl opacity-90">
                        Sua nota: <span class="font-bold">{{ number_format($resultado['nota'], 1, ',', '.') }}</span> / 
                        Nota mínima: <span class="font-bold">{{ number_format($resultado['nota_minima'], 1, ',', '.') }}</span>
                    </p>
                </div>
                
                <div class="p-8">
                    {{-- Métricas Principais --}}
                    <div class="mb-8" x-data="{ isExpanded: false }">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2H4zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Estatísticas Detalhadas</h3>
                                        <p class="text-gray-600 dark:text-gray-400">Métricas principais e desempenho por categoria</p>
                                    </div>
                                </div>
                                
                                <!-- Toggle Button -->
                                <button 
                                    @click="isExpanded = !isExpanded"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-700 shadow-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200"
                                    :class="{ 'rotate-180': isExpanded }"
                                >
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Estatísticas Content -->
                        <div x-show="isExpanded" 
                             x-transition:enter="transition-all duration-300 ease-out"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-96"
                             x-transition:leave="transition-all duration-300 ease-in"
                             x-transition:leave-start="opacity-100 max-h-96"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="overflow-hidden">
                            <div class="mt-6 space-y-8">
                                {{-- Métricas Principais --}}
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Métricas Principais</h4>
                                    
                                    {{-- Card de Aprovação --}}
                                    <div class="mb-6 {{ $resultado['aprovado'] ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }} rounded-2xl p-6 border-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 h-16 {{ $resultado['aprovado'] ? 'bg-emerald-500' : 'bg-red-500' }} rounded-xl flex items-center justify-center">
                                                    @if($resultado['aprovado'])
                                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h5 class="text-lg font-bold {{ $resultado['aprovado'] ? 'text-emerald-800 dark:text-emerald-200' : 'text-red-800 dark:text-red-200' }}">
                                                        @if($resultado['aprovado'])
                                                            Aprovado!
                                                        @else
                                                            Não Aprovado
                                                        @endif
                                                    </h5>
                                                    <p class="text-sm {{ $resultado['aprovado'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300' }}">
                                                        @if($resultado['aprovado'])
                                                            Você atingiu a nota mínima necessária
                                                        @else
                                                            Você precisa de {{ number_format($resultado['nota_minima'], 1, ',', '.') }} para ser aprovado
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-3xl font-bold {{ $resultado['aprovado'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ number_format($resultado['nota'], 1, ',', '.') }}
                                                </div>
                                                <div class="text-sm {{ $resultado['aprovado'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                    de {{ number_format($resultado['nota_minima'], 1, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">Nota: {{ $resultado['nota'] }}</div>
                                        </div>
                                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-6 text-center border border-purple-200 dark:border-purple-800">
                                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $resultado['nota'] }}</div>
                                            <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Nota (0-10)</div>
                                            <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">{{ $resultado['percentual'] }}% aproveitamento</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estatísticas por Categoria --}}
                                @if($estatisticasCategoria->count() > 0)
                                    <div>
                                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Desempenho por Categoria</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach($estatisticasCategoria as $estatistica)
                                                <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 shadow-lg">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $estatistica['cor'] }}"></div>
                                                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $estatistica['categoria'] }}</span>
                                                        </div>
                                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $estatistica['percentual'] }}%</div>
                                                    </div>
                                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                        <span>{{ $estatistica['acertos'] }}/{{ $estatistica['total'] }} acertos</span>
                                                        <span>{{ $estatistica['erros'] }} erros</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                                                        <div class="h-3 rounded-full transition-all duration-300" 
                                                             style="width: {{ $estatistica['percentual'] }}%; background-color: {{ $estatistica['cor'] }};">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Seção de Revisão das Questões --}}
                    @if(count($resultado['respostas_detalhadas']) > 0)
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Revisão das Questões</h3>
                            
                            {{-- Resumo das Questões Erradas --}}
                            @php
                                $questoesErradas = array_filter($resultado['respostas_detalhadas'], fn($r) => !$r['correta']);
                                $questoesCorretas = array_filter($resultado['respostas_detalhadas'], fn($r) => $r['correta']);
                            @endphp
                            
                            @if(count($questoesErradas) > 0)
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 mb-6 border border-red-200 dark:border-red-800">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-red-800 dark:text-red-200">Questões que Precisam de Revisão</h4>
                                            <p class="text-red-600 dark:text-red-300 text-sm">Revise cuidadosamente as {{ count($questoesErradas) }} questões que você errou</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ count($questoesErradas) }}</div>
                                            <div class="text-sm text-red-700 dark:text-red-300">Questões Erradas</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ count($questoesCorretas) }}</div>
                                            <div class="text-sm text-emerald-700 dark:text-emerald-300">Questões Corretas</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count($resultado['respostas_detalhadas']) }}</div>
                                            <div class="text-sm text-blue-700 dark:text-blue-300">Total de Questões</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ round((count($questoesErradas) / count($resultado['respostas_detalhadas'])) * 100, 1) }}%</div>
                                            <div class="text-sm text-purple-700 dark:text-purple-300">Taxa de Erro</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Filtros para Revisão --}}
                            <div class="mb-6" x-data="{ filtro: 'todas' }">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <button @click="filtro = 'todas'" 
                                            :class="filtro === 'todas' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                                        Todas as Questões ({{ count($resultado['respostas_detalhadas']) }})
                                    </button>
                                    <button @click="filtro = 'erradas'" 
                                            :class="filtro === 'erradas' ? 'bg-red-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                                        Apenas Erradas ({{ count($questoesErradas) }})
                                    </button>
                                    <button @click="filtro = 'corretas'" 
                                            :class="filtro === 'corretas' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                                        Apenas Corretas ({{ count($questoesCorretas) }})
                                    </button>
                                </div>
                                
                                <div class="space-y-6">
                                    @foreach($resultado['respostas_detalhadas'] as $index => $resposta)
                                        @php
                                            $questao = $resposta['questao'];
                                            $respostaEscolhida = $resposta['resposta_escolhida'];
                                            $respostaCorreta = $resposta['resposta_correta'];
                                            $correta = $resposta['correta'];
                                        @endphp
                                        
                                        <div x-show="filtro === 'todas' || filtro === '{{ $correta ? 'corretas' : 'erradas' }}'"
                                             class="bg-white dark:bg-gray-700 rounded-2xl p-6 border-2 {{ $correta ? 'border-emerald-200 dark:border-emerald-800' : 'border-red-200 dark:border-red-800' }}">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $correta ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                            Questão {{ $index + 1 }}
                                                        </h4>
                                                        <div class="flex items-center gap-2 mt-1">
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
                                                            @if($questao->categoria)
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $questao->categoria->cor }}20; color: {{ $questao->categoria->cor }};">
                                                                    <div class="w-2 h-2 rounded-full" style="background-color: {{ $questao->categoria->cor }};"></div>
                                                                    {{ $questao->categoria->nome }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $questao->pergunta }}</p>
                                            </div>
                                            
                                            @if($respostaEscolhida === 'pulado')
                                                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800 mb-4">
                                                    <div class="flex items-center gap-2 text-amber-700 dark:text-amber-300">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="font-semibold">Questão pulada</span>
                                                    </div>
                                                    <p class="text-amber-600 dark:text-amber-400 text-sm mt-1">Esta questão foi pulada durante o simulado.</p>
                                                </div>
                                            @elseif($respostaEscolhida === 'nao_respondida')
                                                <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4 border border-gray-200 dark:border-gray-800 mb-4">
                                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="font-semibold">Questão não respondida</span>
                                                    </div>
                                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Esta questão não foi respondida durante o simulado.</p>
                                                </div>
                                            @else
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
                                                            <div class="flex items-center gap-2">
                                                                @if($isCorreta)
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Correta
                                                                    </span>
                                                                @endif
                                                                @if($isEscolhida && !$correta)
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Sua Resposta
                                                                    </span>
                                                                @endif
                                                                @if($isEscolhida && $correta)
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Sua Resposta
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
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
                        </div>
                    @endif
                    
                    <div class="text-center space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/aluno/simulados" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-600 to-gray-700 text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                </svg>
                                Voltar para Simulados
                            </a>
                            
                            <a href="{{ route('aluno.resultados') }}?simulado={{ $simulado->id }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Ver Desempenho Geral
                            </a>
                        </div>
                        
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Visualize estatísticas detalhadas e histórico de desempenho
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .rotate-180 {
        transform: rotate(180deg);
    }
    </style>
</div>
