<?php

namespace App\Livewire;

use App\Models\Aviso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminNotificacoesSino extends Component
{
    public $avisos = [];
    public $avisosNaoLidos = 0;
    public $mostrarModal = false;

    protected $listeners = ['aviso-lido' => 'atualizarContador'];

    public function mount()
    {
        if (Auth::check()) {
            $this->carregarAvisos();
            $this->contarNaoLidos();
        }
    }

    public function carregarAvisos()
    {
        try {
            $user = Auth::user();
            
            $this->avisos = Aviso::where('ativo', true)
                ->where(function ($query) use ($user) {
                    $query->whereJsonContains('destinatarios', $user->tipo)
                          ->orWhereJsonContains('destinatarios', 'todos');
                })
                ->where(function ($query) {
                    $query->whereNull('data_inicio')
                          ->orWhere('data_inicio', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('data_fim')
                          ->orWhere('data_fim', '>=', now());
                })
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            $this->avisos = [];
        }
    }

    public function contarNaoLidos()
    {
        try {
            $user = Auth::user();
            
            $this->avisosNaoLidos = Aviso::where('ativo', true)
                ->where(function ($query) use ($user) {
                    $query->whereJsonContains('destinatarios', $user->tipo)
                          ->orWhereJsonContains('destinatarios', 'todos');
                })
                ->where(function ($query) {
                    $query->whereNull('data_inicio')
                          ->orWhere('data_inicio', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('data_fim')
                          ->orWhere('data_fim', '>=', now());
                })
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->whereNotNull('lido_em');
                })
                ->count();
        } catch (\Exception $e) {
            $this->avisosNaoLidos = 0;
        }
    }

    public function abrirModal()
    {
        $this->mostrarModal = true;
        $this->carregarAvisos();
    }

    public function fecharModal()
    {
        $this->mostrarModal = false;
    }

    public function marcarComoLido($avisoId)
    {
        $user = Auth::user();
        $aviso = Aviso::find($avisoId);
        
        if ($aviso && $user) {
            // Marcar como lido
            $user->avisos()->syncWithoutDetaching([
                $avisoId => ['lido_em' => now()]
            ]);
            
            // Atualizar contadores
            $this->contarNaoLidos();
            $this->carregarAvisos();
            
            $this->dispatch('aviso-lido', $avisoId);
        }
    }

    public function marcarTodosComoLidos()
    {
        $user = Auth::user();
        
        if ($user) {
            // Buscar todos os avisos não lidos
            $avisosNaoLidos = Aviso::where('ativo', true)
                ->where(function ($query) use ($user) {
                    $query->whereJsonContains('destinatarios', $user->tipo)
                          ->orWhereJsonContains('destinatarios', 'todos');
                })
                ->where(function ($query) {
                    $query->whereNull('data_inicio')
                          ->orWhere('data_inicio', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('data_fim')
                          ->orWhere('data_fim', '>=', now());
                })
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->whereNotNull('lido_em');
                })
                ->get();

            // Marcar todos como lidos
            foreach ($avisosNaoLidos as $aviso) {
                $user->avisos()->syncWithoutDetaching([
                    $aviso->id => ['lido_em' => now()]
                ]);
            }
            
            $this->contarNaoLidos();
            $this->carregarAvisos();
        }
    }

    public function atualizarContador()
    {
        $this->contarNaoLidos();
    }

    public function render()
    {
        return view('livewire.admin-notificacoes-sino');
    }
} 