<?php

namespace iEducar\Packages\Educacenso\Providers;

use App\Process;
use iEducar\Packages\Educacenso\Http\Controllers\ExportSituationController;
use iEducar\Packages\Educacenso\Http\Controllers\ImportInepController;
use iEducar\Packages\Educacenso\Http\Controllers\ImportRegistrationController;
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

            Route::view('/impediments', 'educacenso::export.impediments')->name('export.impediments');

            Route::resource('educacenso/import-registrations', ImportRegistrationController::class)
                ->only(['index', 'create', 'store'])
                ->names('educacenso-import-registrations')
                ->middleware('can:view:' . Process::EDUCACENSO_IMPORT_HISTORY);

            Route::prefix('educacenso/importacao/inep')->middleware('can:modify:' . Process::EDUCACENSO_IMPORT_INEP)->group(function (): void {
                Route::get('create', [ImportInepController::class, 'create'])->name('educacenso.import.inep.create');
                Route::post('/', [ImportInepController::class, 'store'])->name('educacenso.import.inep.store');
                Route::get('/', [ImportInepController::class, 'index'])->name('educacenso.import.inep.index');
            });
        });
    }
}
