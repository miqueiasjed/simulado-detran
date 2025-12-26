<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CNH.Br - Simulado DETRAN</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>
    <body class="bg-gov-light text-slate-800 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <div class="h-1.5 w-full bg-gradient-to-r from-gov-green via-gov-yellow to-gov-blue mb-6"></div>
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a
                                href="{{ url('/admin') }}"
                                class="inline-block px-5 py-1.5 border border-gray-300 hover:border-gov-blue text-gray-700 hover:text-gov-blue rounded text-sm leading-normal transition-colors"
                            >
                                Painel Admin
                            </a>
                        @endif
                        <a
                            href="{{ route('aluno.simulados') }}"
                            class="inline-block px-5 py-1.5 border border-gray-300 hover:border-gov-blue text-gray-700 hover:text-gov-blue rounded text-sm leading-normal transition-colors"
                        >
                            Meus Simulados
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-block px-5 py-1.5 text-gov-red border border-transparent hover:border-red-200 rounded text-sm leading-normal transition-colors">
                                Sair
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 text-gray-700 border border-transparent hover:border-gray-300 rounded text-sm leading-normal transition-colors"
                        >
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 border border-gray-300 hover:border-gov-blue text-gray-700 hover:text-gov-blue rounded text-sm leading-normal transition-colors">
                                Registrar
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white shadow-sm border border-gray-200 rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    
                    {{-- Mensagens de Erro/Sucesso --}}
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-circle-exclamation text-gov-red mr-2"></i>
                                <span class="text-gov-red font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-check-circle text-gov-green mr-2"></i>
                                <span class="text-gov-green font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-gov-blue text-white w-12 h-12 flex items-center justify-center rounded shadow-sm text-xl">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <h1 class="mb-1 font-bold text-2xl text-gov-blue">CNH<span class="text-gov-green">.Br</span></h1>
                            <p class="text-sm text-gray-500 uppercase tracking-widest font-bold">Simulado DETRAN</p>
                        </div>
                    </div>
                    <p class="mb-4 text-gray-600">Prepare-se para a prova teórica do DETRAN com nossos simulados completos e atualizados.</p>
                    
                    @guest
                        <div class="mb-6 space-y-3">
                            <a href="{{ route('register') }}" 
                               class="block w-full text-center py-3 px-4 bg-gov-blue hover:bg-gov-darkblue text-white font-medium rounded shadow-md hover:shadow-lg transition-all">
                                Criar Conta Gratuita
                            </a>
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center py-3 px-4 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded transition-colors">
                                Já tenho conta
                            </a>
                            
                            {{-- Separador visual --}}
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Administradores</span>
                                </div>
                            </div>
                            
                            {{-- Botão para acesso ao painel admin --}}
                            <a href="{{ url('/admin/login') }}" 
                               class="block w-full text-center py-2 px-4 bg-gov-yellow hover:bg-yellow-600 text-yellow-900 font-medium rounded shadow-md hover:shadow-lg transition-all text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    Acesso Administrativo
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="mb-6 space-y-3">
                            @if(Auth::user()->isAdmin())
                                {{-- Botão especial para Admin --}}
                                <a href="{{ url('/admin') }}" 
                                   class="block w-full text-center py-3 px-4 bg-gov-yellow hover:bg-yellow-600 text-yellow-900 font-semibold rounded shadow-md hover:shadow-lg transition-all">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        Painel Administrativo
                                    </div>
                                </a>
                                
                                {{-- Separador visual --}}
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">ou</span>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Botão para Simulados (todos os usuários) --}}
                            <a href="{{ route('aluno.simulados') }}" 
                               class="block w-full text-center py-3 px-4 bg-gov-blue hover:bg-gov-darkblue text-white font-medium rounded shadow-md hover:shadow-lg transition-all">
                                @if(Auth::user()->isAdmin())
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-clipboard-question"></i>
                                        Testar Simulados (Modo Aluno)
                                    </div>
                                @else
                                    Acessar Simulados Disponíveis
                                @endif
                            </a>
                        </div>
                    @endguest

                    <ul class="flex flex-col mb-4 lg:mb-6 space-y-3">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gov-green/10 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-check text-gov-green text-sm"></i>
                            </div>
                            <span class="text-gray-700">
                                Questões atualizadas conforme legislação vigente
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gov-blue/10 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-check text-gov-blue text-sm"></i>
                            </div>
                            <span class="text-gray-700">
                                Correção automática e explicações detalhadas
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="bg-gradient-to-br from-gov-blue/10 to-gov-green/10 relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden border border-gray-200">
                    {{-- Conteúdo visual --}}
                    <div class="flex items-center justify-center h-full p-8">
                        <div class="text-center">
                            <div class="bg-gov-blue text-white w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fa-solid fa-id-card text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gov-blue mb-2">CNH<span class="text-gov-green">.Br</span></h2>
                            <p class="text-gray-600 text-sm">Plataforma Oficial de Simulação</p>
                        </div>
                    </div>
                    {{-- SVG removido --}}
                    <svg class="hidden" viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
                        <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618C83.4247 30.3618 78.5885 31.3389 74.201 33.2923C69.8111 35.2456 66.0474 37.928 62.9059 41.3333C59.7643 44.7401 57.3198 48.6726 55.5754 53.1293C53.8287 57.589 52.9572 62.274 52.9572 67.1813C52.9572 72.1925 53.8287 76.8995 55.5754 81.3069C57.3191 85.7173 59.7636 89.6241 62.9059 93.0293C66.0474 96.4361 69.8119 99.1155 74.201 101.069C78.5885 103.022 83.4247 103.999 88.7131 103.999C92.8016 103.999 96.8667 102.997 100.905 100.994C104.945 98.9911 108.061 96.2359 110.256 92.7282V102.195H126.563V32.1642H110.256V41.6337ZM108.76 75.7472C107.762 78.4531 106.366 80.8078 104.572 82.8112C102.776 84.8161 100.606 86.4183 98.0637 87.6206C95.5202 88.823 92.7004 89.4238 89.6103 89.4238C86.5178 89.4238 83.7252 88.823 81.2324 87.6206C78.7388 86.4183 76.5949 84.8161 74.7998 82.8112C73.004 80.8078 71.6319 78.4531 70.6856 75.7472C69.7356 73.0421 69.2644 70.1868 69.2644 67.1821C69.2644 64.1758 69.7356 61.3205 70.6856 58.6154C71.6319 55.9102 73.004 53.5571 74.7998 51.5522C76.5949 49.5495 78.738 47.9451 81.2324 46.7427C83.7252 45.5404 86.5178 44.9396 89.6103 44.9396C92.7012 44.9396 95.5202 45.5404 98.0637 46.7427C100.606 47.9451 102.776 49.5487 104.572 51.5522C106.367 53.5571 107.762 55.9102 108.76 58.6154C109.756 61.3205 110.256 64.1758 110.256 67.1821C110.256 70.1868 109.756 73.0421 108.76 75.7472Z" fill="currentColor" />
                        <path d="M242.805 41.6337C240.611 38.1275 237.494 35.3731 233.455 33.3681C229.416 31.3647 225.351 30.3618 221.262 30.3618C215.974 30.3618 211.138 31.3389 206.75 33.2923C202.36 35.2456 198.597 37.928 195.455 41.3333C192.314 44.7401 189.869 48.6726 188.125 53.1293C186.378 57.589 185.507 62.274 185.507 67.1813C185.507 72.1925 186.378 76.8995 188.125 81.3069C189.868 85.7173 192.313 89.6241 195.455 93.0293C198.597 96.4361 202.361 99.1155 206.75 101.069C211.138 103.022 215.974 103.999 221.262 103.999C225.351 103.999 229.416 102.997 233.455 100.994C237.494 98.9911 240.611 96.2359 242.805 92.7282V102.195H259.112V32.1642H242.805V41.6337ZM241.31 75.7472C240.312 78.4531 238.916 80.8078 237.122 82.8112C235.326 84.8161 233.156 86.4183 230.614 87.6206C228.07 88.823 225.251 89.4238 222.16 89.4238C219.068 89.4238 216.275 88.823 213.782 87.6206C211.289 86.4183 209.145 84.8161 207.35 82.8112C205.554 80.8078 204.182 78.4531 203.236 75.7472C202.286 73.0421 201.814 70.1868 201.814 67.1821C201.814 64.1758 202.286 61.3205 203.236 58.6154C204.182 55.9102 205.554 53.5571 207.35 51.5522C209.145 49.5495 211.288 47.9451 213.782 46.7427C216.275 45.5404 219.068 44.9396 222.16 44.9396C225.251 44.9396 228.07 45.5404 230.614 46.7427C233.156 47.9451 235.326 49.5487 237.122 51.5522C238.917 53.5571 240.312 55.9102 241.31 58.6154C242.306 61.3205 242.806 64.1758 242.806 67.1821C242.805 70.1868 242.305 73.0421 241.31 75.7472Z" fill="currentColor" />
                        <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
                        <path d="M139.43 102.197H155.735V48.2834H183.712V32.1665H139.43V102.197Z" fill="currentColor" />
                        <path d="M324.49 32.1665L303.995 85.794L283.498 32.1665H266.983L293.748 102.197H314.242L341.006 32.1665H324.49Z" fill="currentColor" />
                        <path d="M376.571 30.3656C356.603 30.3656 340.797 46.8497 340.797 67.1828C340.797 89.6597 356.094 104 378.661 104C391.29 104 399.354 99.1488 409.206 88.5848L398.189 80.0226C398.183 80.031 389.874 90.9895 377.468 90.9895C363.048 90.9895 356.977 79.3111 356.977 73.269H411.075C413.917 50.1328 398.775 30.3656 376.571 30.3656ZM357.02 61.0967C357.145 59.7487 359.023 43.3761 376.442 43.3761C393.861 43.3761 395.978 59.7464 396.099 61.0967H357.02Z" fill="currentColor" />
                    </svg>

                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
