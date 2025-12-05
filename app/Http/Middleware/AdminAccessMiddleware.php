<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir acesso à rota de login do Filament (não verificar admin antes de autenticar)
        if ($request->is('admin/login') || $request->is('admin/login/*')) {
            return $next($request);
        }

        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            // Para requisições AJAX/API, retornar 401 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Não autenticado'], 401);
            }
            return redirect()->route('filament.admin.auth.login');
        }

        // Buscar o usuário diretamente do banco para garantir dados atualizados
        // Isso é importante em produção onde pode haver cache de sessão
        try {
            $user = \App\Models\User::find(Auth::id());
            if (!$user) {
                Auth::logout();
                return redirect()->route('filament.admin.auth.login');
            }
        } catch (\Exception $e) {
            // Se houver erro, usar o usuário da sessão
            Log::warning('Erro ao buscar usuário no AdminAccessMiddleware: ' . $e->getMessage());
            $user = Auth::user();
        }

        // Verificar se o campo tipo existe e se o usuário é admin
        if (!$user || !$user->tipo || $user->tipo !== 'admin') {
            // Para requisições AJAX/API, retornar 403 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Acesso negado. Apenas administradores podem acessar o painel administrativo.',
                    'user_tipo' => $user->tipo ?? 'null'
                ], 403);
            }
            // Se não for admin, redirecionar para a página inicial com mensagem de erro
            return redirect('/')->with('error', 'Acesso negado. Apenas administradores podem acessar o painel administrativo.');
        }

        // Se for admin, permitir acesso
        return $next($request);
    }
}
