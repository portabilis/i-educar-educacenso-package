<?php

namespace iEducar\Packages\Educacenso\Services\Version2023;

use iEducar\Packages\Educacenso\Services\Version2020\Registro40Import;
use iEducar\Packages\Educacenso\Services\Version2022\ImportService as ImportServiceVersion2022;

class ImportService extends ImportServiceVersion2022
{
    /**
     * Retorna o ano a que o service se refere
     *
     * @return int
     */
    public function getYear()
    {
        return 2023;
    }

    /**
     * Retorna a classe responsável por importar o registro da linha
     *
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

    public function getSchoolNameByFile($school)
    {
        $columns = explode(self::DELIMITER, $school[0]);

        return $columns[5];
    }
}
