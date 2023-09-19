<?php

namespace iEducar\Packages\Educacenso\Services;

use App\Models\Educacenso\RegistroEducacenso;
use App\User;

interface RegistroImportInterface
{
    /**
     * Faz a importação dos dados a partir do model que representa o registro do educacenso
     *
     * @param int                $year
     * @return void
     */
    public function import(RegistroEducacenso $model, $year, User $user): void;

    /**
     * Returna um model representando os dados do registro
     *
     *
     * @return RegistroEducacenso
     */
    public static function getModel($arrayColumns);
}
