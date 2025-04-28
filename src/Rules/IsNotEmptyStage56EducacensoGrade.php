<?php

namespace iEducar\Packages\Educacenso\Rules;

use App\Models\LegacyGrade;
use App\Models\LegacyRegistration;
use Closure;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use iEducar\Packages\Educacenso\Layout\Export\Situation\SituationRepositoryFactory;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IsNotEmptyStage56EducacensoGrade implements DataAwareRule, ValidationRule
{
    protected $data = [];

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $year = $this->data['year'];
        $shool_id = $this->data['school_id'];

        $repository = SituationRepositoryFactory::fromYear((int) $this->data['year']);
        $enrollments90 = $repository->getEnrollments90ToExport($year, $shool_id);
        $enrollments91 = $repository->getEnrollments91ToExport($year, $shool_id);

        $enrollments = $enrollments90->merge($enrollments91);

        $schoolClassIds = $enrollments->where('schoolClass.etapa_educacenso', 56)->pluck('schoolClass.cod_turma')->toArray();
        $registrationsIds = $enrollments->whereIn('schoolClass.cod_turma', $schoolClassIds)->pluck('ref_cod_matricula')->toArray();

        $gradesIds = LegacyRegistration::query()
            ->select('ref_ref_cod_serie')
            ->whereIn('cod_matricula', $registrationsIds)
            ->distinct('ref_ref_cod_serie')
            ->get()
            ->pluck('ref_ref_cod_serie')
            ->toArray();

        $grades = LegacyGrade::query()
            ->select([
                'cod_serie',
                'nm_serie',
            ])
            ->whereIn('cod_serie', $gradesIds)
            ->whereNull('etapa_educacenso')
            ->get();

        foreach ($grades as $grade) {
            (new ErrorMessage($fail, [
                'key' => 'cod_serie',
                'breadcrumb' => 'Escolas -> Cadastros -> Séries -> Editar Série',
                'value' => $grade->getKey(),
                'url' => 'intranet/educar_serie_det.php?cod_serie=' . $grade->getKey()
            ]))->toString([
                'message' => 'Dados para formular os registros 90 e 91 inválidos, a série: ' . $grade->nm_serie . ' não possui a Etapa de ensino selecionada.',
            ]);
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
