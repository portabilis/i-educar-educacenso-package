<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class Export implements FromCollection
{
    public function __construct(
        public array $data
    ) {
    }

    public function collection(): Collection
    {
        $collect = collect();

        $collect->push($this->data['escola']);

        foreach ($this->data['matriculas'] as $matricula) {
            $collect->push($matricula);
        }
        foreach ($this->data['turma_matriculas'] as $turma_matricula) {
            $collect->push($turma_matricula);
        }

        return $collect;
    }
}
