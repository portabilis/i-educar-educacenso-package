<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use iEducar\Packages\Educacenso\Layout\Export\Situation\SituationRepositoryFactory;
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

        $repository = SituationRepositoryFactory::fromYear((int) $this->data['year']);
        $enrollments = $repository->getEnrollments90ToExport($year, $shool_id);
        foreach ($enrollments as $enrollment) {
            $this->validateEnrollment($enrollment, $fail);
        }

        $enrollments = $repository->getEnrollments91ToExport($year, $shool_id);
        foreach ($enrollments as $enrollment) {
            $this->validateEnrollment(
                enrollment: $enrollment,
                fail: $fail,
                record91: true
            );
        }
    }

    private function validateEnrollment(LegacyEnrollment $enrollment, Closure $fail, $record91 = false): void
    {
        if (is_null($enrollment->registration->student)) {
            (new ErrorMessage($fail, [
                'key' => 'cod_matricula',
                'value' => $enrollment->registration->getKey(),
                'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula',
                'url' => '/intranet/educar_matricula_det.php?cod_matricula=' . $enrollment->registration->getKey()
            ]))->toString([
                'message' => 'Dados para formular os registros inválidos. A matrícula ' . $enrollment->registration->getKey() . ' teve seu aluno removido indevidamente.',
            ]);

            return;
        }

        $errorMessage = new ErrorMessage($fail, [
            'key' => 'cod_aluno',
            'value' => $enrollment->registration->student->getKey(),
            'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Código INEP',
            'url' => '/module/Cadastro/aluno?id=' . $enrollment->registration->student->getKey()
        ]);

        if (is_null($enrollment->registration->student->inep)) {
            $errorMessage->toString([
                'message' => 'Dados para formular os registros inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP.',
            ]);

            if ($record91) {
                (new ErrorMessage($fail, [
                    'impediment' => false,
                    'value' => $enrollment->registration->student->getKey(),
                    'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula -> Histórico de Enturmações -> INEP da Matrícula',
                    'url' => route('enrollments.enrollment-inep.edit', $enrollment->getKey()),
                ]))->toString([
                    'message' => 'Caso o aluno não possua INEP na base do censo, acesse a tela de INEP da matricula e marque para não exportar o(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome),
                ]);
            }
            return;
        }

        if (empty($enrollment->registration->student->inep->cod_aluno_inep)) {
            $errorMessage->toString([
                'message' => 'Dados para formular os registros inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP válido.',
            ]);

            if ($record91) {
                (new ErrorMessage($fail, [
                    'impediment' => false,
                    'value' => $enrollment->registration->student->getKey(),
                    'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula -> Histórico de Enturmações -> INEP da Matrícula',
                    'url' => route('enrollments.enrollment-inep.edit', $enrollment->getKey()),
                ]))->toString([
                    'message' => 'Caso o aluno não possua INEP na base do censo, acesse a tela de INEP da matricula e marque para não exportar o(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome),
                ]);
            }
            return;
        }

        if (strlen($enrollment->registration->student->inep->cod_aluno_inep) != 12) {
            $errorMessage->toString([
                'message' => 'Dados para formular os registros inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP com 12 casas decimais.',
            ]);
            return;
        }

        if (! is_numeric($enrollment->registration->student->inep->cod_aluno_inep)) {
            $errorMessage->toString([
                'message' => 'Dados para formular os registros inválidos. O(a) aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não possui um número INEP numérico.',
            ]);
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
