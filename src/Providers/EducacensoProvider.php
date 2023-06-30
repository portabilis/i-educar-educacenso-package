<?php

namespace iEducar\Packages\Educacenso\Providers;

use iEducar\Packages\Educacenso\Http\Controllers\ExportSituationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EducacensoProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../../config/educacenso.php',
            key: 'educacenso'
        );

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(
                paths: __DIR__ . '/../../database/migrations'
            );
        }
    }

    public function boot(): void
    {
        Route::post('/educacenso/export-situation', ExportSituationController::class)
            ->name('educacenso-export-situation');
    }
}
