<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

use App\Models\LegacySchoolClass;
use App\Models\LegacyStudent;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
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
            'matriculas.*.1' => [
                'required',
                'integer',
                'in:90',
            ],
            'matriculas.*.2' => [
                'required',
                'integer',
                'digits:8',
            ],
            'matriculas.*.3' => [
                'nullable',
                'max:20'
            ],
            'matriculas.*.4' => [
                function ($attribute, $value, $fail) use ($data): void {
                    $index = array_search($value, array_column($data, '4'), true);

                    $schoolClassId = $data[$index]['3'];

                    $errorMessage = new ErrorMessage($fail, [
                        'key' => 'cod_turma',
                        'breadcrumb' => 'Escolas -> Cadastros -> Turmas -> Dados Adicionais -> Código INEP',
                        'value' => $schoolClassId,
                        'url' => 'intranet/educar_turma_cad.php?cod_turma=' . $schoolClassId
                    ]);

                    if (is_null($value) || $value == '') {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' é obrigatório.',
                        ]);
                    }

                    if (strlen($value) > 10) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve possuir no máximo 10 caracteres.',
                        ]);
                    }

                    if (is_numeric($value) == false) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve conter apenas números.',
                        ]);
                    }
                }
            ],
            'matriculas.*.5' => [
                function ($attribute, $value, $fail) use ($data): void {
                    $index = array_search($value, array_column($data, '5'), true);
                    $studentId = $data[$index]['6'];

                    $errorMessage = new ErrorMessage($fail, [
                        'key' => 'cod_aluno',
                        'value' => $studentId,
                        'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Código INEP',
                        'url' => '/module/Cadastro/aluno?id=' . $studentId
                    ]);

                    if (is_null($value) || $value == '') {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do Aluno ' . $student->name . ' é obrigatório.',
                        ]);
                    }

                    if (strlen($value) != 12) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do Aluno ' . $student->name . ' deve possuir 12 caracteres.',
                        ]);
                    }

                    if (is_numeric($value) == false) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do Aluno ' . $student->name . ' deve conter apenas números.',
                        ]);
                    }
                }
            ],
            'matriculas.*.6' => [
                'required',
                'max:20'
            ],
            'matriculas.*.7' => [
                'required',
                'integer',
                'digits_between:0,12'
            ],
            'matriculas.*.8' => [
                'required',
                'integer',
                'in:1,2,3,4,5,6,7',
            ],
        ];
    }

    public function messages()
    {
        $errorMessage = new ErrorMessage();

        return [
            'matriculas.*.2.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" é obrigatório.'
            ]),
            'matriculas.*.2.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" deve ser de apenas números.'
            ]),
            'matriculas.*.2.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" deve conter 8 dígitos.'
            ]),
            'matriculas.*.3.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código da Turma" deve conter no máximo 20 caracteres.'
            ]),
            'matriculas.*.6.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código do Aluno" deve conter no máximo 20 caracteres.',
            ]),
            'matriculas.*.7.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código da Matrícula" é obrigatório.',
            ]),
            'matriculas.*.7.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código da Matrícula" deve ser de apenas números.',
            ]),
            'matriculas.*.7.digits_between' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código da Matrícula" deve conter entre 0 e 12 dígitos.',
            ]),
            'matriculas.*.8.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Situação da Matrícula" é obrigatório.',
            ]),
            'matriculas.*.8.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Situação de matrícula" deve ser de apenas números.',
            ]),
            'matriculas.*.8.in' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Situação de matrícula" deve ser um dos seguintes valores: 1, 2, 3, 4, 5, 6 ou 7.',
            ]),
        ];
    }
}
