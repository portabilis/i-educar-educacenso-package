<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $menu = Menu::where('title', 'Exportações')->first();

        if($menu) {
            Menu::updateOrCreate([
                'process' => 9998845,
            ], [
                'parent_id' => $menu->getKey(),
                'title' => '2ª fase - Situação final',
                'description' => 'Exportação do educacenso - 2ª fase',
                'link' => '/educacenso/export-situation',
                'order' => 1,
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
