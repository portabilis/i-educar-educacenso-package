<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacySchoolClass;
use Closure;
use iEducar\Modules\Educacenso\Model\TipoAtendimentoTurma;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyInepNumberSchoolClass implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $year = $this->data['year'];
        $shool_id = $this->data['school_id'];

        $schoolClasses = LegacySchoolClass::query()
            ->active()
            ->whereSchool($shool_id)
            ->whereYearEq($year)
            ->where('nao_informar_educacenso', '!=', 1)
            ->whereRaw(TipoAtendimentoTurma::CURRICULAR_ETAPA_ENSINO . ' = ANY(tipo_atendimento)')
            ->get();

        foreach ($schoolClasses as $schoolClass) {
            $errorMessage = new ErrorMessage($fail, [
                'key' => 'cod_turma',
                'breadcrumb' => 'Escolas -> Cadastros -> Turmas -> Dados Adicionais -> Código INEP',
                'value' => $schoolClass->getKey(),
                'url' => 'intranet/educar_turma_cad.php?cod_turma=' . $schoolClass->getKey()
            ]);

            if (is_null($schoolClass->inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $schoolClass->nm_turma . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (is_null($schoolClass->inep->cod_turma_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $schoolClass->nm_turma . ' não possui um número INEP.',
                ]);
                continue;
            }

            if (strlen($schoolClass->inep->cod_turma_inep) > 10) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A turma ' . $schoolClass->nm_turma . ' possui um número INEP com mais de 10 digitos.',
                ]);
                continue;
            }

            if (! is_numeric($schoolClass->inep->cod_turma_inep)) {
                $errorMessage->toString([
                    'message' => 'Dados para formular o registro 90 inválidos. A Turma ' . $schoolClass->nm_turma . ' não possui um número INEP válido.',
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
