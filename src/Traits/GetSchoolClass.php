<?php

namespace iEducar\Packages\Educacenso\Traits;

trait GetSchoolClass
{
    protected function getSchoolClass($schoolClassId): LegacySchoolClass
    {
        /*
         * Para turmas integrais com enturmações parciais e concateado o turno no código da turma
         * Mas para busca da turma, é necessário remover o turno
         */
        if (str_contains($schoolClassId, '-')) {
            $schoolClassId = explode('-', $schoolClassId)[0];
        }
        $schoolClass = LegacySchoolClass::find($schoolClassId);

        return $schoolClass;
    }
}
