<?php

namespace iEducar\Packages\Educacenso\Services\Version2023;

use App\Models\City;
use App\Models\District;
use App\Models\Educacenso\Registro00;
use App\Models\Educacenso\RegistroEducacenso;
use App\Models\LegacySchool;
use iEducar\Packages\Educacenso\Services\Version2019\Registro00Import as Registro00Import2019;
use iEducar\Packages\Educacenso\Services\Version2022\Models\Registro00Model;

class Registro00Import extends Registro00Import2019
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        $this->model = $model;
        $this->year = $year;
        $this->user = $user;
        parent::import($model, $year, $user);
    }

    protected function getOrCreateSchool(): void
    {
        parent::getOrCreateSchool();

        $schoolInep = parent::getSchool();

        if (empty($schoolInep)) {
            return;
        }

        /** @var LegacySchool $school */
        $school = $schoolInep->school;
        $model = $this->model;

        if (! $school->iddis) {
            $ibge_code = explode($model->codigoIbgeMunicipio, $model->codigoIbgeDistrito)[1];
            $city = City::where('ibge_code', $model->codigoIbgeMunicipio)->first();

            $district = District::where('city_id', $city->id)
                ->where('ibge_code', $ibge_code)
                ->first();

            $school->iddis = $district->getKey();
        }

        $school->save();
    }

    /**
     * @return Registro00|RegistroEducacenso
     */
    public static function getModel($arrayColumns)
    {
        $registro = new Registro00Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }
}
