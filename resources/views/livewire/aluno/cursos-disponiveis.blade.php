<div>
    <div class="min-h-screen bg-gov-light">
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">Cursos Disponíveis</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    @if(Auth::user()->isAdmin())
                        Modo Administrador - Visualização de cursos para teste
                    @else
                        Explore nossos cursos e aprimore seus conhecimentos
                    @endif
                </p>
            </div>

            {{-- Mensagens Flash --}}
            @if (session()->has('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('message'))
                <div class="mb-6 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Meus Cursos (Cursos Inscritos) --}}
            @if($meusCursos->count() > 0)
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Meus Cursos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($meusCursos as $curso)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                                {{-- Imagem do Curso --}}
                                <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-700 relative overflow-hidden">
                                    @if($curso->imagem_capa)
                                        <img src="{{ asset('storage/' . $curso->imagem_capa) }}" 
                                             alt="{{ $curso->titulo }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    {{-- Badge Progresso --}}
                                    <div class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $curso->progresso['percentual'] }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- Conteúdo --}}
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                        {{ $curso->titulo }}
                                    </h3>
                                    
                                    @if($curso->descricao)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                            {{ $curso->descricao }}
                                        </p>
                                    @endif

                                    {{-- Estatísticas --}}
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $curso->modulos->count() }} módulos
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $curso->getTotalAulas() }} aulas
                                        </span>
                                    </div>

                                    {{-- Barra de Progresso --}}
                                    <div class="mb-4">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $curso->progresso['percentual'] }}%">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $curso->progresso['assistidas'] }} de {{ $curso->progresso['total'] }} aulas assistidas
                                        </p>
                                    </div>

                                    {{-- Botão Assistir --}}
                                    <a href="{{ route('aluno.curso.assistir', $curso->id) }}" 
                                       class="block w-full bg-gov-blue hover:bg-gov-darkblue text-white text-center px-4 py-2 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                                        Continuar Assistindo
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Todos os Cursos Disponíveis --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    {{ $meusCursos->count() > 0 ? 'Outros Cursos' : 'Cursos Disponíveis' }}
                </h2>
                
                @if($cursos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cursos as $curso)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                                {{-- Imagem do Curso --}}
                                <div class="h-48 bg-gradient-to-br from-primary-500 to-primary-700 relative overflow-hidden">
                                    @if($curso->imagem_capa)
                                        <img src="{{ asset('storage/' . $curso->imagem_capa) }}" 
                                             alt="{{ $curso->titulo }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Conteúdo --}}
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                        {{ $curso->titulo }}
                                    </h3>
                                    
                                    @if($curso->descricao)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                            {{ $curso->descricao }}
                                        </p>
                                    @endif

                                    {{-- Estatísticas --}}
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $curso->modulos->count() }} módulos
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $curso->getTotalAulas() }} aulas
                                        </span>
                                    </div>

                                    {{-- Botão Inscrever-se / Assistir (Admin) --}}
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('aluno.curso.assistir', $curso->id) }}" 
                                           class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors">
                                            Visualizar Curso
                                        </a>
                                    @elseif(in_array($curso->id, $cursosInscritos))
                                        <button disabled
                                                class="w-full bg-gray-300 text-gray-500 text-center px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                                            Já Inscrito
                                        </button>
                                    @else
                                        <button wire:click="inscrever({{ $curso->id }})"
                                                class="w-full bg-gov-blue hover:bg-gov-darkblue text-white text-center px-4 py-2 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                                            Inscrever-se
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center border border-gray-200 dark:border-gray-700">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Nenhum curso disponível no momento.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


