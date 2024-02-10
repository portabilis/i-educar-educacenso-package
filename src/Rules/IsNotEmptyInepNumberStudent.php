<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
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
        $dataBaseEducacenso = config('educacenso.data_base.' . $this->data['year']);

        $enrollments = LegacyEnrollment::query()
            ->select([
                'ref_cod_matricula',
                'ref_cod_turma',
            ])
            ->with([
                'registration:cod_matricula,ref_cod_aluno,ano',
                'registration.student:cod_aluno,ref_idpes',
                'registration.student.person:idpes,nome',
                'registration.student.inep:cod_aluno,cod_aluno_inep',
                'schoolClass:cod_turma,ref_ref_cod_escola',
                'schoolClass.school:cod_escola',
            ])
            ->when($dataBaseEducacenso, function ($q) use ($dataBaseEducacenso): void {
                $q->where('data_enturmacao', '<=', $dataBaseEducacenso);
            })
            ->whereHas('registration', fn (
                $query
            ) => $query->where('ano', $this->data['year']))
            ->whereHas('schoolClass', fn (
                $query
            ) => $query->where('ref_ref_cod_escola', $this->data['school_id']))
            ->active()
            ->get();

        foreach ($enrollments as $enrollment) {
            $errorMessage = new ErrorMessage($fail, [
                'key' => 'cod_aluno',
                'value' => $enrollment->registration->student->getKey(),
                'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Código INEP',
                'url' => '/module/Cadastro/aluno?id=' . $enrollment->registration->student->getKey()
            ]);

            if (is_null($enrollment->registration->student->inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (empty($enrollment->registration->student->inep->cod_aluno_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP válido.',
                ]);
                continue;
            }

            if (strlen($enrollment->registration->student->inep->cod_aluno_inep) != 12) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP com 12 casas decimais.',
                ]);
                continue;
            }

            if (! is_numeric($enrollment->registration->student->inep->cod_aluno_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP numérico.',
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
