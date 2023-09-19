<?php

namespace iEducar\Packages\Educacenso\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EducacensoImportRegistrationDate implements ValidationRule
{
    public function __construct(
        private int $selectedYear
    ) {
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $registrationYear = Carbon::createFromFormat('Y-m-d', $value)->year;

        if ($registrationYear > $this->selectedYear) {
            $fail('O ano da data de entrada das matrículas não pode ser maior que o ano selecionado');
        }
    }
}
