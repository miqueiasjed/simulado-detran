<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Simulado DETRAN') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gov-light min-h-screen">
    <div class="h-1.5 w-full bg-gradient-to-r from-gov-green via-gov-yellow to-gov-blue"></div>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="bg-gov-blue text-white w-12 h-12 flex items-center justify-center rounded shadow-sm text-xl">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-bold text-gov-blue leading-none">CNH<span class="text-gov-green">.Br</span></h1>
                        <span class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Simulado DETRAN</span>
                    </div>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Faça login na sua conta
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou
                    <a href="{{ route('register') }}" class="font-medium text-gov-blue hover:text-gov-darkblue">
                        crie uma nova conta
                    </a>
                </p>
            </div>

            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-check-circle text-gov-green"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gov-green">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-gov-blue focus:border-gov-blue focus:z-10 sm:text-sm bg-white" 
                               placeholder="Email" 
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-gov-red">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="sr-only">Senha</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-gov-blue focus:border-gov-blue focus:z-10 sm:text-sm bg-white" 
                               placeholder="Senha">
                        @error('password')
                            <p class="mt-1 text-sm text-gov-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-gov-blue focus:ring-gov-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Lembrar de mim
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-gov-blue hover:text-gov-darkblue">
                            Esqueceu sua senha?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gov-blue hover:bg-gov-darkblue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gov-blue shadow-md hover:shadow-lg transition-all">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fa-solid fa-lock text-white/80 group-hover:text-white"></i>
                        </span>
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 