<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Contracts;

abstract class SituationRepository
{
    abstract public function getDataRecord89(int $year, int $schoolId): array;

    abstract public function getDataRecord90(int $year, int $schoolId): array;

    abstract public function getDataRecord91(int $year, int $schoolId): array;

    abstract public function getEnrollments90ToExport(int $year, int $schoolId): mixed;

    abstract public function getEnrollments91ToExport(int $year, int $schoolId): mixed;
}
