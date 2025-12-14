<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header da Página --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('filament.admin.resources.cursos.index') }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar para Cursos
            </a>
        </div>

        {{-- Card Informações do Curso --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    {{-- Imagem --}}
                    <div class="flex-shrink-0">
                        @if($curso->imagem_capa)
                            <img src="{{ asset('storage/' . $curso->imagem_capa) }}" 
                                 alt="{{ $curso->titulo }}"
                                 class="w-40 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-40 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $curso->titulo }}</h1>
                                @if($curso->descricao)
                                    <p class="mt-1 text-gray-600 line-clamp-2">{{ $curso->descricao }}</p>
                                @endif
                            </div>
                            <button wire:click="abrirModalCurso" 
                                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="mt-4 flex items-center gap-6 text-sm">
                            <span class="inline-flex items-center {{ $curso->ativo ? 'text-green-600' : 'text-gray-400' }}">
                                <span class="w-2 h-2 rounded-full {{ $curso->ativo ? 'bg-green-500' : 'bg-gray-400' }} mr-2"></span>
                                {{ $curso->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span class="text-gray-500">
                                <span class="font-semibold text-gray-700">{{ $curso->modulos->count() }}</span> módulos
                            </span>
                            <span class="text-gray-500">
                                <span class="font-semibold text-gray-700">{{ $this->getTotalAulas() }}</span> aulas
                            </span>
                            <span class="text-gray-500">
                                <span class="font-semibold text-gray-700">{{ $this->getDuracaoTotal() }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Módulos e Aulas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Módulos e Aulas</h2>
                <button wire:click="abrirModalNovoModulo"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Módulo
                </button>
            </div>
            
            {{-- Lista de Módulos --}}
            <div class="divide-y divide-gray-200">
                
                @forelse($curso->modulos as $modulo)
                    <div wire:key="modulo-{{ $modulo->id }}" class="bg-white">
                        
                        {{-- Header do Módulo --}}
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                            {{-- Número do Módulo --}}
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                            
                            {{-- Info do Módulo --}}
                            <div class="flex-1 min-w-0 cursor-pointer" wire:click="toggleModulo({{ $modulo->id }})">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-900">{{ $modulo->titulo }}</h3>
                                    @if(!$modulo->ativo)
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Inativo</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $modulo->aulas->count() }} aulas 
                                    @if($modulo->aulas->sum('duracao_minutos') > 0)
                                        • {{ floor($modulo->aulas->sum('duracao_minutos') / 60) }}h {{ $modulo->aulas->sum('duracao_minutos') % 60 }}min
                                    @endif
                                </p>
                            </div>
                            
                            {{-- Ações --}}
                            <div class="flex items-center gap-2">
                                <button wire:click="abrirModalEditarModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Editar módulo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button wire:click="confirmarExclusaoModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Excluir módulo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <button wire:click="toggleModulo({{ $modulo->id }})"
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 transform transition-transform {{ in_array($modulo->id, $modulosExpandidos) ? 'rotate-180' : '' }}" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Lista de Aulas (Expandido) --}}
                        @if(in_array($modulo->id, $modulosExpandidos))
                            <div class="bg-gray-50 border-t border-gray-100">
                                <div class="pl-16 pr-6 py-2 space-y-1">
                                    
                                    @forelse($modulo->aulas as $aula)
                                        <div wire:key="aula-{{ $aula->id }}"
                                             class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors {{ !$aula->ativo ? 'opacity-50' : '' }}">
                                            
                                            {{-- Ícone Play --}}
                                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                            
                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-900 text-sm">{{ $aula->titulo }}</h4>
                                            </div>
                                            
                                            {{-- Badges --}}
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="px-2 py-1 rounded {{ $aula->video_plataforma === 'youtube' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                                    {{ ucfirst($aula->video_plataforma) }}
                                                </span>
                                                @if($aula->duracao_minutos)
                                                    <span class="text-gray-500">{{ $aula->duracao_minutos }} min</span>
                                                @endif
                                            </div>
                                            
                                            {{-- Ações --}}
                                            <div class="flex items-center gap-1">
                                                <a href="{{ $aula->video_url }}" target="_blank"
                                                   class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors"
                                                   title="Ver vídeo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                                <button wire:click="abrirModalEditarAula({{ $aula->id }})"
                                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                                        title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmarExclusaoAula({{ $aula->id }})"
                                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                        title="Excluir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-6 text-center text-gray-500 text-sm">
                                            Nenhuma aula neste módulo
                                        </div>
                                    @endforelse
                                    
                                    {{-- Botão Adicionar Aula --}}
                                    <button wire:click="abrirModalNovaAula({{ $modulo->id }})"
                                            class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
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
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum módulo ainda</h3>
                        <p class="mt-2 text-gray-500">Comece adicionando o primeiro módulo do seu curso.</p>
                        <button wire:click="abrirModalNovoModulo"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
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
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModalCurso', false)"></div>
                
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Editar Curso</h3>
                    </div>
                    
                    <div class="bg-white px-6 py-4 space-y-4">
                        {{-- Título --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                            <input type="text" wire:model="cursoTitulo" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('cursoTitulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Descrição --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea wire:model="cursoDescricao" rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        
                        {{-- Imagem --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagem de Capa</label>
                            <div class="flex items-center gap-4">
                                @if($cursoImagemAtual && !$cursoImagem)
                                    <img src="{{ asset('storage/' . $cursoImagemAtual) }}" class="w-20 h-12 object-cover rounded">
                                @elseif($cursoImagem)
                                    <img src="{{ $cursoImagem->temporaryUrl() }}" class="w-20 h-12 object-cover rounded">
                                @endif
                                <input type="file" wire:model="cursoImagem" accept="image/*"
                                       class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                        
                        {{-- Ativo e Ordem --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="cursoAtivo" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Curso Ativo</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                                <input type="number" wire:model="cursoOrdem" min="0"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button wire:click="$set('showModalCurso', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="salvarCurso"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Módulo --}}
    @if($showModalModulo)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModalModulo', false)"></div>
                
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $moduloId ? 'Editar Módulo' : 'Novo Módulo' }}
                        </h3>
                    </div>
                    
                    <div class="bg-white px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título do Módulo *</label>
                            <input type="text" wire:model="moduloTitulo" 
                                   placeholder="Ex: Introdução à Legislação"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('moduloTitulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição (opcional)</label>
                            <textarea wire:model="moduloDescricao" rows="3"
                                      placeholder="Descreva o conteúdo deste módulo..."
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="moduloAtivo" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Módulo Ativo</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button wire:click="$set('showModalModulo', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="salvarModulo"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            {{ $moduloId ? 'Salvar Alterações' : 'Criar Módulo' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Aula --}}
    @if($showModalAula)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModalAula', false)"></div>
                
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $aulaId ? 'Editar Aula' : 'Nova Aula' }}
                        </h3>
                    </div>
                    
                    <div class="bg-white px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título da Aula *</label>
                            <input type="text" wire:model="aulaTitulo" 
                                   placeholder="Ex: Bem-vindo ao curso"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('aulaTitulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição (opcional)</label>
                            <textarea wire:model="aulaDescricao" rows="2"
                                      placeholder="Descreva o conteúdo desta aula..."
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plataforma *</label>
                                <select wire:model="aulaVideoPlataforma"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="youtube">YouTube</option>
                                    <option value="vimeo">Vimeo</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Duração (minutos)</label>
                                <input type="number" wire:model="aulaDuracao" min="1"
                                       placeholder="Ex: 15"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL do Vídeo *</label>
                            <input type="url" wire:model="aulaVideoUrl" 
                                   placeholder="{{ $aulaVideoPlataforma === 'youtube' ? 'https://www.youtube.com/watch?v=...' : 'https://vimeo.com/...' }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('aulaVideoUrl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="aulaAtivo" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Aula Ativa</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button wire:click="$set('showModalAula', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="salvarAula"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            {{ $aulaId ? 'Salvar Alterações' : 'Criar Aula' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Confirmação --}}
    @if($showModalConfirmacao)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModalConfirmacao', false)"></div>
                
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-6 py-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmar Exclusão</h3>
                        
                        <p class="text-gray-600">
                            Tem certeza que deseja excluir 
                            {{ $confirmacaoTipo === 'modulo' ? 'o módulo' : 'a aula' }}
                            <strong>"{{ $confirmacaoNome }}"</strong>?
                        </p>
                        
                        @if($confirmacaoTipo === 'modulo' && isset($confirmacaoExtra) && $confirmacaoExtra > 0)
                            <p class="mt-2 text-sm text-red-600">
                                ⚠️ Esta ação também excluirá {{ $confirmacaoExtra }} aula(s) associada(s).
                            </p>
                        @endif
                        
                        <p class="mt-2 text-sm text-gray-500">Esta ação não pode ser desfeita.</p>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-center gap-3">
                        <button wire:click="$set('showModalConfirmacao', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="executarExclusao"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
