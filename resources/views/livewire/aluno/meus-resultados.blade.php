<div>
    <div class="min-h-screen bg-gov-light">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Meus Resultados</h1>
                <p class="text-gray-600">Histórico de simulados realizados</p>
            </div>

            {{-- Filtro Ativo --}}
            @if($simuladoSelecionado)
                <div class="bg-white rounded shadow-sm p-6 mb-8 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gov-blue/10 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-filter text-gov-blue text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Filtro Ativo</h3>
                                <p class="text-gray-600">Mostrando resultados apenas para: <strong class="text-gray-800">{{ $simuladoSelecionado->titulo }}</strong></p>
                            </div>
                        </div>
                        <button wire:click="limparFiltro" 
                                class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-medium transition-colors">
                            <i class="fa-solid fa-times"></i>
                            Limpar Filtro
                        </button>
                    </div>
                </div>
            @endif

            {{-- Estatísticas Gerais --}}
            <div class="bg-gradient-to-r from-gov-blue to-gov-darkblue rounded shadow-sm p-6 mb-8 text-white">
                <h2 class="text-lg font-semibold mb-4 text-white">Resultados Realizados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2 text-white">{{ $estatisticasGerais['total_simulados'] }}</div>
                        <div class="text-sm text-white/90">
                            @if($simuladoSelecionado)
                                Tentativas Realizadas
                            @else
                                Simulados Realizados
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2 text-white">{{ $estatisticasGerais['aprovacoes'] }}</div>
                        <div class="text-sm text-white/90">Aprovações</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2 text-white">{{ $estatisticasGerais['media_geral'] }}%</div>
                        <div class="text-sm text-white/90">Média Geral</div>
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
                        
                        <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200">
                            {{-- Header do Resultado --}}
                            <div class="bg-gray-50 p-6 border-b border-gray-200">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $tentativa->simulado->titulo }}</h3>
                                        <p class="text-gray-600">{{ $tentativa->simulado->descricao }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $statusColor === 'emerald' ? 'text-gov-green' : 'text-gov-red' }}">{{ $tentativa->getAproveitamentoFormatado() }}</div>
                                            <div class="text-sm text-gray-600">Aproveitamento</div>
                                            <div class="text-xs {{ $statusColor === 'emerald' ? 'text-gov-green' : 'text-gov-red' }}">Nota: {{ $tentativa->getNotaFormatada() }}</div>
                                        </div>
                                        <div class="px-4 py-2 rounded-full text-sm font-medium {{ $statusColor === 'emerald' ? 'bg-gov-green/10 text-gov-green' : 'bg-gov-red/10 text-gov-red' }}">
                                            {{ $status }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle Section para todas as estatísticas --}}
                            <div x-data="{ isExpanded: false }">
                                <div class="bg-gray-50 p-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gov-blue/10 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-chart-bar text-gov-blue"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-800">Estatísticas Detalhadas</h4>
                                                <p class="text-sm text-gray-600">Métricas principais e desempenho por categoria</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Toggle Button -->
                                        <button 
                                            @click="isExpanded = !isExpanded"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-200"
                                            :class="{ 'rotate-180': isExpanded }"
                                        >
                                            <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-200"></i>
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
                                            <h5 class="text-md font-semibold text-gray-800 mb-4">Métricas Principais</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="bg-gov-green/10 rounded p-4 text-center border border-gov-green/20">
                                                    <div class="text-2xl font-bold text-gov-green mb-1">{{ $tentativa->acertos }}</div>
                                                    <div class="text-sm font-medium text-gray-700">Acertos</div>
                                                </div>
                                                <div class="bg-gov-red/10 rounded p-4 text-center border border-gov-red/20">
                                                    <div class="text-2xl font-bold text-gov-red mb-1">{{ $tentativa->erros }}</div>
                                                    <div class="text-sm font-medium text-gray-700">Erros</div>
                                                </div>
                                                <div class="bg-gov-blue/10 rounded p-4 text-center border border-gov-blue/20">
                                                    <div class="text-2xl font-bold text-gov-blue mb-1">{{ $tentativa->getTempoUtilizadoFormatado() }}</div>
                                                    <div class="text-sm font-medium text-gray-700">Tempo</div>
                                                </div>
                                                <div class="bg-gov-yellow/10 rounded p-4 text-center border border-gov-yellow/20">
                                                    <div class="text-2xl font-bold text-yellow-700 mb-1">{{ $tentativa->respostas->count() }}</div>
                                                    <div class="text-sm font-medium text-gray-700">Questões</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Estatísticas por Categoria --}}
                                        @if($estatisticasCategoria->count() > 0)
                                            <div>
                                                <h5 class="text-md font-semibold text-gray-800 mb-4">Desempenho por Categoria</h5>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    @foreach($estatisticasCategoria as $estatistica)
                                                        <div class="bg-white rounded p-4 border border-gray-200">
                                                            <div class="flex items-center justify-between mb-3">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $estatistica['cor'] }}"></div>
                                                                    <span class="font-medium text-gray-800">{{ $estatistica['categoria'] }}</span>
                                                                </div>
                                                                <div class="text-lg font-bold text-gray-800">{{ $estatistica['percentual'] }}%</div>
                                                            </div>
                                                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                                                <span>{{ $estatistica['acertos'] }}/{{ $estatistica['total'] }} acertos</span>
                                                                <span>{{ $estatistica['erros'] }} erros</span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 rounded-full h-2">
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
                                        <div class="pt-4 border-t border-gray-200">
                                            <h5 class="text-md font-semibold text-gray-800 mb-3">Informações Adicionais</h5>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-solid fa-calendar text-gray-400"></i>
                                                    @if($tentativa->finalizado_em)
                                                        {{ $tentativa->finalizado_em->format('d/m/Y H:i') }}
                                                    @else
                                                        <span class="text-gov-yellow">Em andamento</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                                    {{ $tentativa->getTempoUtilizadoFormatado() }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-solid fa-list-ol text-gray-400"></i>
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
                <div class="bg-white rounded shadow-sm p-12 text-center border border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-clipboard-question text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum resultado encontrado</h3>
                    <p class="text-gray-500 mb-6">Você ainda não realizou nenhum simulado.</p>
                    <a href="/aluno/simulados" 
                       class="inline-flex items-center gap-2 bg-gov-blue hover:bg-gov-darkblue text-white px-6 py-3 rounded font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fa-solid fa-clipboard-question"></i>
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
