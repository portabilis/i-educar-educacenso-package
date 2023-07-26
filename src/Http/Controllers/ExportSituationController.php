<?php

namespace iEducar\Packages\Educacenso\Http\Controllers;

use App\Http\Controllers\Controller;
use iEducar\Packages\Educacenso\Http\Requests\ExportSituationRequest;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Record89;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Record90;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Record91;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\SituationRepository;
use Illuminate\Support\Facades\Validator;

class ExportSituationController extends Controller
{
    public function __invoke(ExportSituationRequest $request)
    {
        $repository = new SituationRepository();

        $array = [
            'escola' => $repository->getDataRecord89($request->get('year'), $request->get('school_id')),
            'matriculas' => $repository->getDataRecord90($request->get('year'), $request->get('school_id')),
            'turma_matriculas' => $repository->getDataRecord91($request->get('year'), $request->get('school_id'))
        ];

        $rulesRecord89 = new Record89();
        $rulesRecord90 = new Record90($array['matriculas']);
        $rulesRecord91 = new Record91($array['matriculas']);


        $rules = array_merge($rulesRecord89->rules(), $rulesRecord90->rules(), $rulesRecord91->rules());
        $messages = array_merge($rulesRecord89->messages(), $rulesRecord90->messages(), $rulesRecord91->messages());

        $validator = Validator::make($array, $rules, $messages);

        if ($validator->fails()) {
            return $validator->validate();
        }

        // exportação em fase de construção
    }
}
