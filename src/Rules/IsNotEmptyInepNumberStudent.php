<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberStudent implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
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
            ->whereHas('registration', fn ($query) => $query->where('ano', $this->data['year']))
            ->whereHas('schoolClass', fn ($query) => $query->where('ref_ref_cod_escola', $this->data['school_id']))
            ->active()
            ->get();

        foreach ($enrollments as $enrollment) {
            if (is_null($enrollment->registration->student->inep)) {
                $fail('O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP válido.');
                continue;
            }

            if (empty($enrollment->registration->student->inep->cod_aluno_inep)) {
                $fail('O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP válido.');
                continue;
            }

            if (strlen($enrollment->registration->student->inep->cod_aluno_inep) != 12) {
                $fail('O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP válido.');
                continue;
            }

            if (! is_numeric($enrollment->registration->student->inep->cod_aluno_inep)) {
                $fail('O aluno ' . $enrollment->registration->student->person->nome . ' não possui um número INEP válido.');
            }
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
