<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use InvalidArgumentException;

class SituationRecordFactory
{
    public static function record89FromYear(int $year): string
    {
        return match ($year) {
            2022 => Layout2022\Record89::class,
            2023 => Layout2023\Record89::class,
            2024 => Layout2024\Record89::class,
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record90FromYear(int $year): string
    {
        return match ($year) {
            2022 => Layout2022\Record90::class,
            2023 => Layout2023\Record90::class,
            2024 => Layout2024\Record90::class,
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }

    public static function record91FromYear(int $year): string
    {
        return match ($year) {
            2022 => Layout2022\Record91::class,
            2023 => Layout2023\Record91::class,
            2024 => Layout2024\Record91::class,
            default => throw new InvalidArgumentException("Year {$year} is not supported."),
        };
    }
}
