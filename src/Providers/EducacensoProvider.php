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

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'educacenso');
    }

    public function boot(): void
    {
        Route::group(['middleware' => ['web', 'ieducar.navigation', 'ieducar.footer', 'ieducar.suspended', 'auth', 'ieducar.checkresetpassword']], function (): void {
            Route::get('educacenso/export-situation', [ExportSituationController::class, 'create'])
                ->name('educacenso-export-situation');
            Route::post('/educacenso/export-situation', [ExportSituationController::class, 'store']);
        });
    }
}
