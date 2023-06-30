<?php

namespace iEducar\Packages\Educacenso\Http\Controllers;

use App\Http\Controllers\Controller;
use iEducar\Packages\Educacenso\Http\Requests\ExportSituationRequest;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Record89;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Record90;
use iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\SituationRepository;
use Illuminate\Support\Facades\Validator;

class ExportSituationController extends Controller
{
    public function __invoke(ExportSituationRequest $request)
    {
        $teste = new SituationRepository();

        $array = [
            'escola' => $teste->getDataRecord89($request->get('year'), $request->get('school_id')),
            'turmas' => $teste->getDataRecord90($request->get('year'), $request->get('school_id'))
        ];

        $rulesRecord89 = new Record89();
        $rulesRecord90 = new Record90($array['turmas']);

        $rules = array_merge($rulesRecord89->rules(), $rulesRecord90->rules());
        $messages = array_merge($rulesRecord89->messages(), $rulesRecord90->messages());

        $validator = Validator::make($array, $rules, $messages);

        if ($validator->fails()) {
            return $validator->validate();
        }


        var_export("deu certo");
        die();

        /*$record89 =

        $rules = new Record89();
        $validator = Validator::make($record89, $rules->rules(), $rules->messages());


        if ($validator->fails()) {
            return $validator->validate();
        }
        $record90 = $teste->getDataRecord90($request->get('year'), $request->get('school_id'));

        //var_export($record90);
        //die();

        $rules = new Record90();
        $validator = Validator::make($record90, $rules->rules(), $rules->messages());*/

        if ($validator->fails()) {
            return $validator->validate();
        }
        var_export('algo esta errado');


        die();

    }
}
