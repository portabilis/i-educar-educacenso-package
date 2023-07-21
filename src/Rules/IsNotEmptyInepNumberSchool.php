<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacySchool;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberSchool implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('Informe uma escola válida para continuar.');
        }

        $school = LegacySchool::findOrFail($value);

        if (is_null($school)) {
            $fail('A escola informada não existe.');
        }

        if (is_null($school->inep)) {
            $fail('A escola informada não possui um número INEP válido.');
        }

        if (empty($school->inep->number)) {
            $fail('A escola informada não possui um número INEP válido.');
        }

        if (strlen($school->inep->number) != 8) {
            $fail('A escola informada não possui um número INEP válido.');
        }

        if (! is_numeric($school->inep->number)) {
            $fail('A escola informada não possui um número INEP válido.');
        }
    }
}
