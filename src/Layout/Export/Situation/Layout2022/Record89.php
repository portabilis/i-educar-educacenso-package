<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

use iEducar\Packages\Educacenso\Layout\Export\Contracts\Validation;
use iEducar\Packages\Educacenso\Rules\CPF;

class Record89 extends Validation
{
    public function rules(): array
    {
        return [
            'escola.1' => [
                'required',
                'integer',
                'in:89',
            ],
            'escola.2' => [
                'required',
                'digits:8',
            ],
            'escola.3' => [
                'required',
                'digits:11',
                new CPF(),
            ],
            'escola.4' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z ]+$/'
            ],
            'escola.5' => [
                'required',
                'integer',
                'in:1',
            ],
            'escola.6' => [
                'required',
                'email',
            ],
        ];
    }

    public function messages()
    {
        return [
            '1.required' => 'O campo "Código da escola" é obrigatório.',
            '1.digits' => 'O campo "Código da escola" deve conter 8 dígitos.',
            '2.required' => 'O campo "CPF do diretor" é obrigatório.',
            '2.digits' => 'O campo "CPF do diretor" deve conter 11 dígitos.',
            '2.cpf' => 'O campo "CPF do diretor" deve ser um CPF válido.',
            '3.required' => 'O campo "Nome do diretor" é obrigatório.',
            '3.string' => 'O campo "Nome do diretor" deve ser uma string.',
            '3.max' => 'O campo "Nome do diretor" deve conter no máximo 100 caracteres.',
            '3.regex' => 'O campo "Nome do diretor" deve conter apenas letras e espaços.',
            '5.required' => 'O campo "E-mail do diretor" é obrigatório.',
            '5.email' => 'O campo "E-mail do diretor" deve ser um e-mail válido.',
        ];
    }
}
