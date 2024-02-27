<?php

namespace iEducar\Packages\Educacenso\Services;

use App\Models\Employee;
use App\Models\EmployeeInep;
use App\Models\EnrollmentInep;
use App\Models\LegacyEnrollment;
use App\Models\LegacySchoolClass;
use App\Models\LegacyStudent;
use App\Models\NotificationType;
use App\Models\SchoolClassInep;
use App\Models\StudentInep;
use App\Services\NotificationService;
use Generator;
use iEducar\Packages\Educacenso\Enums\EducacensoImportStatus;
use iEducar\Packages\Educacenso\Models\EducacensoInepImport;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EducacensoImportInepService
{
    private string $schoolName;

    public function __construct(private EducacensoInepImport $educacensoInepImport, private array $data)
    {
    }

    public static function getDataBySchool(UploadedFile $file): Generator
    {
        $lines = self::readFile($file);
        $school = [];
        foreach ($lines as $key => $line) {
            if (Str::startsWith($line, '00|')) {
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
    }

    public function execute(): void
    {
        $schoolData = explode('|', $this->data[0]);
        $this->schoolName = $schoolData[5];
        foreach ($this->data as $line) {
            $lineArray = explode('|', $line);
            $register = $lineArray[0];
            $id = $lineArray[2] ?? null;
            $inep = $lineArray[3] ?? null;
            $inepSchoolClass = $lineArray[5] ?? null;
            $matricula = $lineArray[6] ?? null;
            if (! empty($id) && ! empty($inep)) {
                match ($register) {
                    '20' => $this->updateSchoolClass($id, $inep),
                    '40', '50' => $this->updateEmployee($id, $inep),
                    '60' => $this->updateStudent($id, $inep, $inepSchoolClass, $matricula),
                    default => null
                };
            }
        }
        $this->updateImporter();
        $this->notifyUser();
    }

    private function updateSchoolClass($id, $inep): void
    {
        $doesntExist = LegacySchoolClass::query()->whereKey($id)->doesntExist();
        if ($doesntExist) {
            return;
        }
        SchoolClassInep::query()->updateOrCreate(['cod_turma' => $id], ['cod_turma_inep' => $inep]);
    }

    private function updateEmployee($id, $inep): void
    {
        $doesntExist = Employee::query()->whereKey($id)->doesntExist();
        if ($doesntExist) {
            return;
        }
        EmployeeInep::query()->updateOrCreate(['cod_servidor' => $id], ['cod_docente_inep' => $inep]);
    }

    private function updateStudent($id, $inep, $inepSchoolClass, $matricula): void
    {
        $students = LegacyStudent::query()->where('ref_idpes', $id)->get(['cod_aluno']);
        if ($students->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            StudentInep::query()
                ->updateOrCreate([
                    'cod_aluno' => $student->getKey()
                ], [
                    'cod_aluno_inep' => $inep
                ]);
        }

        $schoolClassInep = SchoolClassInep::query()
            ->where('cod_turma_inep', $inepSchoolClass)
            ->first();

        if ($schoolClassInep) {
            $enrollment = LegacyEnrollment::query()
                ->where('ref_cod_turma', $schoolClassInep->cod_turma)
                ->whereHas('registration', function ($q) use ($student): void {
                    $q->where('ref_cod_aluno', $student->getKey());
                })
                ->orderByDesc('data_enturmacao')
                ->get(['id'])
                ->first();

            if ($enrollment) {
                EnrollmentInep::query()
                    ->updateOrCreate([
                        'matricula_turma_id' => $enrollment->getKey(),
                        'matricula_inep' => $matricula
                    ], [
                        'matricula_turma_id' => $enrollment->getKey(),
                        'matricula_inep' => $matricula
                    ]);
            }
        }
    }

    private function updateImporter(): void
    {
        $this->educacensoInepImport->update([
            'status_id' => EducacensoImportStatus::SUCCESS,
        ]);
    }

    private function notifyUser(): void
    {
        (new NotificationService())->createByUser(
            userId: $this->educacensoInepImport->user_id,
            text: $this->getMessage(),
            link: route('educacenso.import.inep.index'),
            type: NotificationType::OTHER
        );
    }

    private function getMessage(): string
    {
        return "Foram importados os INEPs da escola {$this->schoolName}. Clique aqui para visualizar.";
    }

    public function failed(): void
    {
        $this->educacensoInepImport->update([
            'status_id' => EducacensoImportStatus::ERROR,
        ]);
    }
}
