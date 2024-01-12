<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

use App\Models\LegacySchoolClass;
use App\Models\LegacyStudent;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use iEducar\Packages\Educacenso\Layout\Export\Contracts\Validation;

class Record91 extends Validation
{
    public function __construct(
        public array $data
    ) {
    }

    public function rules(): array
    {
        $data = $this->data;
        return [
            'turma_matriculas.*.1' => [
                'required',
                'integer',
                'in:91',
            ],
            'turma_matriculas.*.2' => [
                'required',
                'integer',
                'digits:8',
            ],
            'turma_matriculas.*.3' => [
                'nullable',
                'max:20'
            ],
            'turma_matriculas.*.4' => [
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
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' é obrigatório.',
                        ]);
                    }

                    if (strlen($value) > 10) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve possuir no máximo 10 caracteres.',
                        ]);
                    }

                    if (is_numeric($value) == false) {
                        $schoolClass = LegacySchoolClass::find($schoolClassId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP da Turma ' . $schoolClass->name . ' deve conter apenas números.',
                        ]);
                    }
                }
            ],
            'turma_matriculas.*.5' => [
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
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP do Aluno ' . $student->name . ' é obrigatório.',
                        ]);
                    }

                    if (strlen($value) != 12) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP do Aluno ' . $student->name . ' deve possuir 12 caracteres.',
                        ]);
                    }

                    if (is_numeric($value) == false) {
                        $student = LegacyStudent::find($studentId);
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 91 inválidos. O campo Código INEP do Aluno ' . $student->name . ' deve conter apenas números.',
                        ]);
                    }
                }
            ],
            'turma_matriculas.*.6' => [
                'nullable',
                'max:20'
            ],
            'turma_matriculas.*.7' => [
                'nullable',
                'max:12'
            ],
            'turma_matriculas.*.8' => [
                'nullable',
                'max:1'
            ],
            'turma_matriculas.*.9' => [
                'nullable',
                'max:1'
            ],
            'turma_matriculas.*.10' => [
                'nullable',
                'max:2'
            ],
            'turma_matriculas.*.11' => [
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
            'turma_matriculas.*.2.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da escola" é obrigatório.',
            ]),
            'turma_matriculas.*.2.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da escola" deve ser de apenas números.',
            ]),
            'turma_matriculas.*.2.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da escola" deve conter 8 dígitos.',
            ]),
            'turma_matriculas.*.3.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código da Turma" deve conter no máximo 20 caracteres.',
            ]),
            'turma_matriculas.*.4.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da Turma" é obrigatório.',
            ]),
            'turma_matriculas.*.4.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da Turma" deve ser de apenas números.',
            ]),
            'turma_matriculas.*.4.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP da Turma" deve conter 10 dígitos.',
            ]),
            'turma_matriculas.*.5.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP do Aluno" é obrigatório.',
            ]),
            'turma_matriculas.*.5.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP do Aluno" deve ser de apenas números.',
            ]),
            'turma_matriculas.*.5.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código INEP do Aluno" deve conter 12 dígitos.',
            ]),
            'turma_matriculas.*.6.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Código da Turma" deve conter no máximo 20 caracteres.',
            ]),
            'turma_matriculas.*.11.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Etapa de Ensino" é obrigatório.',
            ]),
            'turma_matriculas.*.11.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Etapa de Ensino" deve ser de apenas números.',
            ]),
            'turma_matriculas.*.11.in' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 91 inválidos. O campo "Etapa de Ensino" deve ser um valor entre 1 e 7.',
            ]),
        ];
    }
}
