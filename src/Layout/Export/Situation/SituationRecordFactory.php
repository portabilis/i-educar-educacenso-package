<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use iEducar\Packages\Educacenso\Layout\Export\Contracts\Validation;
use InvalidArgumentException;

class SituationRecordFactory
{
    public static function record89FromYear(int $year): Validation
    {
        return match ($year) {
            2022 => new Layout2022\Record89(),
            2023 => new Layout2023\Record89(),
            2024 => new Layout2024\Record89(),
            2025 => new Layout2025\Record89(),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record90FromYear(int $year, array $matriculas): Validation
    {
        return match ($year) {
            2022 => new Layout2022\Record90($matriculas),
            2023 => new Layout2023\Record90($matriculas),
            2024 => new Layout2024\Record90($matriculas),
            2025 => new Layout2025\Record90($matriculas),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record91FromYear(int $year, array $enturmacoes): Validation
    {
        return match ($year) {
            2022 => new Layout2022\Record91($enturmacoes),
            2023 => new Layout2023\Record91($enturmacoes),
            2024 => new Layout2024\Record91($enturmacoes),
            2025 => new Layout2025\Record91($enturmacoes),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }
}
