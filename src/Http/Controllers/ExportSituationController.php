<?php

namespace iEducar\Packages\Educacenso\Http\Controllers;

use App\Http\Controllers\Controller;
use iEducar\Packages\Educacenso\Http\Requests\ExportSituationRequest;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Export;
use iEducar\Packages\Educacenso\Layout\Export\Situation\SituationRecordFactory;
use iEducar\Packages\Educacenso\Layout\Export\Situation\SituationRepositoryFactory;
use iEducar\Packages\Educacenso\Services\Csv;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExportSituationController extends Controller
{
    public function create()
    {
        $this->breadcrumb('Nova Exportação', [
            url('/intranet/educar_configuracoes_index.php') => 'Configurações',
            route('export.index') => 'Exportações',
        ]);

        $this->menu(9998845);

        $years = config('educacenso.stage-2.years');
        rsort($years);

        return view('educacenso::export.situation', compact('years'));
    }

    public function store(ExportSituationRequest $request)
    {
        $year = (int) $request->get('year');
        $repository = SituationRepositoryFactory::fromYear($year);

        $array = [
            'escola' => $repository->getDataRecord89($request->get('year'), $request->get('school_id')),
            'matriculas' => $repository->getDataRecord90($request->get('year'), $request->get('school_id')),
            'turma_matriculas' => $repository->getDataRecord91($request->get('year'), $request->get('school_id')),
        ];

        $rulesRecord89 = SituationRecordFactory::record89FromYear($year);
        $rulesRecord90 = SituationRecordFactory::record90FromYear($year, $array['matriculas']);
        $rulesRecord91 = SituationRecordFactory::record91FromYear($year, $array['turma_matriculas']);

        $rules = array_merge($rulesRecord89->rules(), $rulesRecord90->rules(), $rulesRecord91->rules());
        $messages = array_merge($rulesRecord89->messages(), $rulesRecord90->messages(), $rulesRecord91->messages());

        $validator = Validator::make($array, $rules, $messages);

        if ($validator->fails()) {
            return redirect('/impediments')
                ->withErrors($validator)
                ->withInput();
        }

        /**
         * Foi necessário reescrever a classe Csv permitindo assim a
         * exportação de linhas com número diferente de colunas.
         */
        IOFactory::registerWriter(
            writerType: \Maatwebsite\Excel\Excel::CSV,
            writerClass: Csv::class
        );

        $name = 'sit_' . $request->get('school_id') . '_' . $request->get('year') . '.txt';

        return Excel::download(
            export: new Export($array),
            fileName: $name,
            writerType: \Maatwebsite\Excel\Excel::CSV
        );
    }
}
