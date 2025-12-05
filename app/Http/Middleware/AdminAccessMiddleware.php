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
        // Permitir acesso a todas as rotas de autenticação do Filament
        $authRoutes = [
            'admin/login',
            'admin/logout',
            'admin/password/reset',
            'admin/password/confirm',
        ];

        foreach ($authRoutes as $route) {
            if ($request->is($route) || $request->is($route . '/*')) {
                return $next($request);
            }
        }

        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            // Para requisições AJAX/API, retornar 401 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Não autenticado'], 401);
            }
            // Redirecionar para login do Filament (sempre usar redirect, nunca 403)
            return redirect()->to('/admin/login');
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
            // Log para debug
            Log::warning('Tentativa de acesso ao admin por usuário não-admin', [
                'user_id' => $user->id ?? 'null',
                'user_email' => $user->email ?? 'null',
                'user_tipo' => $user->tipo ?? 'null',
                'path' => $request->path(),
            ]);

            // Para requisições AJAX/API, retornar 403 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Acesso negado. Apenas administradores podem acessar o painel administrativo.',
                    'user_tipo' => $user->tipo ?? 'null'
                ], 403);
            }

            // Fazer logout e redirecionar para login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Acesso negado. Apenas administradores podem acessar o painel administrativo.');
        }

        // Se for admin, permitir acesso
        return $next($request);
    }
}
