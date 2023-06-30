<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

use App\Models\LegacySchoolClass;
use App\Models\LegacyStudent;
use iEducar\Packages\Educacenso\Layout\Export\Contracts\Validation;

class Record90 extends Validation
{
    public function __construct(
        public array $data
    ) {
    }

    public function rules()
    {
        $data = $this->data;
        return [
            'turmas.*.1' => [
                'required',
                'integer',
                'in:90',
            ],
            'turmas.*.2' => [
                'required',
                'integer',
                'digits:8',
            ],
            'turmas.*.3' => [
                'nullable',
                'max:20'
            ],
            'turmas.*.4' => [
                function ($attribute, $value, $fail) use ($data): void {
                    $index = array_search($value, array_column($data, '4'), true);

                    $schoolClassId = $data[$index]['3'];

                    if (is_null($value) || $value == '') {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $fail('O campo Código INEP da Turma ' . $schoolClass->name . ' é obrigatório.');
                    }

                    if (strlen($value) > 10) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $fail('O campo Código INEP da Turma ' . $schoolClass->name . ' deve possuir no máximo 10 caracteres.');
                    }

                    if (is_numeric($value) == false) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $fail('O campo Código INEP da Turma ' . $schoolClass->name . ' deve conter apenas números.');
                    }
                }
            ],
            'turmas.*.5' => [
                function ($attribute, $value, $fail) use ($data): void {
                    $index = array_search($value, array_column($data, '5'), true);
                    $studentId = $data[$index]['6'];

                    if (is_null($value) || $value == '') {
                        $student = LegacyStudent::find($studentId);
                        $fail('O campo Código INEP do Aluno ' . $student->name . ' é obrigatório.');
                    }

                    if (strlen($value) != 12) {
                        $student = LegacyStudent::find($studentId);
                        $fail('O campo Código INEP do Aluno ' . $student->name . ' deve possuir 12 caracteres.');
                    }

                    if (is_numeric($value) == false) {
                        $student = LegacyStudent::find($studentId);
                        $fail('O campo Código INEP do Aluno ' . $student->name . ' deve conter apenas números.');
                    }
                }
            ],
            'turmas.*.6' => [
                'required',
                'max:20'
            ],
            'turmas.*.7' => [
                'required',
                'integer',
                'digits_between:0,12'
            ],
            'turmas.*.8' => [
                'required',
                'integer',
                'in:1,2,3,4,5,6,7',
            ],
        ];
    }

    public function messages()
    {
        return [
            'turmas.*.2.required' => 'O campo "Código INEP da escola" é obrigatório.',
            'turmas.*.2.integer' => 'O campo "Código INEP da escola" deve ser de apenas números.',
            'turmas.*.2.digits' => 'O campo "Código INEP da escola" deve conter 8 dígitos.',
            'turmas.*.3.max' => 'O campo "Código da Turma" deve conter no máximo 20 caracteres.',
            'turmas.*.4.required' => 'O campo "Código INEP da Turma" é obrigatório.',
            'turmas.*.4.integer' => 'O campo "Código INEP da Turma" deve ser de apenas números.',
            'turmas.*.4.digits_between' => 'O campo "Código INEP da Turma" deve conter entre 0 e 10 dígitos.',
            'turmas.*.5.required' => 'O campo "Código INEP do Aluno" é obrigatório.',
            'turmas.*.5.integer' => 'O campo "Código INEP do Aluno" deve ser de apenas números.',
            'turmas.*.5.digits' => 'O campo "Código INEP do Aluno" deve conter 12 dígitos.',
            'turmas.*.6.max' => 'O campo "Código do Aluno" deve conter no máximo 20 caracteres.',
            'turmas.*.7.required' => 'O campo "Código da Matrícula" é obrigatório.',
            'turmas.*.7.integer' => 'O campo "Código da Matrícula" deve ser de apenas números.',
            'turmas.*.7.digits_between' => 'O campo "Código da Matrícula" deve conter entre 0 e 12 dígitos.',
            'turmas.*.8.required' => 'O campo "Situação da Matrícula" é obrigatório.',
            'turmas.*.8.integer' => 'O campo "Situação de matrícula" deve ser de apenas números.',
            'turmas.*.8.in' => 'O campo "Tipo de situação de matrícula" deve ser um dos seguintes valores: :values.',
        ];
    }
}
