<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria os 2 admins só se ainda não existirem: rodar o seed de novo nunca
        // troca a senha de quem já está em produção. A senha vem do .env
        // (SEED_ADMIN_PASSWORD); sem ela, sai aleatória e se define depois.
        $senha = env('SEED_ADMIN_PASSWORD') ?: Str::random(32);

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make($senha),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin2@admin.com'],
            [
                'name' => 'Admin 2',
                'password' => Hash::make($senha),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Cria 10 alunos
        // User::factory(10)->create(['tipo' => 'aluno']);

        // Criar categorias usando CategoriaSeeder
        $this->call(CategoriaSeeder::class);

        // Criar questões usando QuestaoSeeder
        $this->call(QuestaoSeeder::class);

        // Criar simulados com questões relacionadas usando SimuladoSeeder
        $this->call(SimuladoSeeder::class);

        // Criar avisos usando AvisoSeeder
        $this->call(AvisoSeeder::class);

        // Criar banners usando BannerSeeder
        $this->call(BannerSeeder::class);
    }
}
