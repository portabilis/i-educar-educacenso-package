<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2023;

use App\Models\LegacyEnrollment;
use App\Models\LegacySchool;
use iEducar\Modules\Educacenso\Model\TipoAtendimentoTurma;
use function iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\config;

class SituationRepository extends \iEducar\Packages\Educacenso\Layout\Export\Contracts\SituationRepository
{
    public function getDataRecord89(
        int $year,
        int $schoolId
    ): array {
        $school = LegacySchool::query()
            ->select([
                'cod_escola',
                'ref_idpes_gestor'
            ])
            ->with([
                'inep',
                'schoolManagers',
            ])
            ->whereKey($schoolId)
            ->first();
        if ($school instanceof LegacySchool) {

            $schoolManager = $school->schoolManagers->sortBy('role_id')->first();

            return [
                '1' => 89,
                '2' => $school->inep->number,
                '3' => clearInt($schoolManager->individual->cpf),
                '4' => mb_strtoupper($schoolManager->individual->person->name),
                '5' => $schoolManager->role_id,
                '6' => $schoolManager->individual->person->email,
            ];
        }

        return [];
    }

    public function getDataRecord90(
        int $year,
        int $schoolId
    ): array {
        $dataBaseEducacenso = config('educacenso.data_base.' . $year);

        $enrollments = LegacyEnrollment::query()
            ->select([
                'ref_cod_matricula',
                'ref_cod_turma',
                'data_enturmacao'
            ])
            ->with([
                'registration:cod_matricula,ref_cod_aluno,ano',
                'registration.student:cod_aluno',
                'registration.student.inep:cod_aluno,cod_aluno_inep',
                'registration.situation:cod_matricula,cod_situacao',
                'schoolClass' => function ($q): void {
                    $q->select([
                        'cod_turma',
                        'ref_ref_cod_escola',
                        'tipo_atendimento',
                        'etapa_educacenso'
                    ]);
                    $q->where('tipo_atendimento', TipoAtendimentoTurma::ESCOLARIZACAO);
                },
                'schoolClass.school:cod_escola',
                'schoolClass.school.inep:cod_escola,cod_escola_inep',
                'schoolClass.inep:cod_turma,cod_turma_inep',
            ])
            ->where('data_enturmacao', '<=', $dataBaseEducacenso)
            ->whereHas('registration', fn ($query) => $query->where('ano', $year))
            ->whereHas('schoolClass', function ($q) use ($schoolId): void {
                $q->where('ref_ref_cod_escola', $schoolId);
                $q->where('tipo_atendimento', TipoAtendimentoTurma::ESCOLARIZACAO);
            })
            ->active()
            ->get();

        $enrollments = $enrollments->map(function ($enrollment) {
            return [
                '1' => 90,
                '2' => $enrollment->schoolClass->school->inep->number,
                '3' => $enrollment->schoolClass->getKey(),
                '4' => $enrollment->schoolClass->inep ? $enrollment->schoolClass->inep->number : null,
                '5' => $enrollment->registration->student->inep ? $enrollment->registration->student->inep->number : null,
                '6' => $enrollment->registration->student->getKey(),
                '7' => $enrollment->registration->getKey(),
                '8' => convertSituationIEducarToEducacenso($enrollment->registration->situation->cod_situacao, $enrollment->schoolClass->etapa_educacenso),
            ];
        });

        return $enrollments->toArray();
    }

    public function getDataRecord91(
        int $year,
        int $schoolId
    ): array {
        $dataBaseEducacenso = config('educacenso.data_base.' . $year);

        $enrollments = LegacyEnrollment::query()
            ->select([
                'ref_cod_matricula',
                'ref_cod_turma',
                'data_enturmacao'
            ])
            ->with([
                'registration:cod_matricula,ref_cod_aluno,ano',
                'registration.student:cod_aluno',
                'registration.student.inep:cod_aluno,cod_aluno_inep',
                'registration.situation:cod_matricula,cod_situacao',
                'schoolClass' => function ($q): void {
                    $q->select([
                        'cod_turma',
                        'ref_ref_cod_escola',
                        'tipo_atendimento',
                        'etapa_educacenso'
                    ]);
                    $q->where('tipo_atendimento', TipoAtendimentoTurma::ESCOLARIZACAO);
                },
                'schoolClass.school:cod_escola',
                'schoolClass.school.inep:cod_escola,cod_escola_inep',
                'schoolClass.inep:cod_turma,cod_turma_inep',
            ])
            ->where('data_enturmacao', '<=', $dataBaseEducacenso)
            ->whereHas('registration', fn ($query) => $query->where('ano', $year))
            ->whereHas('schoolClass', function ($q) use ($schoolId): void {
                $q->where('ref_ref_cod_escola', $schoolId);
                $q->where('tipo_atendimento', TipoAtendimentoTurma::ESCOLARIZACAO);
            })
            ->active()
            ->get();

        $enrollments = $enrollments->map(function ($enrollment) {
            return [
                '1' => 91,
                '2' => $enrollment->schoolClass->school->inep->number,
                '3' => $enrollment->schoolClass->getKey(),
                '4' => $enrollment->schoolClass->inep ? $enrollment->schoolClass->inep->number : null,
                '5' => $enrollment->registration->student->inep ? $enrollment->registration->student->inep->number : null,
                '6' => $enrollment->registration->student->getKey(),
                '7' => null,
                '8' => null,
                '9' => null,
                '10' => null,
                '11' => convertSituationIEducarToEducacenso($enrollment->registration->situation->cod_situacao, $enrollment->schoolClass->etapa_educacenso),
            ];
        });

        return $enrollments->toArray();
    }
}
