<?php

namespace iEducar\Packages\Educacenso\Services\Version2025;

use App\Models\Educacenso\Registro60;
use App\Models\Educacenso\RegistroEducacenso;
use iEducar\Packages\Educacenso\Services\Version2013\Registro60Import as Registro60Import2023;
use iEducar\Packages\Educacenso\Services\Version2025\Models\Registro60Model;

class Registro60Import extends Registro60Import2023
{
    /**
     * Faz a importação dos dados a partir da linha do arquivo
     *
     * @param int                $year
     * @return void
     */
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        $this->year = $year;
        $this->user = $user;
        $this->model = $model;

        parent::import($model, $year, $user);
    }

    /**
     * @return Registro60|RegistroEducacenso
     */
    public static function getModel($arrayColumns)
    {
        $registro = new Registro60Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }
}
