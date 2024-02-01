<?php

namespace iEducar\Packages\Educacenso\Layout\Export\Situation;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class Export implements WithCustomCsvSettings, FromCollection
{
    public function __construct(
        public array $data
    ) {
    }

    public function collection()
    {
        $collect = collect();

        $collect->push($this->data['escola']);

        $array [] = $this->data['escola'];
        foreach ($this->data['matriculas'] as $matricula) {
            $collect->push($matricula);
        }
        foreach ($this->data['turma_matriculas'] as $turma_matricula) {
            $collect->push($turma_matricula);
        }

        return $collect;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => '|',
            'enclosure' => '',
        ];
    }
}
