<div class="min-h-screen bg-gray-900 py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header da Página --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('filament.admin.resources.cursos.index') }}" 
               class="inline-flex items-center text-sm text-gray-400 hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar para Cursos
            </a>
        </div>

        {{-- Card Informações do Curso --}}
        <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 mb-6 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col gap-6">
                    {{-- Imagem --}}
                    <div class="flex justify-center">
                        @if($curso->imagem_capa)
                            <img src="{{ asset('storage/' . $curso->imagem_capa) }}" 
                                 alt="{{ $curso->titulo }}"
                                 class="w-full max-w-md h-48 object-cover rounded-lg border border-gray-700">
                        @else
                            <div class="w-full max-w-md h-48 bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg flex items-center justify-center border border-primary-500/20">
                                <svg class="w-16 h-16 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-white">{{ $curso->titulo }}</h1>
                                @if($curso->descricao)
                                    <p class="mt-1 text-gray-400 line-clamp-2">{{ $curso->descricao }}</p>
                                @endif
                            </div>
                            <button wire:click="abrirModalCurso" 
                                    class="p-2 text-gray-400 hover:text-primary-400 hover:bg-gray-700 rounded-lg transition-colors ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="mt-4 flex items-center gap-6 text-sm">
                            <span class="inline-flex items-center {{ $curso->ativo ? 'text-green-400' : 'text-gray-500' }}">
                                <span class="w-2 h-2 rounded-full {{ $curso->ativo ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                {{ $curso->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span class="text-gray-400">
                                <span class="font-semibold text-gray-200">{{ $curso->modulos->count() }}</span> módulos
                            </span>
                            <span class="text-gray-400">
                                <span class="font-semibold text-gray-200">{{ $this->getTotalAulas() }}</span> aulas
                            </span>
                            <span class="text-gray-400">
                                <span class="font-semibold text-gray-200">{{ $this->getDuracaoTotal() }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Módulos e Aulas --}}
        <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Módulos e Aulas</h2>
                <button wire:click="abrirModalNovoModulo"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Módulo
                </button>
            </div>
            
            {{-- Lista de Módulos --}}
            <div class="divide-y divide-gray-700">
                
                @forelse($curso->modulos as $modulo)
                    <div wire:key="modulo-{{ $modulo->id }}" class="bg-gray-800">
                        
                        {{-- Header do Módulo --}}
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-700/50 transition-colors">
                            {{-- Número do Módulo --}}
                            <div class="flex-shrink-0 w-8 h-8 bg-primary-600/20 text-primary-400 border border-primary-500/30 rounded-full flex items-center justify-center font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                            
                            {{-- Info do Módulo --}}
                            <div class="flex-1 min-w-0 cursor-pointer" wire:click="toggleModulo({{ $modulo->id }})">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-white">{{ $modulo->titulo }}</h3>
                                    @if(!$modulo->ativo)
                                        <span class="px-2 py-0.5 text-xs bg-gray-700 text-gray-400 border border-gray-600 rounded">Inativo</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-400">
                                    {{ $modulo->aulas->count() }} aulas 
                                    @if($modulo->aulas->sum('duracao_minutos') > 0)
                                        • {{ floor($modulo->aulas->sum('duracao_minutos') / 60) }}h {{ $modulo->aulas->sum('duracao_minutos') % 60 }}min
                                    @endif
                                </p>
                            </div>
                            
                            {{-- Ações --}}
                            <div class="flex items-center gap-2">
                                <button wire:click="abrirModalEditarModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-primary-400 hover:bg-gray-700 rounded-lg transition-colors"
                                        title="Editar módulo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button wire:click="confirmarExclusaoModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-danger-400 hover:bg-danger-500/10 rounded-lg transition-colors"
                                        title="Excluir módulo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <button wire:click="toggleModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 transform transition-transform {{ in_array($modulo->id, $modulosExpandidos) ? 'rotate-180' : '' }}" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Lista de Aulas (Expandido) --}}
                        @if(in_array($modulo->id, $modulosExpandidos))
                            <div class="bg-gray-900/50 border-t border-gray-700">
                                <div class="pl-16 pr-6 py-2 space-y-1">
                                    
                                    @forelse($modulo->aulas as $aula)
                                        <div wire:key="aula-{{ $aula->id }}"
                                             class="flex items-center gap-3 p-3 bg-gray-700/30 rounded-lg border border-gray-700 hover:border-primary-500/50 transition-colors {{ !$aula->ativo ? 'opacity-50' : '' }}">
                                            
                                            {{-- Ícone Play --}}
                                            <div class="flex-shrink-0 w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center border border-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                            
                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-white text-sm">{{ $aula->titulo }}</h4>
                                            </div>
                                            
                                            {{-- Badges --}}
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="px-2 py-1 rounded border {{ $aula->video_plataforma === 'youtube' ? 'bg-red-500/20 text-red-400 border-red-500/30' : 'bg-blue-500/20 text-blue-400 border-blue-500/30' }}">
                                                    {{ ucfirst($aula->video_plataforma) }}
                                                </span>
                                                @if($aula->duracao_minutos)
                                                    <span class="text-gray-400">{{ $aula->duracao_minutos }} min</span>
                                                @endif
                                            </div>
                                            
                                            {{-- Ações --}}
                                            <div class="flex items-center gap-1">
                                                <a href="{{ $aula->video_url }}" target="_blank"
                                                   class="p-1.5 text-gray-400 hover:text-success-400 hover:bg-success-500/10 rounded transition-colors"
                                                   title="Ver vídeo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                                <button wire:click="abrirModalEditarAula({{ $aula->id }})"
                                                        class="p-1.5 text-gray-400 hover:text-primary-400 hover:bg-primary-500/10 rounded transition-colors"
                                                        title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmarExclusaoAula({{ $aula->id }})"
                                                        class="p-1.5 text-gray-400 hover:text-danger-400 hover:bg-danger-500/10 rounded transition-colors"
                                                        title="Excluir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-6 text-center text-gray-400 text-sm">
                                            Nenhuma aula neste módulo
                                        </div>
                                    @endforelse
                                    
                                    {{-- Botão Adicionar Aula --}}
                                    <button wire:click="abrirModalNovaAula({{ $modulo->id }})"
                                            class="w-full py-3 border-2 border-dashed border-gray-600 rounded-lg text-sm text-gray-400 hover:border-primary-500 hover:text-primary-400 hover:bg-primary-500/10 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Adicionar Aula
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-white">Nenhum módulo ainda</h3>
                        <p class="mt-2 text-gray-400">Comece adicionando o primeiro módulo do seu curso.</p>
                        <button wire:click="abrirModalNovoModulo"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Criar Primeiro Módulo
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ==================== MODAIS ==================== --}}
    
    {{-- Modal Editar Curso --}}
    @if($showModalCurso)
        <x-filament::modal 
            id="modal-curso" 
            width="2xl"
        >
            <x-slot name="heading">
                Editar Curso
            </x-slot>

            <div class="space-y-6">
                {{-- Título --}}
                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Título <span class="text-danger-600 dark:text-danger-400">*</span>
                    </label>
                    <div class="mt-1">
                        <input 
                            type="text" 
                            wire:model="cursoTitulo" 
                            placeholder="Digite o título do curso"
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        />
                    </div>
                    @error('cursoTitulo') 
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Descrição --}}
                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Descrição
                    </label>
                    <div class="mt-1">
                        <textarea 
                            wire:model="cursoDescricao" 
                            rows="3"
                            placeholder="Descreva o curso..."
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        ></textarea>
                    </div>
                </div>
                
                {{-- Imagem --}}
                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Imagem de Capa
                    </label>
                    <div class="mt-1 flex items-center gap-4">
                        @if($cursoImagemAtual && !$cursoImagem)
                            <img src="{{ asset('storage/' . $cursoImagemAtual) }}" 
                                 class="w-20 h-12 object-cover rounded-lg border border-gray-300 dark:border-gray-700">
                        @elseif($cursoImagem)
                            <img src="{{ $cursoImagem->temporaryUrl() }}" 
                                 class="w-20 h-12 object-cover rounded-lg border border-gray-300 dark:border-gray-700">
                        @endif
                        <div class="flex-1">
                            <input 
                                type="file" 
                                wire:model="cursoImagem" 
                                accept="image/*"
                                class="filament-input block w-full text-sm text-gray-950 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-600 file:text-white hover:file:bg-primary-700 dark:file:bg-primary-500 dark:file:hover:bg-primary-600"
                            />
                        </div>
                    </div>
                </div>
                
                {{-- Ativo e Ordem --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="filament-checkbox flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                wire:model="cursoAtivo" 
                                class="filament-checkbox-input h-4 w-4 rounded border-gray-300 text-primary-600 shadow-sm transition duration-75 checked:focus:ring-primary-500/20 focus:ring-2 focus:ring-primary-500/50 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-400 disabled:checked:bg-current disabled:checked:text-gray-400 dark:border-white/20 dark:bg-white/5 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-900"
                            />
                            <span class="filament-checkbox-label text-sm font-medium text-gray-950 dark:text-white">
                                Curso Ativo
                            </span>
                        </label>
                    </div>
                    <div>
                        <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                            Ordem
                        </label>
                        <div class="mt-1">
                            <input 
                                type="number" 
                                wire:model="cursoOrdem" 
                                min="0"
                                placeholder="0"
                                class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <x-filament::button 
                    color="gray" 
                    wire:click="$set('showModalCurso', false)"
                >
                    Cancelar
                </x-filament::button>
                <x-filament::button 
                    wire:click="salvarCurso"
                >
                    Salvar Alterações
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif

    {{-- Modal Módulo --}}
    @if($showModalModulo)
        <x-filament::modal 
            id="modal-modulo" 
            width="md"
        >
            <x-slot name="heading">
                {{ $moduloId ? 'Editar Módulo' : 'Novo Módulo' }}
            </x-slot>

            <div class="space-y-6">
                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Título do Módulo <span class="text-danger-600 dark:text-danger-400">*</span>
                    </label>
                    <div class="mt-1">
                        <input 
                            type="text" 
                            wire:model="moduloTitulo" 
                            placeholder="Ex: Introdução à Legislação"
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        />
                    </div>
                    @error('moduloTitulo') 
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Descrição (opcional)
                    </label>
                    <div class="mt-1">
                        <textarea 
                            wire:model="moduloDescricao" 
                            rows="3"
                            placeholder="Descreva o conteúdo deste módulo..."
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        ></textarea>
                    </div>
                </div>

                <div>
                    <label class="filament-checkbox flex items-center gap-3">
                        <input 
                            type="checkbox" 
                            wire:model="moduloAtivo" 
                            class="filament-checkbox-input h-4 w-4 rounded border-gray-300 text-primary-600 shadow-sm transition duration-75 checked:focus:ring-primary-500/20 focus:ring-2 focus:ring-primary-500/50 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-400 disabled:checked:bg-current disabled:checked:text-gray-400 dark:border-white/20 dark:bg-white/5 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-900"
                        />
                        <span class="filament-checkbox-label text-sm font-medium text-gray-950 dark:text-white">
                            Módulo Ativo
                        </span>
                    </label>
                </div>
            </div>

            <x-slot name="footer">
                <x-filament::button 
                    color="gray" 
                    wire:click="$set('showModalModulo', false)"
                >
                    Cancelar
                </x-filament::button>
                <x-filament::button 
                    wire:click="salvarModulo"
                >
                    {{ $moduloId ? 'Salvar Alterações' : 'Criar Módulo' }}
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif

    {{-- Modal Aula --}}
    @if($showModalAula)
        <x-filament::modal 
            id="modal-aula" 
            width="2xl"
        >
            <x-slot name="heading">
                {{ $aulaId ? 'Editar Aula' : 'Nova Aula' }}
            </x-slot>

            <div class="space-y-6">
                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Título da Aula <span class="text-danger-600 dark:text-danger-400">*</span>
                    </label>
                    <div class="mt-1">
                        <input 
                            type="text" 
                            wire:model="aulaTitulo" 
                            placeholder="Ex: Bem-vindo ao curso"
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        />
                    </div>
                    @error('aulaTitulo') 
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        Descrição (opcional)
                    </label>
                    <div class="mt-1">
                        <textarea 
                            wire:model="aulaDescricao" 
                            rows="2"
                            placeholder="Descreva o conteúdo desta aula..."
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        ></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                            Plataforma <span class="text-danger-600 dark:text-danger-400">*</span>
                        </label>
                        <div class="mt-1">
                            <select 
                                wire:model="aulaVideoPlataforma"
                                class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                            >
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                            Duração (minutos)
                        </label>
                        <div class="mt-1">
                            <input 
                                type="number" 
                                wire:model="aulaDuracao" 
                                min="1"
                                placeholder="Ex: 15"
                                class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                            />
                        </div>
                    </div>
                </div>

                <div>
                    <label class="filament-forms-field-wrapper-label text-sm font-medium text-gray-950 dark:text-white">
                        URL do Vídeo <span class="text-danger-600 dark:text-danger-400">*</span>
                    </label>
                    <div class="mt-1">
                        <input 
                            type="url" 
                            wire:model="aulaVideoUrl" 
                            placeholder="{{ $aulaVideoPlataforma === 'youtube' ? 'https://www.youtube.com/watch?v=...' : 'https://vimeo.com/...' }}"
                            class="filament-input block w-full rounded-lg border-none bg-white shadow-sm ring-1 transition duration-75 invalid:ring-danger-600 focus:ring-2 focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:invalid:ring-danger-500 dark:disabled:bg-transparent dark:disabled:text-gray-400 dark:disabled:ring-white/10"
                        />
                    </div>
                    @error('aulaVideoUrl') 
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="filament-checkbox flex items-center gap-3">
                        <input 
                            type="checkbox" 
                            wire:model="aulaAtivo" 
                            class="filament-checkbox-input h-4 w-4 rounded border-gray-300 text-primary-600 shadow-sm transition duration-75 checked:focus:ring-primary-500/20 focus:ring-2 focus:ring-primary-500/50 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-400 disabled:checked:bg-current disabled:checked:text-gray-400 dark:border-white/20 dark:bg-white/5 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-900"
                        />
                        <span class="filament-checkbox-label text-sm font-medium text-gray-950 dark:text-white">
                            Aula Ativa
                        </span>
                    </label>
                </div>
            </div>

            <x-slot name="footer">
                <x-filament::button 
                    color="gray" 
                    wire:click="$set('showModalAula', false)"
                >
                    Cancelar
                </x-filament::button>
                <x-filament::button 
                    wire:click="salvarAula"
                >
                    {{ $aulaId ? 'Salvar Alterações' : 'Criar Aula' }}
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif

    {{-- Modal Confirmação --}}
    @if($showModalConfirmacao)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModalConfirmacao', false)"></div>
                
                <div class="inline-block align-bottom bg-gray-800 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-700">
                    <div class="bg-gray-800 px-6 py-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-danger-500/20 border border-danger-500/30 mb-4">
                            <svg class="h-6 w-6 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-white mb-2">Confirmar Exclusão</h3>
                        
                        <p class="text-gray-300">
                            Tem certeza que deseja excluir 
                            {{ $confirmacaoTipo === 'modulo' ? 'o módulo' : 'a aula' }}
                            <strong class="text-white">"{{ $confirmacaoNome }}"</strong>?
                        </p>
                        
                        @if($confirmacaoTipo === 'modulo' && isset($confirmacaoExtra) && $confirmacaoExtra > 0)
                            <p class="mt-2 text-sm text-danger-400">
                                ⚠️ Esta ação também excluirá {{ $confirmacaoExtra }} aula(s) associada(s).
                            </p>
                        @endif
                        
                        <p class="mt-2 text-sm text-gray-400">Esta ação não pode ser desfeita.</p>
                    </div>
                    
                    <div class="bg-gray-750 px-6 py-4 flex justify-center gap-3 border-t border-gray-700">
                        <button wire:click="$set('showModalConfirmacao', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="executarExclusao"
                                class="px-4 py-2 text-sm font-medium text-white bg-danger-600 rounded-lg hover:bg-danger-700 transition-colors">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
