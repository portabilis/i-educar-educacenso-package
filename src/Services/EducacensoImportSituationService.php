<?php

namespace iEducar\Packages\Educacenso\Services;

use App\Models\LegacyRegistration;
use App\Models\NotificationType;
use App\Models\SchoolClassInep;
use App\Models\StudentInep;
use App\Services\NotificationService;
use Generator;
use iEducar\Packages\Educacenso\Enums\EducacensoImportStatus;
use iEducar\Packages\Educacenso\Models\EducacensoSituationImport;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EducacensoImportSituationService
{
    public function __construct(
        private EducacensoSituationImport $educacensoSituationImport,
        private array $data
    ) {
    }

    private int $schoolInep;

    public static function getDataBySchool(UploadedFile $file): Generator
    {
        $lines = self::readFile($file);
        $school = [];
        foreach ($lines as $key => $line) {
            if (Str::startsWith($line, '89|')) {
                if (count($school)) {
                    yield $school;
                }
                $school = [];
            }
            $school[] = $line;
        }
        if (count($school)) {
            yield $school;
        }
    }

    private static function readFile($file): Generator
    {
        $handle = fopen($file, 'r');
        while (($line = fgets($handle)) !== false) {
            yield $line;
        }
        fclose($handle);
    }

    public function execute(): void
    {
        foreach ($this->data as $line) {
            $lineArray = explode('|', $line);
            if ($lineArray[0] === '89') {
                $this->schoolInep = $lineArray[1] ?? null;
                continue;
            }

            $schoolClassInep = $lineArray[3] ?? null;
            $studentInep = $lineArray[4] ?? null;
            $situation = $lineArray[7] ?? null;

            if (empty($schoolClassInep) || empty($studentInep) || empty($situation)) {
                continue;
            }

            $this->updateSituation($schoolClassInep, $studentInep, $situation);
        }
        $this->updateImporter();
        $this->notifyUser();
    }

    private function updateSituation($schoolClassInep, $studentInep, $situation): void
    {
        $schoolClassId = SchoolClassInep::query()->where('cod_turma_inep', $schoolClassInep)->value('cod_turma');
        $studentId = StudentInep::query()->where('cod_aluno_inep', $studentInep)->value('cod_aluno');

        $registration = LegacyRegistration::query()
            ->with([
                'lastEnrollment',
                'lastEnrollment.schoolClass',
                'grade',
            ])
            ->join('matricula_turma', 'matricula_turma.ref_cod_matricula', '=', 'matricula.cod_matricula')
            ->where('ref_cod_aluno', $studentId)
            ->where('matricula_turma.ref_cod_turma', $schoolClassId)
            ->first();

        if (is_null($registration)) {
            return;
        }

        $situation = convertSituationEducacensoToIeducar(
            situation: $situation,
            etapaTurma: $registration->lastEnrollment->schoolClass->etapa_educacenso,
            etapaSerie: $registration->grade?->etapa_educacenso,
        );

        $data = [
            'aprovado' => $situation,
            'ativo' => 1,
            'data_cancel' => null,
        ];

        if (in_array($situation, [
            \App_Model_MatriculaSituacao::TRANSFERIDO,
            \App_Model_MatriculaSituacao::ABANDONO,
            \App_Model_MatriculaSituacao::FALECIDO,
        ], true)) {
            $data['data_cancel'] = $registration->data_matricula;

            $registration->lastEnrollment->update([
                'ativo' => 0,
                'transferido' => $situation === \App_Model_MatriculaSituacao::TRANSFERIDO,
                'abandono' => $situation === \App_Model_MatriculaSituacao::ABANDONO,
                'falecido' => $situation === \App_Model_MatriculaSituacao::FALECIDO,
                'data_exclusao' => $registration->data_matricula,
            ]);
        }

        $registration->update($data);
    }

    private function updateImporter(): void
    {
        $this->educacensoSituationImport->update([
            'status_id' => EducacensoImportStatus::SUCCESS,
        ]);
    }

    private function notifyUser(): void
    {
        (new NotificationService())->createByUser(
            userId: $this->educacensoSituationImport->user_id,
            text: $this->getMessage(),
            link: route('educacenso.import.inep.index'),
            type: NotificationType::OTHER
        );
    }

    private function getMessage(): string
    {
        return "Foram atualizadas as situações das matrículas vinculadas a escola com código INEP {$this->schoolInep}.";
    }

    public function failed(): void
    {
        $this->educacensoSituationImport->update([
            'status_id' => EducacensoImportStatus::ERROR,
        ]);
    }
}
