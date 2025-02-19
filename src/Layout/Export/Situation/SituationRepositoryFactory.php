<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use InvalidArgumentException;

class SituationRepositoryFactory
{
    public static function fromYear(int $year): string
    {
        return match ($year) {
            2022 => Layout2022\SituationRepository::class,
            2023 => Layout2023\SituationRepository::class,
            2024 => Layout2024\SituationRepository::class,
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }
}
