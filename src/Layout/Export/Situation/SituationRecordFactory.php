<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use InvalidArgumentException;

class SituationRecordFactory
{
    public static function record89FromYear(int $year): Layout2022\Record89|Layout2023\Record89|Layout2024\Record89
    {
        return match ($year) {
            2022 => new Layout2022\Record89(),
            2023 => new Layout2023\Record89(),
            2024 => new Layout2024\Record89(),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record90FromYear(int $year, array $matriculas): Layout2022\Record90|Layout2023\Record90|Layout2024\Record90
    {
        return match ($year) {
            2022 => new Layout2022\Record90($matriculas),
            2023 => new Layout2023\Record90($matriculas),
            2024 => new Layout2024\Record90($matriculas),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record91FromYear(int $year, array $enturmacoes): Layout2022\Record91|Layout2023\Record91|Layout2024\Record91
    {
        return match ($year) {
            2022 => new Layout2022\Record91($enturmacoes),
            2023 => new Layout2023\Record91($enturmacoes),
            2024 => new Layout2024\Record91($enturmacoes),
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }
}
