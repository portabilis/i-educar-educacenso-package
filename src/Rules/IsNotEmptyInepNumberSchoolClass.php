<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyEnrollment;
use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberSchoolClass implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dataBaseEducacenso = config('educacenso.data_base.' . $this->data['year']);

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
            ->when($dataBaseEducacenso, function ($q) use ($dataBaseEducacenso): void {
                $q->where('data_enturmacao', '<=', $dataBaseEducacenso);
            })
            ->whereHas('registration', function ($q) {
                $q->where('ano', $this->data['year']);
                $q->whereNull('data_cancel');
            })
            ->whereHas('schoolClass', fn ($query) => $query->where('ref_ref_cod_escola', $this->data['school_id']))
            ->active()
            ->get();

        foreach ($enrollments as $enrollment) {
            $errorMessage = new ErrorMessage($fail, [
                'key' => 'cod_turma',
                'breadcrumb' => 'Escolas -> Cadastros -> Turmas -> Dados Adicionais -> Código INEP',
                'value' => $enrollment->registration->schoolClass->getKey(),
                'url' => 'intranet/educar_turma_cad.php?cod_turma=' . $enrollment->registration->schoolClass->getKey()
            ]);

            if (is_null($enrollment->registration->schoolClass->inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (is_null($enrollment->registration->schoolClass->inep->cod_turma_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (strlen($enrollment->registration->schoolClass->inep->cod_turma_inep) > 10) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $enrollment->registration->schoolClass->nm_turma . ' possui um número INEP com mais de 10 digitos.',
                ]);
                continue;
            }

            if (! is_numeric($enrollment->registration->schoolClass->inep->cod_turma_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A Turma ' . $enrollment->registration->schoolClass->nm_turma . ' não possui um número INEP válido.',
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
