<?php

namespace iEducar\Packages\Educacenso\Tests\Import;

use App\Models\LegacySchool;
use Carbon\Carbon;
use iEducar\Packages\Educacenso\Services\HandleFileService;
use iEducar\Packages\Educacenso\Services\ImportServiceFactory;
use iEducar\Packages\Educacenso\Tests\EducacensoTestCase;
use Illuminate\Http\UploadedFile;

class Import2023Test extends EducacensoTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        \Artisan::call('db:seed', ['--class' => 'DefaultCadastroDeficienciaTableSeeder']);

        $this->year = 2023;
        $this->dateEnrollment = new Carbon('2023-01-01');

        $yearImportService = ImportServiceFactory::createImportService(
            $this->year,
            $this->dateEnrollment->format('d/m/Y')
        );

        $importFileService = new HandleFileService($yearImportService, $this->user);

        $importFileService->handleFile(new UploadedFile(
            path: __DIR__ . '/importacao_educacenso_2023.txt',
            originalName: 'importacao_educacenso_2023.txt'
        ));
    }

    /** @test */
    public function import2023Specific(): void
    {
        $legacySchool = LegacySchool::first();
        $this->assertEquals('{6}', $legacySchool->formas_contratacao_parceria_escola_secretaria_municipal);

        [$schoolClasses01, $schoolClasses02] = $legacySchool->schoolClasses;

        $this->assertEquals(1, $schoolClasses01->classe_com_lingua_brasileira_sinais);
        $this->assertEquals(2, $schoolClasses02->classe_com_lingua_brasileira_sinais);
    }
}
