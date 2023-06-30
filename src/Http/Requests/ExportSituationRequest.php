<?php

namespace iEducar\Packages\Educacenso\Http\Requests;

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
        ];
    }

    public function messages()
    {
        return [
            'year.required' => 'O campo ano é obrigatório.',
            'year.integer' => 'O campo ano deve ser um número inteiro.',
            'year.in' => 'O campo ano deve ser um dos seguintes valores: ' . implode(', ', array_values(config('educacenso.stage-2.years'))) . '.',
        ];
    }
}
