<?php

namespace Database\Seeders;

use App\Models\Simulado;
use App\Models\Categoria;
use App\Models\Questao;
use Illuminate\Database\Seeder;

class SimuladoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::all();
        
        // Simulado 1: Simulado Completo DETRAN
        $simulado1 = Simulado::create([
            'titulo' => 'Simulado Completo DETRAN',
            'descricao' => 'Simulado completo com questões de todas as categorias para preparação ao exame do DETRAN',
            'tempo_limite' => 30,
            'numero_questoes' => 30,
            'nota_minima_aprovacao' => 7.0,
            'ativo' => true,
        ]);

        // Associa categorias ao simulado 1 com quantidades
        $mecanica = $categorias->where('nome', 'Mecânica Básica')->first();
        $legislacao = $categorias->where('nome', 'Legislação')->first();
        $meioAmbiente = $categorias->where('nome', 'Meio Ambiente')->first();
        $direcaoDefensiva = $categorias->where('nome', 'Direção Defensiva')->first();
        $sinalizacao = $categorias->where('nome', 'Sinalização')->first();
        $primeirosSocorros = $categorias->where('nome', 'Primeiros Socorros')->first();

        if ($mecanica) {
            $simulado1->categorias()->attach($mecanica->id, ['quantidade_questoes' => 5]);
            $questoesMecanica = Questao::where('categoria_id', $mecanica->id)->limit(5)->get();
            $simulado1->questoes()->attach($questoesMecanica->pluck('id'));
        }

        if ($legislacao) {
            $simulado1->categorias()->attach($legislacao->id, ['quantidade_questoes' => 8]);
            $questoesLegislacao = Questao::where('categoria_id', $legislacao->id)->limit(8)->get();
            $simulado1->questoes()->attach($questoesLegislacao->pluck('id'));
        }

        if ($meioAmbiente) {
            $simulado1->categorias()->attach($meioAmbiente->id, ['quantidade_questoes' => 3]);
            $questoesMeioAmbiente = Questao::where('categoria_id', $meioAmbiente->id)->limit(3)->get();
            $simulado1->questoes()->attach($questoesMeioAmbiente->pluck('id'));
        }

        if ($direcaoDefensiva) {
            $simulado1->categorias()->attach($direcaoDefensiva->id, ['quantidade_questoes' => 5]);
            $questoesDirecaoDefensiva = Questao::where('categoria_id', $direcaoDefensiva->id)->limit(5)->get();
            $simulado1->questoes()->attach($questoesDirecaoDefensiva->pluck('id'));
        }

        if ($sinalizacao) {
            $simulado1->categorias()->attach($sinalizacao->id, ['quantidade_questoes' => 5]);
            $questoesSinalizacao = Questao::where('categoria_id', $sinalizacao->id)->limit(5)->get();
            $simulado1->questoes()->attach($questoesSinalizacao->pluck('id'));
        }

        if ($primeirosSocorros) {
            $simulado1->categorias()->attach($primeirosSocorros->id, ['quantidade_questoes' => 4]);
            $questoesPrimeirosSocorros = Questao::where('categoria_id', $primeirosSocorros->id)->limit(4)->get();
            $simulado1->questoes()->attach($questoesPrimeirosSocorros->pluck('id'));
        }

        // Simulado 2: Foco em Legislação
        $simulado2 = Simulado::create([
            'titulo' => 'Simulado de Legislação',
            'descricao' => 'Simulado focado em questões de legislação de trânsito',
            'tempo_limite' => 20,
            'numero_questoes' => 20,
            'nota_minima_aprovacao' => 7.0,
            'ativo' => true,
        ]);

        if ($legislacao) {
            $simulado2->categorias()->attach($legislacao->id, ['quantidade_questoes' => 20]);
            $questoesLegislacao2 = Questao::where('categoria_id', $legislacao->id)->limit(20)->get();
            $simulado2->questoes()->attach($questoesLegislacao2->pluck('id'));
        }

        // Simulado 3: Mecânica e Direção Defensiva
        $simulado3 = Simulado::create([
            'titulo' => 'Simulado Mecânica e Direção Defensiva',
            'descricao' => 'Simulado com questões de mecânica básica e direção defensiva',
            'tempo_limite' => 25,
            'numero_questoes' => 25,
            'nota_minima_aprovacao' => 7.0,
            'ativo' => true,
        ]);

        if ($mecanica) {
            $simulado3->categorias()->attach($mecanica->id, ['quantidade_questoes' => 12]);
            $questoesMecanica3 = Questao::where('categoria_id', $mecanica->id)->limit(12)->get();
            $simulado3->questoes()->attach($questoesMecanica3->pluck('id'));
        }

        if ($direcaoDefensiva) {
            $simulado3->categorias()->attach($direcaoDefensiva->id, ['quantidade_questoes' => 13]);
            $questoesDirecaoDefensiva3 = Questao::where('categoria_id', $direcaoDefensiva->id)->limit(13)->get();
            $simulado3->questoes()->attach($questoesDirecaoDefensiva3->pluck('id'));
        }

        // Simulado 4: Sinalização e Primeiros Socorros
        $simulado4 = Simulado::create([
            'titulo' => 'Simulado Sinalização e Primeiros Socorros',
            'descricao' => 'Simulado focado em sinalização de trânsito e primeiros socorros',
            'tempo_limite' => 15,
            'numero_questoes' => 15,
            'nota_minima_aprovacao' => 7.0,
            'ativo' => true,
        ]);

        if ($sinalizacao) {
            $simulado4->categorias()->attach($sinalizacao->id, ['quantidade_questoes' => 8]);
            $questoesSinalizacao4 = Questao::where('categoria_id', $sinalizacao->id)->limit(8)->get();
            $simulado4->questoes()->attach($questoesSinalizacao4->pluck('id'));
        }

        if ($primeirosSocorros) {
            $simulado4->categorias()->attach($primeirosSocorros->id, ['quantidade_questoes' => 7]);
            $questoesPrimeirosSocorros4 = Questao::where('categoria_id', $primeirosSocorros->id)->limit(7)->get();
            $simulado4->questoes()->attach($questoesPrimeirosSocorros4->pluck('id'));
        }

        // Simulado 5: Simulado Rápido
        $simulado5 = Simulado::create([
            'titulo' => 'Simulado Rápido',
            'descricao' => 'Simulado rápido com questões variadas para prática',
            'tempo_limite' => 15,
            'numero_questoes' => 15,
            'nota_minima_aprovacao' => 7.0,
            'ativo' => true,
        ]);

        if ($legislacao) {
            $simulado5->categorias()->attach($legislacao->id, ['quantidade_questoes' => 5]);
            $questoesLegislacao5 = Questao::where('categoria_id', $legislacao->id)->limit(5)->get();
            $simulado5->questoes()->attach($questoesLegislacao5->pluck('id'));
        }

        if ($sinalizacao) {
            $simulado5->categorias()->attach($sinalizacao->id, ['quantidade_questoes' => 5]);
            $questoesSinalizacao5 = Questao::where('categoria_id', $sinalizacao->id)->limit(5)->get();
            $simulado5->questoes()->attach($questoesSinalizacao5->pluck('id'));
        }

        if ($direcaoDefensiva) {
            $simulado5->categorias()->attach($direcaoDefensiva->id, ['quantidade_questoes' => 5]);
            $questoesDirecaoDefensiva5 = Questao::where('categoria_id', $direcaoDefensiva->id)->limit(5)->get();
            $simulado5->questoes()->attach($questoesDirecaoDefensiva5->pluck('id'));
        }
    }
}

