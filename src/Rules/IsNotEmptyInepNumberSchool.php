<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacySchool;
use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberSchool implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $errorMessage = new ErrorMessage($fail, [
            'key' => 'school_id',
            'value' => $value,
            'breadcrumb' => 'Escolas -> Cadastros -> Escolas',
            'url' => 'intranet/educar_escola_det.php?cod_escola=' . $value,
        ]);

        if (empty($value)) {
            $errorMessage->toString([
                'message' => 'O campo escola é obrigatório.',
            ]);
        }

        $school = LegacySchool::findOrFail($value);

        if (is_null($school)) {
            $errorMessage->toString([
                'message' => 'A escola informada não existe.',
            ]);
        }

        if (is_null($school->inep)) {
            $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. A escola informada não possui um número INEP válido.',
                'breadcrumb' => 'Escolas -> Cadastros -> Escolas -> Código INEP',
                'field' => 'inep',
            ]);
        }

        if (empty($school->inep?->number)) {
            $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. A escola informada não possui um número INEP válido.',
                'breadcrumb' => 'Escolas -> Cadastros -> Escolas -> Código INEP',
                'field' => 'inep',
            ]);
        }

        if (strlen($school->inep?->number) != 8) {
            $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. A escola informada não possui um número INEP com 8 digitos.',
                'breadcrumb' => 'Escolas -> Cadastros -> Escolas -> Código INEP',
                'field' => 'inep',
            ]);
        }

        if (! is_numeric($school->inep?->number)) {
            $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. A escola informada não possui um número INEP válido.',
                'breadcrumb' => 'Escolas -> Cadastros -> Escolas -> Código INEP',
                'field' => 'inep',
            ]);
        }
    }
}
