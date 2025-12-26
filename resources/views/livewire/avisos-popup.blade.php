<div>
    @if(count($avisos) > 0)
        @foreach($avisos as $aviso)
            <div 
                x-data="{ 
                    show: true,
                    avisoId: {{ $aviso['id'] }}
                }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background-color: rgba(0, 0, 0, 0.5);"
            >
                <div 
                    class="relative w-full max-w-md mx-auto bg-white rounded-lg shadow-xl overflow-hidden"
                    style="
                        background-color: {{ $aviso['cor_fundo'] ?? '#ffffff' }};
                        color: {{ $aviso['cor_texto'] ?? '#000000' }};
                    "
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            @php
                                $tipoIcon = match($aviso['tipo']) {
                                    'informacao' => 'heroicon-o-information-circle',
                                    'aviso' => 'heroicon-o-exclamation-triangle',
                                    'erro' => 'heroicon-o-x-circle',
                                    'sucesso' => 'heroicon-o-check-circle',
                                    default => 'heroicon-o-megaphone'
                                };
                                
                                $tipoColor = match($aviso['tipo']) {
                                    'informacao' => 'text-blue-500',
                                    'aviso' => 'text-yellow-500',
                                    'erro' => 'text-red-500',
                                    'sucesso' => 'text-green-500',
                                    default => 'text-gray-500'
                                };
                                
                                $prioridadeColor = match($aviso['prioridade']) {
                                    'baixa' => 'bg-green-100 text-green-800',
                                    'media' => 'bg-yellow-100 text-yellow-800',
                                    'alta' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            
                            <svg class="w-6 h-6 {{ $tipoColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            
                            <h3 class="text-lg font-semibold">{{ $aviso['titulo'] }}</h3>
                        </div>
                        
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $prioridadeColor }}">
                            {{ ucfirst($aviso['prioridade']) }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="prose prose-sm max-w-none">
                            {!! $aviso['conteudo'] !!}
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end space-x-2 p-4 border-t border-gray-200">
                        <button
                            type="button"
                            @click="show = false; $wire.fecharPopup(avisoId)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        >
                            Fechar
                        </button>
                        
                        <button
                            type="button"
                            @click="show = false; $wire.marcarComoLido(avisoId)"
                            class="px-4 py-2 text-sm font-medium text-white bg-gov-blue border border-transparent rounded-md hover:bg-gov-darkblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gov-blue"
                        >
                            Marcar como Lido
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @script
    <script>
        // Auto-hide popups after 10 seconds if not interacted with
        document.addEventListener('DOMContentLoaded', function() {
            const popups = document.querySelectorAll('[x-data*="avisoId"]');
            popups.forEach(popup => {
                setTimeout(() => {
                    const show = popup.__x.$data.show;
                    if (show) {
                        popup.__x.$data.show = false;
                    }
                }, 10000);
            });
        });
    </script>
    @endscript
</div> 