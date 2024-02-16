<?php

namespace iEducar\Packages\Educacenso\Jobs;

use iEducar\Packages\Educacenso\Models\EducacensoInepImport;
use iEducar\Packages\Educacenso\Services\EducacensoImportInepService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class EducacensoInepImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 3600;

    public function __construct(private EducacensoInepImport $educacensoInepImport, private array $data)
    {
    }

    public function handle(): void
    {
        $this->setConnection();
        (new EducacensoImportInepService($this->educacensoInepImport, $this->data))->execute();
    }

    private function setConnection(): void
    {
        DB::setDefaultConnection($this->educacensoInepImport->getConnectionName());
    }

    public function failed(Throwable $exception): void
    {
        $this->setConnection();
        (new EducacensoImportInepService($this->educacensoInepImport, $this->data))->failed();
    }

    public function tags()
    {
        return [
            $this->educacensoInepImport->getConnectionName(),
            'educacenso-inep-import',
        ];
    }
}
