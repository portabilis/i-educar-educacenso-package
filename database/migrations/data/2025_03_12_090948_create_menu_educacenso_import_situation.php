<?php

use App\Menu;
use App\Process;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('educacenso_situation_imports', function (Blueprint $table): void {
            $table->id();
            $table->smallInteger('year');
            $table->string('school_name');
            $table->bigInteger('user_id');
            $table->smallInteger('status_id');
            $table->timestamps();
        });

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
        Schema::dropIfExists('educacenso_situation_imports');

        Menu::query()
            ->where('process', Process::EDUCACENSO_IMPORT_SITUATION)
            ->delete();
    }
};
