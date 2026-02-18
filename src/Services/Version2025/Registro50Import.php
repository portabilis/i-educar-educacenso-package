<?php

namespace iEducar\Packages\Educacenso\Services\Version2025;

use App\Models\Educacenso\Registro50;
use App\Models\Educacenso\RegistroEducacenso;
use App\Models\LegacySchoolClassTeacher;
use iEducar\Packages\Educacenso\Services\Version2023\Registro50Import as Registro50Import2023;

use function iEducar\Packages\Educacenso\Services\Version2023\transformDBArrayInString;

use iEducar\Packages\Educacenso\Services\Version2025\Models\Registro50Model;

class Registro50Import extends Registro50Import2023
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        $this->user = $user;
        $this->model = $model;
        $this->year = $year;

        parent::import($model, $year, $user);

        $employee = parent::getEmployee();
        $schoolClass = $this->getSchoolClass();
        $schoolClassTeacher = LegacySchoolClassTeacher::where('turma_id', $schoolClass->getKey())
            ->where('servidor_id', $employee->getKey())
            ->first();
        $schoolClassTeacher->area_itinerario = transformDBArrayInString($model->areaItinerario) ?: null;
        $schoolClassTeacher->leciona_itinerario_tecnico_profissional = $model->lecionaItinerarioTecnicoProfissional;

        $schoolClassTeacher->save();
    }

    /**
     * @return Registro50|RegistroEducacenso
     */
    public static function getModel($arrayColumns)
    {
        $registro = new Registro50Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }
}
