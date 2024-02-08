<?php

namespace iEducar\Packages\Educacenso\Http\Requests;

use iEducar\Packages\Educacenso\Rules\EducacensoImportRegistrationDate;
use Illuminate\Foundation\Http\FormRequest;

class ImportRegistrationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'year' => $this->input('ano'),
        ]);
    }

    public function rules()
    {
        return [
            'year' => [
                'required',
                'integer',
                'in:' . implode(',', array_values(config('educacenso.stage-1.years'))),
            ],
            'date' => [
                'required',
                'date_format:Y-m-d',
                new EducacensoImportRegistrationDate($this->year),
            ],
            'file' => [
                'required',
                'file',
                'mimes:txt',
            ]
        ];
    }

    public function messages()
    {
        return [
            'year.required' => 'O campo ano é obrigatório.',
            'year.integer' => 'O campo ano deve ser um número inteiro.',
            'year.in' => 'O ano informado não possui um layout disponível para importação.',
            'date.required' => 'O campo data de entrada das matrículas é obrigatório.',
            'date.date_format' => 'O campo data de entrada das matrículas não possui um valor válido.',
            'file.required' => 'O campo arquivo é obrigatório.',
            'file.file' => 'O campo arquivo deve ser um arquivo.',
            'file.mimes' => 'O campo arquivo deve ser um arquivo do tipo: txt.',
        ];
    }
}
