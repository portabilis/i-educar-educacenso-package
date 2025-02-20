<?php

namespace iEducar\Packages\Educacenso\Http\Requests;

use App\Models\LegacyInstitution;
use App\Models\LegacySchool;
use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberEnrollment;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberSchool;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberSchoolClass;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyInepNumberStudent;
use iEducar\Packages\Educacenso\Rules\IsNotEmptyStage56EducacensoGrade;
use Illuminate\Foundation\Http\FormRequest;

class ExportSituationRequest extends FormRequest
{
    protected $redirectRoute = 'export.impediments';

    protected function prepareForValidation(): void
    {
        $this->merge([
            'year' => $this->input('ano'),
            'institution_id' => $this->input('ref_cod_instituicao'),
            'school_id' => $this->input('ref_cod_escola'),
        ]);
    }

    public function rules()
    {
        return [
            'year' => [
                'required',
                'integer',
                'in:' . implode(',', array_values(config('educacenso.stage-2.years'))),
            ],
            'institution_id' => [
                'required',
                'integer',
                'exists:' . LegacyInstitution::class . ',cod_instituicao',
            ],
            'school_id' => [
                'required',
                'integer',
                'exists:' . LegacySchool::class . ',cod_escola',
                new IsNotEmptyInepNumberSchool(),
                new IsNotEmptyInepNumberSchoolClass(),
                new IsNotEmptyInepNumberStudent(),
                new IsNotEmptyInepNumberEnrollment(),
                new IsNotEmptyStage56EducacensoGrade(),
            ]
        ];
    }

    public function messages()
    {
        $errorMessage = new ErrorMessage();

        return [
            'year.required' => $errorMessage->toString([
                'message' => 'O campo ano é obrigatório.',
                'field' => 'year',
            ]),
            'year.integer' => $errorMessage->toString([
                'message' => 'O campo ano deve ser um número inteiro.',
                'field' => 'year'
            ]),
            'year.in' => $errorMessage->toString([
                'message' => 'O ano informado não possui um layout disponível para exportação.',
                'field' => 'year',
                'value' => implode(',', array_values(config('educacenso.stage-2.years'))),
            ]),
            'institution_id.required' => $errorMessage->toString([
                'message' => 'O campo Instituição é obrigatório.',
                'field' => 'institution_id',
            ]),
            'institution_id.exists' => $errorMessage->toString([
                'message' => 'A Instituição informada não foi localizada.',
                'field' => 'institution_id',
            ]),
            'school_id.required' => $errorMessage->toString([
                'message' => 'O campo escola é obrigatório.',
                'field' => 'school_id',
            ]),
            'school_id.exists' => $errorMessage->toString([
                'message' => 'A escola informada não foi localizada.',
                'field' => 'school_id',
            ]),
            'school_id.integer' => $errorMessage->toString([
                'message' => 'O campo escola deve ser um número inteiro.',
                'field' => 'school_id',
            ]),
        ];
    }
}
