<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Aluno\SimuladosDisponiveis;
use App\Livewire\Aluno\QuizSimulado;
use App\Livewire\Aluno\MeusResultados;
use App\Livewire\Aluno\MinhaConta;
use App\Livewire\Aluno\VerResultadoSimulado;
use App\Livewire\Aluno\CursosDisponiveis;
use App\Livewire\Aluno\AssistirCurso;
use App\Livewire\AvisosAluno;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AvisoController;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');



Route::get('/admin/questoes/{questao}/dados', function ($questao) {
    $questao = \App\Models\Questao::with('categoria')->findOrFail($questao);
    return response()->json($questao);
})->name('admin.questoes.dados');

Route::post('/admin/simulados/{record}/adicionar-questoes-modal/associate-questoes', [\App\Filament\Resources\SimuladoResource\Pages\AdicionarQuestoesModal::class, 'associateQuestoes'])
    ->name('filament.admin.resources.simulados.adicionar-questoes-modal.associate-questoes');

// Rotas para o sistema de avisos
Route::middleware(['auth'])->group(function () {
    Route::get('/avisos', [AvisoController::class, 'index'])->name('avisos.index');
    Route::get('/avisos/popups', [AvisoController::class, 'popups'])->name('avisos.popups');
    Route::post('/avisos/{id}/marcar-lido', [AvisoController::class, 'marcarComoLido'])->name('avisos.marcar-lido');
    Route::get('/avisos/stats', [AvisoController::class, 'stats'])->name('avisos.stats')->middleware('admin.access');
});

Route::middleware(['auth', 'verified', 'aluno.access'])->group(function () {
    Route::get('/aluno/simulados', SimuladosDisponiveis::class)->name('aluno.simulados');
    Route::get('/aluno/simulado/{simuladoId}/quiz', QuizSimulado::class)->name('aluno.simulado.quiz');
    Route::get('/aluno/simulado/{simuladoId}/resultado', VerResultadoSimulado::class)->name('aluno.simulado.resultado');
    Route::get('/aluno/resultados', MeusResultados::class)->name('aluno.resultados');
    Route::get('/aluno/conta', MinhaConta::class)->name('aluno.conta');
    Route::get('/aluno/avisos', AvisosAluno::class)->name('aluno.avisos');
    
    // Rotas de Cursos
    Route::get('/aluno/cursos', CursosDisponiveis::class)->name('aluno.cursos');
    Route::get('/aluno/curso/{cursoId}/assistir', AssistirCurso::class)->name('aluno.curso.assistir');
});
