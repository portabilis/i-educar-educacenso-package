<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use iEducar\Packages\Educacenso\Layout\Export\Contracts\SituationRepository;
use InvalidArgumentException;

class SituationRepositoryFactory
{
    public static function fromYear(int $year): SituationRepository
    {
        return match ($year) {
            2022 => new Layout2022\SituationRepository(),
            2023 => new Layout2023\SituationRepository(),
            2024 => new Layout2024\SituationRepository(),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }
}
