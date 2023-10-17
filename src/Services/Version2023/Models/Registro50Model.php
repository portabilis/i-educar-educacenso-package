<?php

namespace iEducar\Packages\Educacenso\Services\Version2023\Models;

use App\Models\Educacenso\Registro50;
use iEducar\Modules\Educacenso\Model\UnidadesCurriculares;

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

        $count = count($arrayColumns) - 1; //desconsiderar a última posição do array, pois é o campo 42 string de outras unidades curriculares obrigatórias
        for ($index = 9; $index <= $count; $index++) {
            if (! empty($arrayColumns[$index])) {
                $this->componentes[] = $arrayColumns[$index];
            }
        }

        $this->unidadesCurriculares = array_filter([
            $arrayColumns[34] ? UnidadesCurriculares::ELETIVAS : null,
            $arrayColumns[35] ? UnidadesCurriculares::LIBRAS : null,
            $arrayColumns[36] ? UnidadesCurriculares::LINGUA_INDIGENA : null,
            $arrayColumns[37] ? UnidadesCurriculares::LINGUA_LITERATURA_ESTRANGEIRA_ESPANHOL : null,
            $arrayColumns[38] ? UnidadesCurriculares::LINGUA_LITERATURA_ESTRANGEIRA_FRANCES : null,
            $arrayColumns[39] ? UnidadesCurriculares::LINGUA_LITERATURA_ESTRANGEIRA_OUTRA : null,
            $arrayColumns[40] ? UnidadesCurriculares::PROJETO_DE_VIDA : null,
            $arrayColumns[41] ? UnidadesCurriculares::TRILHAS_DE_APROFUNDAMENTO_APRENDIZAGENS : null,
        ]);

        $this->outrasUnidadesCurricularesObrigatorias = $arrayColumns[42];
    }
}
