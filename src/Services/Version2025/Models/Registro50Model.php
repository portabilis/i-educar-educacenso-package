<?php

namespace iEducar\Packages\Educacenso\Services\Version2025\Models;

use App\Models\Educacenso\Registro50;
use iEducar\Modules\Educacenso\Model\TipoItinerarioFormativo;

class Registro50Model extends Registro50
{
    public function hydrateModel($arrayColumns): void
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        $this->registro = $arrayColumns[1];
        $this->inepEscola = $arrayColumns[2];
        $this->codigoPessoa = $arrayColumns[3];
        $this->inepDocente = $arrayColumns[4];
        $this->codigoTurma = $arrayColumns[5];
        $this->inepTurma = $arrayColumns[6];
        $this->funcaoDocente = $arrayColumns[7] ?: null;
        $this->tipoVinculo = $arrayColumns[8] ?: null;
        $this->componentes = [];

        // COM A MUDANÇA DE LAYOUT, OS COMPONENTES VÃO DE 9 A 33
        for ($index = 9; $index <= 33; $index++) {
            if (! empty($arrayColumns[$index])) {
                $this->componentes[] = $arrayColumns[$index];
            }
        }

        $this->areaItinerario = array_filter([
            $arrayColumns[34] ? TipoItinerarioFormativo::LINGUANGENS : null,
            $arrayColumns[35] ? TipoItinerarioFormativo::MATEMATICA : null,
            $arrayColumns[36] ? TipoItinerarioFormativo::CIENCIAS_NATUREZA : null,
            $arrayColumns[37] ? TipoItinerarioFormativo::CIENCIAS_HUMANAS : null,
        ]);

        $this->lecionaItinerarioTecnicoProfissional = $arrayColumns[38];
    }
}
