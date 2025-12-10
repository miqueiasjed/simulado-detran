<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria ou atualiza 2 admins fixos sem usar faker
        // Usa updateOrCreate para garantir que o tipo seja sempre 'admin'
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin2@admin.com'],
            [
                'name' => 'Admin 2',
                'password' => Hash::make('password'),
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
