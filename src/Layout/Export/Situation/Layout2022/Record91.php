<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

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
            'turma_matriculas.*.5' => [
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
        return [

        ];
    }
}
