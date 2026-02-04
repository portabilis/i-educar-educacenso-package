<?php

namespace iEducar\Packages\Educacenso\Services\Version2025;

use App\Models\Educacenso\Registro50;
use App\Models\Educacenso\RegistroEducacenso;
use App\Models\LegacySchoolClassTeacher;
use iEducar\Packages\Educacenso\Services\Version2023\Registro50Import as Registro50Import2023;
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
        if (is_array($model->areaItinerario) && count($model->areaItinerario) > 0) {
            $schoolClassTeacher->area_itinerario = $this->getPostgresIntegerArray($model->areaItinerario);
        }
        $schoolClassTeacher->leciona_itinerario_tecnico_profissional = $model->lecionaItinerarioTecnicoProfissional ?: null;

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
