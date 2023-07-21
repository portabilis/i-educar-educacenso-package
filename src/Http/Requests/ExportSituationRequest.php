<?php

namespace iEducar\Packages\Educacenso\Http\Requests;

use App\Models\LegacySchool;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberSchool;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberSchoolClass;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberStudent;
use Illuminate\Foundation\Http\FormRequest;

class ExportSituationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'year' => [
                'required',
                'integer',
                'in:' . implode(',', array_values(config('educacenso.stage-2.years'))),
            ],
            'school_id' => [
                'required',
                'integer',
                'exists:' . LegacySchool::class . ',cod_escola',
                new IsNotEmptyInepNumberSchool(),
                new IsNotEmptyInepNumberSchoolClass(),
                new IsNotEmptyInepNumberStudent(),
            ]
        ];
    }

    public function messages()
    {
        return [
            'year.required' => 'O campo ano é obrigatório.',
            'year.integer' => 'O campo ano deve ser um número inteiro.',
            'year.in' => 'O campo ano deve ser um dos seguintes valores: ' . implode(', ', array_values(config('educacenso.stage-2.years'))) . '.',
            'school_id.required' => 'O campo escola é obrigatório.',
            'school_id.integer' => 'O campo escola deve ser um número inteiro.',
            'school_id.exists' => 'A escola informada não foi localizada.',
        ];
    }
}
