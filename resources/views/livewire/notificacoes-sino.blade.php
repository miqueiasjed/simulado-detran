<div>
    <!-- Sino de Notificações -->
    <div class="relative">
        <button
            wire:click="abrirModal"
            class="relative p-2 text-gray-700 hover:text-gov-blue transition-colors duration-200 rounded-full hover:bg-gray-100"
            title="Notificações"
        >
            <!-- Ícone do Sino -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v3.75l2.25 2.25V12a8.25 8.25 0 0 0-16.5 0v3.75L4.5 13.5V9.75a6 6 0 0 1 6-6Z"></path>
            </svg>
            
            <!-- Badge de notificações não lidas -->
            @if($avisosNaoLidos > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium animate-pulse">
                    {{ $avisosNaoLidos > 99 ? '99+' : $avisosNaoLidos }}
                </span>
            @endif
        </button>
    </div>

    <!-- Modal de Notificações -->
    @if($mostrarModal)
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="background-color: rgba(0, 0, 0, 0.5);"
        >
            <div class="flex items-center justify-center min-h-screen p-4">
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="relative w-full max-w-md mx-auto bg-white rounded-lg shadow-xl overflow-hidden"
                >
                    <!-- Header do Modal -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v3.75l2.25 2.25V12a8.25 8.25 0 0 0-16.5 0v3.75L4.5 13.5V9.75a6 6 0 0 1 6-6Z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Notificações
                                @if($avisosNaoLidos > 0)
                                    <span class="ml-2 bg-gov-red text-white text-xs rounded-full px-2 py-1">
                                        {{ $avisosNaoLidos }}
                                    </span>
                                @endif
                            </h3>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            @if($avisosNaoLidos > 0)
                                <button
                                    wire:click="marcarTodosComoLidos"
                                    class="text-sm text-gov-blue hover:text-gov-darkblue font-medium"
                                >
                                    Marcar todas como lidas
                                </button>
                            @endif
                            
                            <button
                                @click="show = false; $wire.fecharModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Notificações -->
                    <div class="max-h-96 overflow-y-auto">
                        @if(count($avisos) > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($avisos as $aviso)
                                    @php
                                        $isLido = isset($aviso['pivot']['lido_em']);
                                        $tipoColor = match($aviso['tipo']) {
                                            'informacao' => 'border-blue-200 bg-blue-50 dark:bg-blue-900/20',
                                            'aviso' => 'border-yellow-200 bg-yellow-50 dark:bg-yellow-900/20',
                                            'erro' => 'border-red-200 bg-red-50 dark:bg-red-900/20',
                                            'sucesso' => 'border-green-200 bg-green-50 dark:bg-green-900/20',
                                            default => 'border-gray-200 bg-gray-50 dark:bg-gray-900/20'
                                        };
                                        
                                        $tipoIconColor = match($aviso['tipo']) {
                                            'informacao' => 'text-blue-500',
                                            'aviso' => 'text-yellow-500',
                                            'erro' => 'text-red-500',
                                            'sucesso' => 'text-green-500',
                                            default => 'text-gray-500'
                                        };
                                        
                                        $prioridadeColor = match($aviso['prioridade']) {
                                            'baixa' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'media' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'alta' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
                                        };
                                    @endphp

                                    <div class="p-4 hover:bg-gray-50 transition-colors {{ $isLido ? 'opacity-75' : '' }}">
                                        <div class="flex items-start space-x-3">
                                            <!-- Ícone do tipo -->
                                            <div class="flex-shrink-0 mt-1">
                                                <svg class="w-5 h-5 {{ $tipoIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($aviso['tipo'] === 'informacao')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @elseif($aviso['tipo'] === 'aviso')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    @elseif($aviso['tipo'] === 'erro')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @elseif($aviso['tipo'] === 'sucesso')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                                    @endif
                                                </svg>
                                            </div>
                                            
                                            <!-- Conteúdo da notificação -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        {{ $aviso['titulo'] }}
                                                        @if($isLido)
                                                            <span class="ml-2 text-xs text-gray-500">(Lido)</span>
                                                        @endif
                                                    </h4>
                                                    
                                                    <div class="flex items-center space-x-2">
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $prioridadeColor }}">
                                                            {{ ucfirst($aviso['prioridade']) }}
                                                        </span>
                                                        
                                                        @if($aviso['mostrar_popup'])
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gov-blue/10 text-gov-blue">
                                                                Pop-up
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-1 text-sm text-gray-600 line-clamp-2">
                                                    {!! strip_tags($aviso['conteudo']) !!}
                                                </div>
                                                
                                                <div class="mt-2 flex items-center justify-between">
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($aviso['created_at'])->diffForHumans() }}
                                                    </span>
                                                    
                                                    @if(!$isLido)
                                                        <button
                                                            wire:click="marcarComoLido({{ $aviso['id'] }})"
                                                            class="text-xs text-gov-blue hover:text-gov-darkblue font-medium"
                                                        >
                                                            Marcar como lido
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v3.75l2.25 2.25V12a8.25 8.25 0 0 0-16.5 0v3.75L4.5 13.5V9.75a6 6 0 0 1 6-6Z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma notificação</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Você está em dia com todas as notificações.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer do Modal -->
                    <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
                        <span class="text-sm text-gray-500">
                            {{ count($avisos) }} notificação(ões)
                        </span>
                        
                        <a 
                            href="{{ route('aluno.avisos') }}" 
                            class="text-sm text-gov-blue hover:text-gov-darkblue font-medium"
                        >
                            Ver todas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 