<?php

namespace iEducar\Packages\Educacenso\Rules;

use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberStudent implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void {
        $year = $this->data['year'];
        $shool_id = $this->data['school_id'];

        $classRepository = 'iEducar\Packages\Educacenso\Layout\Export\Situation\Layout' . $this->data['year'] . '\SituationRepository';
        $repository = new $classRepository();
        $enrollments = $repository->getEnrollments90ToExport($year, $shool_id);

        foreach ($enrollments as $enrollment) {
            $errorMessage = new ErrorMessage($fail, [
                'key' => 'cod_aluno',
                'value' => $enrollment->registration->student->getKey(),
                'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Código INEP',
                'url' => '/module/Cadastro/aluno?id=' . $enrollment->registration->student->getKey()
            ]);

            if (is_null($enrollment->registration->student->inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (empty($enrollment->registration->student->inep->cod_aluno_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP válido.',
                ]);
                continue;
            }

            if (strlen($enrollment->registration->student->inep->cod_aluno_inep) != 12) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP com 12 casas decimais.',
                ]);
                continue;
            }

            if (! is_numeric($enrollment->registration->student->inep->cod_aluno_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP numérico.',
                ]);
            }
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
