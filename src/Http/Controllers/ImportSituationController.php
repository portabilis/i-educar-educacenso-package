<?php

namespace iEducar\Packages\Educacenso\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolInep;
use App\Process;
use Carbon\Carbon;
use Exception;
use iEducar\Packages\Educacenso\Exception\ImportSituationException;
use iEducar\Packages\Educacenso\Http\Requests\EducacensoImportSituationRequest;
use iEducar\Packages\Educacenso\Jobs\EducacensoSituationImportJob;
use iEducar\Packages\Educacenso\Models\EducacensoSituationImport;
use iEducar\Packages\Educacenso\Services\EducacensoImportSituationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ImportSituationController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return redirect('intranet/educar_educacenso_index.php')
                ->with('error', 'Você não tem permissão para acessar essa página.');
        }

        $this->breadcrumb('Importação de Situações', [
            url('intranet/educar_educacenso_index.php') => 'Educacenso',
        ]);
        $this->menu(Process::EDUCACENSO_IMPORT_SITUATION);
        $imports = EducacensoSituationImport::query()
            ->orderByDesc('created_at')
            ->paginate();

        return view('educacenso::import-situation.index', [
            'imports' => $imports,
        ]);
    }

    public function store(EducacensoImportSituationRequest $request)
    {
        if (! $request->user()->isAdmin()) {
            return redirect('intranet/educar_educacenso_index.php')
                ->with('error', 'Você não tem permissão para acessar essa página.');
        }

        $files = $request->file('arquivos');
        $jobs = [];
        $schoolCount = 0;
        try {
            DB::beginTransaction();
            foreach ($files as $file) {
                $schoolsData = EducacensoImportSituationService::getDataBySchool($file);
                foreach ($schoolsData as $schoolData) {
                    $schoolLine = explode('|', $schoolData[0]);
                    $year = $request->integer('ano');
                    $schoolInep = $schoolLine[1];
                    $this->validateSchoolInep($schoolInep);
                    $schoolName = mb_strtoupper($this->getNameSchoolInep($schoolInep));

                    $educacensoSituationImport = EducacensoSituationImport::create([
                        'year' => $year,
                        'user_id' => $request->user()->getKey(),
                        'school_name' => $schoolName,
                    ]);

                    array_walk_recursive($schoolData, static fn (&$item) => $item = mb_convert_encoding($item, 'HTML-ENTITIES', 'UTF-8'));
                    $schoolCount++;
                    $jobs[] = [
                        $educacensoSituationImport,
                        $schoolData,
                    ];
                }
            }
            DB::commit();
            foreach ($jobs as $job) {
                EducacensoSituationImportJob::dispatch(...$job);
            }
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect(route('educacenso.import.situation.create'))
                ->with('error', $exception instanceof ImportSituationException ? $exception->getMessage() : 'Não foi possível realizar a importação!');
        }

        return redirect()->route('educacenso.import.situation.index')->with('success', "Iniciado o processamento das Situações de {$schoolCount} escolas.");
    }

    private function validateSchoolInep(int $inep): void
    {
        $doesntExist = Cache::remember('educacenso_' . $inep . '_doesnt_exist ', Carbon::now()->addHours(12), function () use ($inep) {
            return SchoolInep::query()->where('cod_escola_inep', $inep)->doesntExist();
        });

        if ($doesntExist) {
            throw new ImportSituationException("Não foi possível encontrar a escola com o INEP {$inep}");
        }
    }

    private function getNameSchoolInep(int $inep): string
    {
        $return = Cache::remember('educacenso_' . $inep . '_name', Carbon::now()->addHours(12), function () use ($inep) {
            $schoolInep = SchoolInep::query()->where('cod_escola_inep', $inep)->first();

            return $schoolInep ? $schoolInep->school->name : '';
        });

        return $return;
    }

    public function create(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return redirect('intranet/educar_educacenso_index.php')
                ->with('error', 'Você não tem permissão para acessar essa página.');
        }

        $this->menu(Process::EDUCACENSO_IMPORT_SITUATION);
        $this->breadcrumb('Importação de Situações', [
            url('intranet/educar_educacenso_index.php') => 'Educacenso',
        ]);

        $years = config('educacenso.stage-2.years');
        rsort($years);

        return view('educacenso::import-situation.create', compact('years'));
    }
}
