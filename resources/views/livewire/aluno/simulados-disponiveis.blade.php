<div>
    <header class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Olá, {{ Auth::user()->name }}! <span class="text-yellow-500">👋</span></h2>
        <p class="text-gray-500 mt-1">Vamos treinar para sua prova teórica hoje?</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow hover:border-blue-200">
            <div>
                <div class="w-10 h-10 bg-blue-100 rounded flex items-center justify-center text-gov-blue mb-3">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Simulados Feitos</p>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $tentativas->where('status', 'finalizada')->count() }}</span>
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow hover:border-green-200">
            <div>
                <div class="w-10 h-10 bg-green-100 rounded flex items-center justify-center text-gov-green mb-3">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Aproveitamento</p>
            </div>
            <span class="text-3xl font-bold text-gray-800">
                @php
                    $tentativasFinalizadas = $tentativas->where('status', 'finalizada');
                    $mediaGeral = $tentativasFinalizadas->count() > 0 
                        ? round($tentativasFinalizadas->avg('pontuacao'), 0) 
                        : 0;
                @endphp
                {{ $mediaGeral }}%
            </span>
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow hover:border-yellow-200">
            <div>
                <div class="w-10 h-10 bg-yellow-100 rounded flex items-center justify-center text-yellow-600 mb-3">
                    <i class="fa-solid fa-car"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Categoria Alvo</p>
            </div>
            <span class="text-3xl font-bold text-gray-800">B</span>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-gov-blue pl-3">Acesso Rápido</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded shadow-sm border border-gray-200 hover:shadow-md hover:border-gov-blue transition-all cursor-pointer group">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-gov-blue text-lg group-hover:text-gov-darkblue transition-colors">Gerar Simulado Completo</h4>
                    <p class="text-gray-500 text-sm mt-1">Prova padrão com 30 ou 40 questões sorteadas.</p>
                </div>
                <i class="fa-solid fa-play text-gray-300 group-hover:text-gov-blue transition-colors text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-gray-200 hover:shadow-md hover:border-gov-blue transition-all cursor-pointer group">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-gov-blue text-lg group-hover:text-gov-darkblue transition-colors">Revisar Erros</h4>
                    <p class="text-gray-500 text-sm mt-1">Refaça apenas as questões que você errou anteriormente.</p>
                </div>
                <i class="fa-solid fa-rotate-left text-gray-300 group-hover:text-gov-blue transition-colors text-xl"></i>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-gov-yellow pl-3">Simulados Disponíveis</h3>
    <div class="space-y-6">
        @forelse($simulados as $simulado)
            <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $simulado->titulo }}</h2>
                        <p class="text-gray-600 text-sm mb-2">{{ $simulado->descricao }}</p>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span><i class="fa-solid fa-list-ol"></i> {{ $simulado->questoes_count }} questões</span>
                            <span><i class="fa-solid fa-clock"></i> {{ $simulado->tempo_limite }} min</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 min-w-[180px]">
                        @php 
                            $tentativa = $tentativas[$simulado->id] ?? null; 
                            $tentativaFinalizada = $tentativa && $tentativa->status === 'finalizada';
                        @endphp
                        
                        @if(!$tentativa)
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" 
                               class="bg-gov-blue hover:bg-gov-darkblue text-white px-4 py-2 rounded font-medium text-center transition-colors shadow-md hover:shadow-lg">
                                Iniciar Simulado
                            </a>
                        @elseif($tentativa->status === 'em_andamento')
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" 
                               class="bg-gov-yellow hover:bg-yellow-600 text-yellow-900 px-4 py-2 rounded font-medium text-center transition-colors shadow-md hover:shadow-lg">
                                Continuar Simulado
                            </a>
                        @elseif($tentativaFinalizada)
                            <div class="space-y-2">
                                <div class="flex items-center justify-center gap-2 px-4 py-2 rounded bg-green-100 text-gov-green font-medium">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Finalizado</span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('aluno.simulado.resultado', $simulado->id) }}" 
                                       class="flex-1 bg-gov-blue hover:bg-gov-darkblue text-white px-3 py-2 rounded font-medium text-center text-sm transition-colors">
                                        Ver Respostas
                                    </a>
                                    <a href="{{ route('aluno.resultados') }}?simulado={{ $simulado->id }}" 
                                       class="flex-1 bg-gov-green hover:bg-green-700 text-white px-3 py-2 rounded font-medium text-center text-sm transition-colors">
                                        Ver Desempenho
                                    </a>
                                </div>
                                
                                @if($tentativa->pontuacao >= 70)
                                    <div class="text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-gov-green">
                                            <i class="fa-solid fa-check-circle"></i>
                                            Aprovado
                                        </span>
                                        <div class="text-xs text-gray-600 mt-1">
                                            {{ $tentativa->getAproveitamentoFormatado() }} (Nota: {{ $tentativa->getNotaFormatada() }})
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-gov-red">
                                            <i class="fa-solid fa-times-circle"></i>
                                            Reprovado
                                        </span>
                                        <div class="text-xs text-gray-600 mt-1">
                                            {{ $tentativa->getAproveitamentoFormatado() }} (Nota: {{ $tentativa->getNotaFormatada() }})
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clipboard-question text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Nenhum simulado disponível</h3>
                <p class="text-gray-500">Verifique novamente mais tarde.</p>
            </div>
        @endforelse
    </div>
</div>
