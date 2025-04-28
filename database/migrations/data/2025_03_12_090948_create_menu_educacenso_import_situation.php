<?php

use App\Menu;
use App\Process;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        Menu::query()->create([
            'parent_id' => Menu::query()->where('old', Process::EDUCACENSO_IMPORTACOES)->value('id'),
            'parent_old' => Process::EDUCACENSO_IMPORTACOES,
            'title' => 'Importação de Situações',
            'link' => '/educacenso/importacao/situacao',
            'process' => Process::EDUCACENSO_IMPORT_SITUATION,
        ]);
    }

    public function down(): void
    {
        Menu::query()
            ->where('process', Process::EDUCACENSO_IMPORT_SITUATION)
            ->delete();
    }
};
