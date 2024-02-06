<?php

namespace iEducar\Packages\Educacenso\Services\Version2023;

use App\Models\Educacenso\Registro30;
use App\Models\Educacenso\RegistroEducacenso;
use App\Services\EmployeePosgraduateService;
use iEducar\Modules\ValueObjects\EmployeePosgraduateValueObject;
use iEducar\Packages\Educacenso\Services\Version2020\Registro30Import as Registro30Import2020;
use iEducar\Packages\Educacenso\Services\Version2023\Models\Registro30Model;

class Registro30Import extends Registro30Import2020
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        $this->user = $user;
        $this->model = $model;
        $this->year = $year;
        parent::import($model, $year, $user);
    }

    /**
     * @return Registro30|RegistroEducacenso
     */
    public static function getModel($arrayColumns)
    {
        $registro = new Registro30Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }

    protected function storePosgraduate($employee): void
    {
        if (empty($this->model->posGraduacoes)) {
            return;
        }

        /** @var EmployeePosgraduateService $employeePosgraduateService */
        $employeePosgraduateService = app(EmployeePosgraduateService::class);

        foreach ($this->model->posGraduacoes as $posgraducao) {
            $valueObject = new EmployeePosgraduateValueObject();
            $valueObject->employeeId = $employee->id;
            $valueObject->entityId = $this->institution->getKey();
            $valueObject->typeId = $posgraducao['tipo'] ?: null;
            $valueObject->areaId = $posgraducao['area'] ?: null;
            $valueObject->completionYear = $posgraducao['ano_conclusao'] ?: null;
            $employeePosgraduateService->storePosgraduate($valueObject);
        }
    }
}
