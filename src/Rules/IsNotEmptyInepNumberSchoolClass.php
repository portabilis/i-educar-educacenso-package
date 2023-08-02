<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberSchoolClass implements ValidationRule, DataAwareRule
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
                'registration:cod_matricula,ano',
                'schoolClass:cod_turma,ref_ref_cod_escola,nm_turma',
                'schoolClass.school:cod_escola',
                'schoolClass.inep:cod_turma,cod_turma_inep',
            ])
            ->whereHas('registration', fn ($query) => $query->where('ano', $this->data['year']))
            ->whereHas('schoolClass', fn ($query) => $query->where('ref_ref_cod_escola', $this->data['school_id']))
            ->active()
            ->get();

        foreach ($enrollments as $enrollment) {
            if (is_null($enrollment->registration->schoolClass->inep)) {
                $fail('A turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP.');
                continue;
            }

            if (is_null($enrollment->registration->schoolClass->inep->cod_turma_inep)) {
                $fail('A turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP.');
                continue;
            }

            if (strlen($enrollment->registration->schoolClass->inep->cod_turma_inep) != 10) {
                $fail('A turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP com 10 digitos.');
                continue;
            }

            if (! is_numeric($enrollment->registration->schoolClass->inep->cod_turma_inep)) {
                $fail('A Turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP válido.');
            }
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
