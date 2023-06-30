<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class InepSchoolClass implements DataAwareRule, ValidationRule
{
    public function __construct(
        private string $field
    ) {
        var_export($this->field);
        die();
    }
    protected $data = [];

    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void {
        var_export($this->data);
        die();


        $name = 'teste';

        if (is_null($value) || $value == '') {
            $fail('O campo Código INEP da Turma ' . $name . ' é obrigatório.');
        }

        if (strlen($value) > 10) {
            $fail('O campo Código INEP da Turma ' . $name . ' não deve ter mais de 10 caracteres.');
        }

        if (is_numeric($value) == false) {
            $fail('O campo Código INEP da Turma ' . $name . ' deve conter apenas números.');
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
