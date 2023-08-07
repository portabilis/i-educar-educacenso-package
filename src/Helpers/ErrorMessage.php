<?php

namespace iEducar\Packages\Educacenso\Helpers;

use Closure;

class ErrorMessage
{
    public function __construct(
        public Closure|null $fail = null,
        public array        $message = []
    ) {
    }

    public function toString(array $data = null)
    {
        if (! is_null($data)) {
            $this->message = array_merge($this->message, $data);
        }

        if (! is_null($this->fail)) {
            $this->fail->__invoke(json_encode($this->message));

            return;
        }

        return json_encode($this->message);
    }
}
