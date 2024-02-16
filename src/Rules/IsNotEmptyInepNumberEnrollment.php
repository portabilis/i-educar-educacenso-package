<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
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
        $dataBaseEducacenso = config('educacenso.data_base.' . $this->data['year']);
        $shool_id = $this->data['school_id'];

        $enrollments = LegacyEnrollment::query()
            ->select([
                'ref_cod_matricula',
                'ref_cod_turma',
                'id'
            ])
            ->with([
                'registration:cod_matricula,ref_cod_aluno,ano',
                'registration.student:cod_aluno,ref_idpes',
                'registration.student.person:idpes,nome',
                'registration.student.inep:cod_aluno,cod_aluno_inep',
                'schoolClass:cod_turma,ref_ref_cod_escola',
                'schoolClass.school:cod_escola',
                'inep:matricula_turma_id,matricula_inep',
            ])
            ->when($dataBaseEducacenso, function ($q) use ($dataBaseEducacenso): void {
                $q->where('data_enturmacao', '<=', $dataBaseEducacenso);
            })
            ->whereHas('registration', function ($q): void {
                $q->where('ano', $this->data['year']);
                $q->whereNull('data_cancel');
            })
            ->whereHas('schoolClass', function($q) use ($shool_id): void {
                $q->where('ref_ref_cod_escola', $shool_id);
            })
            ->active()
            ->get();

        foreach ($enrollments as $enrollment) {
            $errorMessage = new ErrorMessage($fail, [
                'key' => 'cod_aluno',
                'value' =>  $enrollment->registration->student->getKey(),
                'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula -> Histórico de Enturmações',
                'url' => '/intranet/educar_aluno_det.php?cod_aluno=' .  $enrollment->registration->student->getKey(),
            ]);

            if (is_null($enrollment->inep->matricula_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' na Turma ' . $enrollment->schoolClass->name . ' é obrigatório.',
                ]);

                continue;
            }

            if (strlen($enrollment->inep->matricula_inep) > 12) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($enrollment->registration->student->person->nome) . ' não pode possuir mais de 12 caracteres.',
                ]);
                continue;
            }

            if (! is_numeric($enrollment->inep->matricula_inep)) {
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
