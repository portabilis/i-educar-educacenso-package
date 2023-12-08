<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $educacensoMenu = Menu::query()
            ->where('title', 'Educacenso')
            ->first();

        $menuExportacao = Menu::query()
            ->where('title', 'Exportações')
            ->where('parent_id', $educacensoMenu->getKey())
            ->first();

        if($menuExportacao) {
            Menu::updateOrCreate([
                'process' => 9998845,
            ], [
                'parent_id' => $menuExportacao->getKey(),
                'title' => '2ª fase - Situação final',
                'description' => 'Exportação do educacenso - 2ª fase',
                'link' => '/educacenso/export-situation',
                'order' => 2,
                'type' => 3,
                'parent_old' => 999932,
                'old' => 9998845,
                'active' => true,
            ]);
        }
    }

    public function down(): void
    {
        Menu::where('process', 9998845)
            ->delete();
    }
};
