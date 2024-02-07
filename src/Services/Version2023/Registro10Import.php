<?php

namespace iEducar\Packages\Educacenso\Services\Version2023;

use App\Models\Educacenso\RegistroEducacenso;
use iEducar\Modules\Educacenso\Model\SalasAtividades;
use iEducar\Packages\Educacenso\Services\Version2022\LegacySchool;
use iEducar\Packages\Educacenso\Services\Version2022\Registro10Import as Registro10Import2022;
use iEducar\Packages\Educacenso\Services\Version2023\Models\Registro10Model;

class Registro10Import extends Registro10Import2022
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        parent::import($model, $year, $user);

        $schoolInep = parent::getSchool();

        if (empty($schoolInep)) {
            return;
        }

        /** @var LegacySchool $school */
        $school = $schoolInep->school;
        $model = $this->model;

        $school->nao_ha_funcionarios_para_funcoes = (bool) $model->semFuncionariosParaFuncoes;
        $school->qtd_tradutor_interprete_libras_outro_ambiente = $model->qtdTradutorInterpreteLibrasOutroAmbiente ?: 0;

        $school->save();
    }

    public static function getModel($arrayColumns)
    {
        $registro = new Registro10Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }
    protected function getArraySalasAtividades()
    {
        $salasAtividades = parent::getArraySalasAtividades();
        $arraySalas = transformStringFromDBInArray($salasAtividades) ?: [];

        if ($this->model->dependenciaSalaEstudioGravacaoEdicao) {
            $arraySalas[] = SalasAtividades::ESTUDIO_GRAVACAO_EDICAO;
        }

        return parent::getPostgresIntegerArray($arraySalas);
    }
}
