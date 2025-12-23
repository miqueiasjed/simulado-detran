<div>
    <div class="min-h-screen bg-gov-light">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">Meus Resultados</h1>
                <p class="text-gray-600 dark:text-gray-400">Histórico de simulados realizados</p>
            </div>

            {{-- Filtro Ativo --}}
            @if($simuladoSelecionado)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Filtro Ativo</h3>
                                <p class="text-gray-600 dark:text-gray-400">Mostrando resultados apenas para: <strong>{{ $simuladoSelecionado->titulo }}</strong></p>
                            </div>
                        </div>
                        <button wire:click="limparFiltro" 
                                class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Limpar Filtro
                        </button>
                    </div>
                </div>
            @endif

            {{-- Estatísticas Gerais --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Resultados Realizados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $estatisticasGerais['total_simulados'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @if($simuladoSelecionado)
                                Tentativas Realizadas
                            @else
                                Simulados Realizados
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">{{ $estatisticasGerais['aprovacoes'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Aprovações</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $estatisticasGerais['media_geral'] }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Média Geral</div>
                    </div>
                </div>
            </div>

            {{-- Lista de Resultados --}}
            @if($tentativas->count() > 0)
                <div class="space-y-6">
                    @foreach($tentativas as $tentativa)
                        @php
                            $estatisticasCategoria = $tentativa->getEstatisticasPorCategoria();
                            $status = $tentativa->pontuacao >= 70 ? 'Aprovado' : 'Reprovado';
                            $statusColor = $tentativa->pontuacao >= 70 ? 'emerald' : 'red';
                        @endphp
                        
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                            {{-- Header do Resultado --}}
                            <div class="bg-gradient-to-r from-slate-100 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-6 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $tentativa->simulado->titulo }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $tentativa->simulado->descricao }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">{{ $tentativa->getAproveitamentoFormatado() }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">Aproveitamento</div>
                                            <div class="text-xs text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">Nota: {{ $tentativa->getNotaFormatada() }}</div>
                                        </div>
                                        <div class="px-4 py-2 rounded-full text-sm font-medium bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/30 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-300">
                                            {{ $status }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle Section para todas as estatísticas --}}
                            <div x-data="{ isExpanded: false }">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2H4zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estatísticas Detalhadas</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Métricas principais e desempenho por categoria</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Toggle Button -->
                                        <button 
                                            @click="isExpanded = !isExpanded"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200"
                                            :class="{ 'rotate-180': isExpanded }"
                                        >
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <div class="p-6 space-y-6">
                                        {{-- Métricas Principais --}}
                                        <div>
                                            <h5 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4">Métricas Principais</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 text-center border border-emerald-200 dark:border-emerald-800">
                                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ $tentativa->acertos }}</div>
                                                    <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Acertos</div>
                                                </div>
                                                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center border border-red-200 dark:border-red-800">
                                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400 mb-1">{{ $tentativa->erros }}</div>
                                                    <div class="text-sm font-medium text-red-700 dark:text-red-300">Erros</div>
                                                </div>
                                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center border border-blue-200 dark:border-blue-800">
                                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">{{ $tentativa->getTempoUtilizadoFormatado() }}</div>
                                                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Tempo</div>
                                                </div>
                                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 text-center border border-purple-200 dark:border-purple-800">
                                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">{{ $tentativa->respostas->count() }}</div>
                                                    <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Questões</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Estatísticas por Categoria --}}
                                        @if($estatisticasCategoria->count() > 0)
                                            <div>
                                                <h5 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4">Desempenho por Categoria</h5>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    @foreach($estatisticasCategoria as $estatistica)
                                                        <div class="bg-white dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                                                            <div class="flex items-center justify-between mb-3">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $estatistica['cor'] }}"></div>
                                                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $estatistica['categoria'] }}</span>
                                                                </div>
                                                                <div class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $estatistica['percentual'] }}%</div>
                                                            </div>
                                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                                <span>{{ $estatistica['acertos'] }}/{{ $estatistica['total'] }} acertos</span>
                                                                <span>{{ $estatistica['erros'] }} erros</span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                                <div class="h-2 rounded-full transition-all duration-300" 
                                                                     style="width: {{ $estatistica['percentual'] }}%; background-color: {{ $estatistica['cor'] }};">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Informações Adicionais --}}
                                        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                            <h5 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3">Informações Adicionais</h5>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    @if($tentativa->finalizado_em)
                                                        {{ $tentativa->finalizado_em->format('d/m/Y H:i') }}
                                                    @else
                                                        <span class="text-yellow-600 dark:text-yellow-400">Em andamento</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $tentativa->getTempoUtilizadoFormatado() }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $tentativa->respostas->count() }} questões
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Estado Vazio --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12 text-center border border-gray-100 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Nenhum resultado encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Você ainda não realizou nenhum simulado.</p>
                    <a href="/aluno/simulados" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Ver Simulados Disponíveis
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
    .rotate-180 {
        transform: rotate(180deg);
    }
    </style>
</div>
