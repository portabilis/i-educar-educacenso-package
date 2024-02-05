<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $educacensoMenu = Menu::query()
            ->where('title', 'Educacenso')
            ->first();

        $menuImportacao = Menu::query()
            ->where('title', 'Importações')
            ->where('parent_id', $educacensoMenu->getKey())
            ->first();

        if($menuImportacao) {
            Menu::updateOrCreate([
                'process' => 9998849,
            ], [
                'parent_id' => $menuImportacao->getKey(),
                'title' => 'Importação educacenso',
                'description' => 'Importação educacenso',
                'link' => '/educacenso/import-registrations/create',
                'order' => 0,
                'type' => 3,
                'parent_old' => 9998848,
                'old' => 9998849,
                'active' => true,
            ]);

            Menu::updateOrCreate([
                'process' => 2004,
            ], [
                'parent_id' => $menuImportacao->getKey(),
                'title' => 'Histórico de importações',
                'link' => '/educacenso/import-registrations',
                'order' => 99,
                'type' => 1,
                'active' => true,
            ]);
        }
    }
};
