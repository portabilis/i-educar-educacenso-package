<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2023;

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
                    } elseif (strlen($value) > 10) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve possuir no máximo 10 caracteres.',
                        ]);
                    } elseif (is_numeric($value) == false) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve conter apenas números.',
                        ]);
                    }
                }
            ],
            'matriculas.*' => [
                function ($attribute, $value, $fail) use ($data): void {
                    $inep = $value['5'];
                    $studentId = $value['6'];

                    $errorMessage = new ErrorMessage($fail, [
                        'key' => 'cod_aluno',
                        'value' => $studentId,
                        'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Código INEP',
                        'url' => '/module/Cadastro/aluno?id=' . $studentId
                    ]);

                    if (is_null($inep) || $inep == '') {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' é obrigatório.',
                        ]);
                    } elseif (strlen($inep) != 12) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' deve possuir 12 caracteres.',
                        ]);
                    } elseif (is_numeric($inep) == false) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Código INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' deve conter apenas números.',
                        ]);
                    }
                },
            ],
            'matriculas.*.6' => [
                'required',
                'max:20',
            ],
            'matriculas.*' => [
                function ($attribute, $value, $fail): void {
                    $schoolClassId = $value['3'];
                    $studentId = $value['6'];
                    $matricula = $value['7'];

                    $errorMessage = new ErrorMessage($fail, [
                        'key' => 'cod_aluno',
                        'value' => $studentId,
                        'breadcrumb' => 'Escolas -> Cadastros -> Alunos -> Matrícula -> Histórico de Enturmações',
                        'url' => '/intranet/educar_aluno_det.php?cod_aluno=' . $studentId,
                    ]);

                    if (is_null($matricula) || $matricula == '') {
                        $student = LegacyStudent::find($studentId);
                        $schoolClass = LegacySchoolClass::find($schoolClassId);

                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' na Turma ' . $schoolClass->name . ' é obrigatório.',
                        ]);
                    } elseif (strlen($matricula) > 12) {
                        $student = LegacyStudent::find($studentId);
                        $schoolClass = LegacySchoolClass::find($schoolClassId);

                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' na Turma ' . $schoolClass->name . ' não pode possuir mais de 12 caracteres.',
                        ]);
                    } elseif (is_numeric($matricula) == false) {
                        $student = LegacyStudent::find($studentId);
                        $schoolClass = LegacySchoolClass::find($schoolClassId);

                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 90 inválidos. O campo Matrícula INEP do(a) Aluno(a) ' . mb_strtoupper($student->name) . ' na Turma ' . $schoolClass->name . ' deve conter apenas números.',
                        ]);
                    }
                },
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
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" é obrigatório.',
            ]),
            'matriculas.*.2.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" deve ser de apenas números.',
            ]),
            'matriculas.*.2.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código INEP da escola" deve conter 8 dígitos.',
            ]),
            'matriculas.*.3.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 90 inválidos. O campo "Código da Turma" deve conter no máximo 20 caracteres.',
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
