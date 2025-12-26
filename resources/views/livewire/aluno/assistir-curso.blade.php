<div>
    <div class="min-h-screen bg-gov-light">
        {{-- Mensagens Flash --}}
        @if (session()->has('success'))
            <div class="fixed top-4 right-4 z-50 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="fixed top-4 right-4 z-50 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row h-screen">
            {{-- Sidebar com Módulos e Aulas --}}
            <div class="w-full lg:w-80 bg-white border-r border-gray-200 overflow-y-auto">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('aluno.cursos') }}" 
                           class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $curso->titulo }}</h2>
                            @if(Auth::user()->isAdmin())
                                <span class="text-xs text-primary-600 dark:text-primary-400 font-medium">Modo Administrador - Visualização</span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Progresso --}}
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Progresso do Curso</span>
                            <span class="font-semibold">{{ $progresso['percentual'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $progresso['percentual'] }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $progresso['assistidas'] }} de {{ $progresso['total'] }} aulas
                        </p>
                    </div>
                </div>

                {{-- Lista de Módulos --}}
                <div class="p-2">
                    @foreach($curso->modulos as $modulo)
                        <div class="mb-2">
                            {{-- Header do Módulo --}}
                            <button 
                                wire:click="selecionarModulo({{ $modulo->id }})"
                                class="w-full flex items-center justify-between p-3 rounded-lg transition-colors
                                       {{ (isset($moduloSelecionadoId) && $moduloSelecionadoId === $modulo->id)
                                           ? 'text-white' 
                                           : 'text-white dark:text-gray-300' }}">
                                <div class="flex items-center gap-2 flex-1 text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span class="font-medium text-sm">{{ $modulo->titulo }}</span>
                                </div>
                                <span class="text-xs {{ (isset($moduloSelecionadoId) && $moduloSelecionadoId === $modulo->id) ? 'text-white/70' : 'text-white/60 dark:text-gray-400' }}">
                                    {{ $modulo->aulas->where('ativo', true)->count() }}
                                </span>
                            </button>

                            {{-- Lista de Aulas do Módulo --}}
                            @if(isset($moduloSelecionadoId) && $moduloSelecionadoId === $modulo->id && $modulo->aulas->where('ativo', true)->count() > 0)
                                <div class="mt-1 ml-6 space-y-1">
                                    @foreach($modulo->aulas->where('ativo', true) as $aula)
                                        <button 
                                            wire:click="selecionarAula({{ $aula->id }})"
                                            class="w-full flex items-center gap-2 p-2 rounded-lg text-sm transition-colors text-left
                                                   {{ (isset($aulaSelecionadaId) && $aulaSelecionadaId === $aula->id)
                                                       ? 'text-white' 
                                                       : 'text-white dark:text-gray-400' }}">
                                            <div class="flex items-center gap-2 flex-1">
                                                @if(!Auth::user()->isAdmin() && $aula->foiAssistidaPor(Auth::id()))
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                                <span class="truncate">{{ $aula->titulo }}</span>
                                            </div>
                                            @if($aula->duracao_minutos)
                                                <span class="text-xs {{ (isset($aulaSelecionadaId) && $aulaSelecionadaId === $aula->id) ? 'text-white/70' : 'text-white/60 dark:text-gray-400' }} whitespace-nowrap">
                                                    {{ $aula->duracao_minutos }}min
                                                </span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Área Principal com Vídeo --}}
            <div class="flex-1 flex flex-col overflow-y-auto">
                @if(isset($aulaSelecionada) && $aulaSelecionada)
                    {{-- Player de Vídeo --}}
                    <div class="bg-black aspect-video flex items-center justify-center">
                        @if($aulaSelecionada->getEmbedUrl())
                            <iframe 
                                src="{{ $aulaSelecionada->getEmbedUrl() }}?autoplay=1" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                class="w-full h-full">
                            </iframe>
                        @else
                            <div class="text-white text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-400">URL do vídeo inválida</p>
                            </div>
                        @endif
                    </div>

                    {{-- Informações da Aula --}}
                    <div class="bg-white dark:bg-gray-800 p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $aulaSelecionada->titulo }}
                                </h1>
                                @if($aulaSelecionada->descricao)
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                                        {{ $aulaSelecionada->descricao }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $aulaSelecionada->duracao_minutos ?? '--' }} minutos
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                        </svg>
                                        {{ ucfirst($aulaSelecionada->video_plataforma) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Botão Marcar como Assistida (desabilitado para admin) --}}
                            <div>
                                @if(Auth::user()->isAdmin())
                                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-4 py-2 rounded-lg font-medium cursor-not-allowed" title="Modo de visualização - Admin">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Modo Visualização
                                    </div>
                                @elseif($aulaSelecionada->foiAssistidaPor(Auth::id()))
                                    <button 
                                        wire:click="desmarcarComoAssistida({{ $aulaSelecionada->id }})"
                                        class="flex items-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-4 py-2 rounded-lg font-medium hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Assistida
                                    </button>
                                @else
                                    <button 
                                        wire:click="marcarComoAssistida({{ $aulaSelecionada->id }})"
                                        class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Marcar como Assistida
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Navegação entre Aulas --}}
                    <div class="bg-white dark:bg-gray-800 p-6">
                        <div class="flex items-center justify-between">
                            @php
                                $aulas = $moduloSelecionado->aulas->where('ativo', true)->values();
                                $indiceAtual = false;
                                if (isset($aulaSelecionadaId) && $aulaSelecionadaId) {
                                    $indiceAtual = $aulas->search(function($aula) use ($aulaSelecionadaId) {
                                        return $aula->id === $aulaSelecionadaId;
                                    });
                                }
                                $aulaAnterior = $indiceAtual !== false && $indiceAtual > 0 ? $aulas[$indiceAtual - 1] : null;
                                $aulaProxima = $indiceAtual !== false && $indiceAtual < $aulas->count() - 1 ? $aulas[$indiceAtual + 1] : null;
                            @endphp

                            <div>
                                @if($aulaAnterior)
                                    <button 
                                        wire:click="selecionarAula({{ $aulaAnterior->id }})"
                                        class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="text-sm font-medium">Aula Anterior</span>
                                    </button>
                                @endif
                            </div>

                            <div>
                                @if($aulaProxima)
                                    <button 
                                        wire:click="selecionarAula({{ $aulaProxima->id }})"
                                        class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                                        <span class="text-sm font-medium">Próxima Aula</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">Selecione uma aula para começar</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


