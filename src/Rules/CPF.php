<?php

namespace iEducar\Packages\Educacenso\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CPF implements ValidationRule
{
    private string $message = 'O campo CPF é inválido.';

    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void {
        $c = preg_replace('/\D/', '', $value);

        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            $fail($this->message);
        } else {
            for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

            if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                $fail($this->message);
            }

            for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

            if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                $fail($this->message);
            }
        }
    }
}
