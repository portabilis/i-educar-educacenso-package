<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Contracts;

abstract class Validation
{
    abstract public function rules();

    abstract public function messages();
}
