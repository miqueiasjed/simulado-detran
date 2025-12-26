<div class="bg-white rounded shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Avisos</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">
                {{ count($avisos) }} aviso(s)
            </span>
        </div>
    </div>

    @if(count($avisos) > 0)
        <div class="space-y-4">
            @foreach($avisos as $aviso)
                @php
                    $isLido = in_array($aviso['id'], $avisosLidos);
                    $tipoColor = match($aviso['tipo']) {
                        'informacao' => 'border-blue-200 bg-blue-50',
                        'aviso' => 'border-yellow-200 bg-yellow-50',
                        'erro' => 'border-red-200 bg-red-50',
                        'sucesso' => 'border-green-200 bg-green-50',
                        default => 'border-gray-200 bg-gray-50'
                    };
                    
                    $tipoIcon = match($aviso['tipo']) {
                        'informacao' => 'heroicon-o-information-circle',
                        'aviso' => 'heroicon-o-exclamation-triangle',
                        'erro' => 'heroicon-o-x-circle',
                        'sucesso' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-megaphone'
                    };
                    
                    $tipoIconColor = match($aviso['tipo']) {
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

                <div class="border rounded-lg p-4 {{ $tipoColor }} {{ $isLido ? 'opacity-75' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3 flex-1">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 {{ $tipoIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $aviso['titulo'] }}
                                        @if($isLido)
                                            <span class="ml-2 text-sm text-gray-500">(Lido)</span>
                                        @endif
                                    </h3>
                                    
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $prioridadeColor }}">
                                            {{ ucfirst($aviso['prioridade']) }}
                                        </span>
                                        
                                        @if($aviso['mostrar_popup'])
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                                Pop-up
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mt-2 prose prose-sm max-w-none">
                                    {!! $aviso['conteudo'] !!}
                                </div>
                                
                                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <span>
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($aviso['created_at'])->format('d/m/Y H:i') }}
                                        </span>
                                        
                                        @if($aviso['data_inicio'] || $aviso['data_fim'])
                                            <span>
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @if($aviso['data_inicio'] && $aviso['data_fim'])
                                                    {{ \Carbon\Carbon::parse($aviso['data_inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($aviso['data_fim'])->format('d/m/Y') }}
                                                @elseif($aviso['data_inicio'])
                                                    A partir de {{ \Carbon\Carbon::parse($aviso['data_inicio'])->format('d/m/Y') }}
                                                @elseif($aviso['data_fim'])
                                                    Até {{ \Carbon\Carbon::parse($aviso['data_fim'])->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if(!$isLido)
                                        <button
                                            wire:click="marcarComoLido({{ $aviso['id'] }})"
                                            class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 border border-blue-200 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            Marcar como Lido
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum aviso</h3>
            <p class="mt-1 text-sm text-gray-500">
                Não há avisos para exibir no momento.
            </p>
        </div>
    @endif
</div> 