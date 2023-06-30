<?php

declare(strict_types=1);

if (! function_exists('clearInt')) {
    function clearInt(string $var): string|null
    {
        return preg_replace('/\D/', '', $var);
    }
}
