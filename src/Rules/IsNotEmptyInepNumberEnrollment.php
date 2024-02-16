<?php

namespace iEducar\Packages\Educacenso\Rules;

use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberEnrollment implements ValidationRule, DataAwareRule
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
                'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula -> Histórico de Enturmações',
                'url' => '/intranet/educar_matricula_historico_cad.php?ref_cod_matricula=' . $enrollment->registration->getKey() . '&ref_cod_turma=' . $enrollment->schoolClass->getKey() . '&sequencial=' . $enrollment->sequencial,
            ]);

            if (is_null($enrollment->inep?->matricula_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' na Turma ' . $enrollment->schoolClass->name . ' é obrigatório.',
                ]);

                continue;
            }

            if (strlen($enrollment->inep?->matricula_inep) > 12) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não pode possuir mais de 12 caracteres.',
                ]);
                continue;
            }

            if (! is_numeric($enrollment?->inep->matricula_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' deve conter apenas números.',
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
