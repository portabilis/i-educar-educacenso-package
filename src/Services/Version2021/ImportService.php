<?php

namespace iEducar\Packages\Educacenso\Services\Version2021;

use iEducar\Packages\Educacenso\Services\Version2020\ImportService as ImportServiceVersion2020;

class ImportService extends ImportServiceVersion2020
{
    /**
     * Retorna o ano a que o service se refere
     *
     * @return int
     */
    public function getYear()
    {
        return 2021;
    }
}
