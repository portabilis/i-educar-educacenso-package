<?php

namespace iEducar\Packages\Educacenso\Services\Version2019;

use iEducar\Modules\Educacenso\Migrations\UpdateEducacensoInstitutionToLayout2020;
use iEducar\Modules\Educacenso\Migrations\UpdateSchoolClassToLayout2020;
use iEducar\Packages\Educacenso\Services\ImportService as GeneralImportService;
use iEducar\Packages\Educacenso\Services\RegistroImportInterface;

class ImportService extends GeneralImportService
{
    /**
     * Retorna o ano a que o service se refere
     *
     * @return int
     */
    public function getYear()
    {
        return 2019;
    }

    /**
     * Retorna o nome da escola a partir da string do arquivo de importação
     *
     *
     * @return string
     */
    public function getSchoolNameByFile($school)
    {
        $columns = explode(self::DELIMITER, $school[0]);

        return $columns[5];
    }

    /**
     * Retorna a classe responsável por importar o registro da linha
     *
     *
     * @return RegistroImportInterface
     */
    public function getRegistroById($lineId)
    {
        $arrayRegistros = [
            '00' => Registro00Import::class,
            '10' => Registro10Import::class,
            '20' => Registro20Import::class,
            '30' => Registro30Import::class,
            '40' => Registro40Import::class,
            '50' => Registro50Import::class,
            '60' => Registro60Import::class,
        ];

        if (! isset($arrayRegistros[$lineId])) {
            return;
        }

        return new $arrayRegistros[$lineId]();
    }

    public function adaptData(): void
    {
        UpdateEducacensoInstitutionToLayout2020::execute();
        UpdateSchoolClassToLayout2020::execute();
    }
}
