<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:ensure {email=admin@admin.com} {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Garante que um usuário admin existe e tem o tipo correto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        $user = User::where('email', $email)->first();

        if ($user) {
            // Atualiza o usuário existente para garantir que seja admin
            $user->update([
                'tipo' => 'admin',
                'password' => Hash::make($password),
            ]);
            $this->info("Usuário {$email} atualizado para admin com sucesso!");
        } else {
            // Cria novo usuário admin
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->info("Usuário admin {$email} criado com sucesso!");
        }

        // Verifica e lista todos os admins
        $admins = User::where('tipo', 'admin')->get(['id', 'name', 'email', 'tipo']);
        $this->info("\nUsuários admin no sistema:");
        foreach ($admins as $admin) {
            $this->line("  - ID: {$admin->id} | {$admin->name} ({$admin->email})");
        }

        return Command::SUCCESS;
    }
}
