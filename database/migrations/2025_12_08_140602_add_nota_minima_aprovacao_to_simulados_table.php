<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('simulados', function (Blueprint $table) {
            $table->decimal('nota_minima_aprovacao', 3, 1)
                  ->default(7.0)
                  ->after('numero_questoes')
                  ->comment('Nota mínima (0-10) para aprovação no simulado');
        });
        
        // Backfill: atualizar registros existentes que possam ter NULL
        // Isso garante que todos os simulados tenham um valor válido
        DB::table('simulados')
            ->whereNull('nota_minima_aprovacao')
            ->update(['nota_minima_aprovacao' => 7.0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulados', function (Blueprint $table) {
            $table->dropColumn('nota_minima_aprovacao');
        });
    }
};
