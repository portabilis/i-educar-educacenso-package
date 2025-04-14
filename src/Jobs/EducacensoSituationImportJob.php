<?php

namespace iEducar\Packages\Educacenso\Jobs;

use iEducar\Packages\Educacenso\Models\EducacensoSituationImport;
use iEducar\Packages\Educacenso\Services\EducacensoImportSituationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class EducacensoSituationImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 1800;

    public function __construct(private EducacensoSituationImport $educacensoSituationImport, private array $data)
    {
    }

    public function handle(): void
    {
        $this->setConnection();
        (new EducacensoImportSituationService($this->educacensoSituationImport, $this->data))->execute();
    }

    private function setConnection(): void
    {
        DB::setDefaultConnection($this->educacensoSituationImport->getConnectionName());
    }

    public function failed(Throwable $exception): void
    {
        $this->setConnection();
        (new EducacensoImportSituationService($this->educacensoSituationImport, $this->data))->failed();
    }

    public function tags()
    {
        return [
            $this->educacensoSituationImport->getConnectionName(),
            'educacenso-situation-import',
        ];
    }
}
