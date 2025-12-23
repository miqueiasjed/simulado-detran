<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CNH.Br') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gov-light text-slate-800 font-sans h-screen flex flex-col overflow-hidden">

    <div class="h-1.5 w-full bg-gradient-to-r from-gov-green via-gov-yellow to-gov-blue shrink-0"></div>

    <div class="flex flex-1 overflow-hidden">

        <div id="mobile-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-white border-r border-gray-200 flex flex-col z-50 lg:z-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-xl lg:shadow-none h-full overflow-y-auto">
            <div class="flex-1 flex flex-col">
                <div class="lg:hidden p-4 flex justify-end border-b border-gray-100">
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gov-blue">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-6 flex items-center gap-3">
                    <div class="bg-gov-blue text-white w-10 h-10 flex items-center justify-center rounded shadow-sm text-lg">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl font-bold text-gov-blue leading-none">CNH<span class="text-gov-green">.Br</span></h1>
                        <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Área do Aluno</span>
                    </div>
                </div>

                <div class="px-6 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e0f2fe&color=1351b4" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-blue-100">
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">
                                @if(Auth::user()->cpf)
                                    CPF: {{ Auth::user()->cpf }}
                                @else
                                    Aluno
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <nav class="mt-6 px-4 space-y-1 flex-1">
                    <a href="{{ route('aluno.simulados') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('aluno.simulados') ? 'bg-gov-blue text-white shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-gov-blue' }} rounded transition-all">
                        <i class="fa-solid fa-house w-5 text-center"></i>
                        <span class="font-medium text-sm">Simulados</span>
                    </a>

                    <a href="{{ route('aluno.cursos') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('aluno.cursos') || request()->routeIs('aluno.curso.*') ? 'bg-gov-blue text-white shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-gov-blue' }} rounded transition-colors group">
                        <i class="fa-solid fa-book-open-reader w-5 text-center group-hover:text-gov-blue transition-colors"></i>
                        <span class="font-medium text-sm">Cursos</span>
                    </a>

                    <a href="{{ route('aluno.resultados') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('aluno.resultados') ? 'bg-gov-blue text-white shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-gov-blue' }} rounded transition-colors group">
                        <i class="fa-solid fa-chart-line w-5 text-center group-hover:text-gov-blue transition-colors"></i>
                        <span class="font-medium text-sm">Meus Resultados</span>
                    </a>

                    <a href="{{ route('aluno.conta') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('aluno.conta') ? 'bg-gov-blue text-white shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-gov-blue' }} rounded transition-colors group">
                        <i class="fa-solid fa-gear w-5 text-center group-hover:text-gov-blue transition-colors"></i>
                        <span class="font-medium text-sm">Minha Conta</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-100 mt-auto">
                <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2 text-gov-red hover:bg-red-50 rounded transition-colors font-medium text-sm w-full">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Sair do Sistema</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-gov-light p-4 md:p-8 w-full min-w-0">
            <button onclick="toggleSidebar()" class="lg:hidden text-gov-blue mb-4">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>

            {{ $slot }}
        </main>
    </div>

    <!-- Banner Carrossel (para alunos e admins na área de alunos) -->
    @if(Auth::check() && (Auth::user()->isAluno() || Auth::user()->isAdmin()))
        @livewire('banner-carrossel')
    @endif

    <!-- Avisos Pop-up -->
    @if(Auth::check())
        @livewire('avisos-popup')
    @endif

    <!-- Notificações Sino -->
    @if(Auth::check())
        @livewire('notificacoes-sino')
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>

    @livewireScripts
</body>
</html>
