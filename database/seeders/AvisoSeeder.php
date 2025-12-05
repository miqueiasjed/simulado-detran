<?php

namespace Database\Seeders;

use App\Models\Aviso;
use Illuminate\Database\Seeder;

class AvisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aviso de boas-vindas para todoss
        Aviso::create([
            'titulo' => 'Bem-vindo ao Sistema de Simulados DETRAN!',
            'conteudo' => '<p>Seja bem-vindo ao nosso sistema de simulados para o DETRAN. Aqui você pode:</p><ul><li>Realizar simulados para testar seus conhecimentos</li><li>Acompanhar seu progresso</li><li>Ver seus resultados detalhados</li><li>Estudar com questões organizadas por categoria</li></ul><p>Boa sorte nos seus estudos!</p>',
            'tipo' => 'informacao',
            'prioridade' => 'media',
            'ativo' => true,
            'destinatarios' => ['todos'],
            'mostrar_popup' => true,
            'cor_fundo' => '#f0f9ff',
            'cor_texto' => '#1e40af',
        ]);

        // Aviso específico para alunos
        Aviso::create([
            'titulo' => 'Dica para Alunos: Como Estudar Eficientemente',
            'conteudo' => '<p><strong>Dicas para maximizar seu aprendizado:</strong></p><ol><li>Faça simulados regularmente</li><li>Revise as questões que errou</li><li>Estude por categorias</li><li>Mantenha um cronograma de estudos</li><li>Pratique com questões similares às do DETRAN</li></ol>',
            'tipo' => 'aviso',
            'prioridade' => 'baixa',
            'ativo' => true,
            'destinatarios' => ['aluno'],
            'mostrar_popup' => false,
            'cor_fundo' => '#fef3c7',
            'cor_texto' => '#92400e',
        ]);

        // Aviso específico para admins
        Aviso::create([
            'titulo' => 'Atualização do Sistema - Painel Administrativo',
            'conteudo' => '<p>O painel administrativo foi atualizado com novas funcionalidades:</p><ul><li>Sistema de avisos com pop-ups</li><li>Estatísticas em tempo real</li><li>Melhor gerenciamento de usuários</li><li>Relatórios detalhados</li></ul><p>Explore as novas funcionalidades disponíveis!</p>',
            'tipo' => 'sucesso',
            'prioridade' => 'media',
            'ativo' => true,
            'destinatarios' => ['admin'],
            'mostrar_popup' => true,
            'cor_fundo' => '#ecfdf5',
            'cor_texto' => '#065f46',
        ]);

        // Aviso de manutenção (exemplo)
        Aviso::create([
            'titulo' => 'Manutenção Programada - 15/01/2025',
            'conteudo' => '<p>Informamos que haverá uma manutenção programada no sistema no dia <strong>15/01/2025</strong> das <strong>02:00 às 04:00</strong>.</p><p>Durante este período, o sistema pode ficar temporariamente indisponível.</p><p>Agradecemos a compreensão!</p>',
            'tipo' => 'aviso',
            'prioridade' => 'alta',
            'ativo' => true,
            'destinatarios' => ['todos'],
            'mostrar_popup' => true,
            'cor_fundo' => '#fef2f2',
            'cor_texto' => '#991b1b',
            'data_inicio' => now(),
            'data_fim' => now()->addDays(30),
        ]);

        // Aviso sobre novas questões
        Aviso::create([
            'titulo' => 'Novas Questões Disponíveis!',
            'conteudo' => '<p>Adicionamos <strong>50 novas questões</strong> ao banco de dados!</p><p>As questões incluem:</p><ul><li>Legislação de trânsito atualizada</li><li>Questões sobre direção defensiva</li><li>Problemas de mecânica básica</li><li>Primeiros socorros</li></ul><p>Atualize seus conhecimentos com as novas questões!</p>',
            'tipo' => 'sucesso',
            'prioridade' => 'media',
            'ativo' => true,
            'destinatarios' => ['aluno'],
            'mostrar_popup' => false,
            'cor_fundo' => '#ecfdf5',
            'cor_texto' => '#065f46',
        ]);
    }
}
