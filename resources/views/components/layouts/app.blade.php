<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Simulado DETRAN') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('aluno.simulados') }}" class="text-xl font-bold text-gray-900 dark:text-white">
                            Simulado DETRAN
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('aluno.simulados') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('aluno.simulados') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                        Simulados
                    </a>
                    <a href="{{ route('aluno.cursos') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('aluno.cursos') || request()->routeIs('aluno.curso.*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                        Cursos
                    </a>
                    <a href="{{ route('aluno.resultados') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('aluno.resultados') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                        Meus Resultados
                    </a>
                    <a href="{{ route('aluno.conta') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('aluno.conta') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                        Minha Conta
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Sino de Notificações -->
                    @if(Auth::check())
                        @livewire('notificacoes-sino')
                    @endif

                    <!-- User Info -->
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Olá, {{ Auth::user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Sair
                            </button>
                        </form>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" 
                                class="mobile-menu-button text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 p-2 rounded-md"
                                aria-controls="mobile-menu" 
                                aria-expanded="false">
                            <span class="sr-only">Abrir menu principal</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 dark:bg-gray-700">
                <a href="{{ route('aluno.simulados') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('aluno.simulados') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                    Simulados
                </a>
                <a href="{{ route('aluno.cursos') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('aluno.cursos') || request()->routeIs('aluno.curso.*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                    Cursos
                </a>
                <a href="{{ route('aluno.resultados') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('aluno.resultados') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                    Meus Resultados
                </a>
                <a href="{{ route('aluno.conta') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('aluno.conta') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                    Minha Conta
                </a>
                <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                    <div class="px-3 py-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="px-3">
                        @csrf
                        <button type="submit" 
                                class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 block w-full text-left py-2 rounded-md text-base font-medium">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Banner Carrossel (para alunos e admins na área de alunos) -->
    @if(Auth::check() && (Auth::user()->isAluno() || Auth::user()->isAdmin()))
        @livewire('banner-carrossel')
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Avisos Pop-up -->
    @if(Auth::check())
        @livewire('avisos-popup')
    @endif

    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    @livewireScripts
</body>
</html> 