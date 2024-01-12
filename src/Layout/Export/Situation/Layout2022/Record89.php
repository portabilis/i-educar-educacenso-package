<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation\Layout2022;

use iEducar\Packages\Educacenso\Helpers\ErrorMessage;
use iEducar\Packages\Educacenso\Layout\Export\Contracts\Validation;

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
                function ($attribute, $value, $fail): void {
                    $c = preg_replace('/\D/', '', $value);

                    $errorMessage = new ErrorMessage($fail, [
                        'key' => 'cpf',
                        'value' => $value,
                    ]);

                    if (is_null($value) || $value == '') {
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" é obrigatório.',
                        ]);
                    }

                    if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
                        $errorMessage->toString([
                            'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" é diferente de 11 caracteres.',
                        ]);
                    } else {
                        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

                        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                            $errorMessage->toString([
                                'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" é inválido.',
                            ]);
                        }

                        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

                        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                            $errorMessage->toString([
                                'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" é inválido.',
                            ]);
                        }
                    }
                }
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
                'in:1,2',
            ],
            'escola.6' => [
                'required',
                'email',
                'regex:/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/'
            ],
        ];
    }

    public function messages()
    {
        $errorMessage = new ErrorMessage();

        return [
            'escola.2.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Código INEP da escola" é obrigatório.',
            ]),
            'escola.2.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Código INEP da escola" deve conter 8 dígitos.',
            ]),
            'escola.3.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" é obrigatório.',
            ]),
            'escola.3.digits' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" deve conter 11 dígitos.',
            ]),
            'escola.3.cpf' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "CPF do Gestor" deve ser um CPF válido.',
            ]),
            'escola.4.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Nome do Gestor" é obrigatório.',
            ]),
            'escola.4.string' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Nome do Gestor" deve conter apenas letras e espaços.',
            ]),
            'escola.4.max' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Nome do Gestor" deve conter no máximo 100 caracteres.',
            ]),
            'escola.4.regex' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Nome do Gestor" deve conter apenas letras e espaços.',
            ]),
            'escola.5.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Tipo de Gestor" é obrigatório.',
            ]),
            'escola.5.integer' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Tipo de Gestor" deve ser um número inteiro.',
            ]),
            'escola.5.in' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "Tipo de Gestor" deve ser 1.',
            ]),
            'escola.6.required' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "E-mail do Gestor" é obrigatório.',
            ]),
            'escola.6.email' => $errorMessage->toString([
                'message' => 'Dados para formular o registro 89 inválidos. O campo "E-mail do Gestor" deve ser um e-mail válido.',
            ]),
        ];
    }
}
