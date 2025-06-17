<?php

namespace iEducar\Packages\Educacenso\Services\Version2023;

use App\Models\Educacenso\Registro20;
use App\Models\Educacenso\RegistroEducacenso;
use App\Models\LegacySchoolClass;
use iEducar\Packages\Educacenso\Services\Version2022\Registro20Import as Registro20Import2022;
use iEducar\Packages\Educacenso\Services\Version2023\Models\Registro20Model;

class Registro20Import extends Registro20Import2022
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        $this->user = $user;
        $this->model = $model;
        $this->year = $year;

        parent::import($model, $year, $user);

        $model = $this->model;

        $schoolClassInep = parent::getSchoolClass();

        $schoolClass = LegacySchoolClass::find($schoolClassInep->cod_turma);

        $schoolClass->organizacao_curricular = transformDBArrayInString($model->estruturaCurricular) ?: null;
        $schoolClass->formas_organizacao_turma = $model->formasOrganizacaoTurma ?: null;
        $schoolClass->unidade_curricular = transformDBArrayInString($model->unidadesCurriculares) ?: null;
        $schoolClass->outras_unidades_curriculares_obrigatorias = $model->outrasUnidadesCurricularesObrigatorias ?: null;
        $schoolClass->classe_com_lingua_brasileira_sinais = $model->classeComLinguaBrasileiraSinais == 1 ? 1 : 2;

        $schoolClass->save();
    }

    /**
     * @return Registro20|RegistroEducacenso
     */
    public static function getModel($arrayColumns)
    {
        $registro = new Registro20Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }

    public static function getComponentes()
    {
        $componentes = parent::getComponentes();


        return $componentes;
    }
}
