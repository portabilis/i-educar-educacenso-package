<?php

namespace iEducar\Packages\Educacenso\Services;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Csv extends \PhpOffice\PhpSpreadsheet\Writer\Csv
{
    private $spreadsheet;

    /**
     * Delimiter.
     *
     * @var string
     */
    private $delimiter = '|';

    /**
     * Sheet index to write.
     *
     * @var int
     */
    private $sheetIndex = 0;

    public function __construct(Spreadsheet $spreadsheet)
    {
        parent::__construct($spreadsheet);
        $this->spreadsheet = $spreadsheet;
    }

    public function save(
        $filename,
        int $flags = 0
    ): void {
        $this->processFlags($flags);

        // Fetch sheet
        $sheet = $this->spreadsheet->getSheet($this->sheetIndex);

        $saveDebugLog = Calculation::getInstance($this->spreadsheet)->getDebugLog()->getWriteDebugLog();
        Calculation::getInstance($this->spreadsheet)->getDebugLog()->setWriteDebugLog(false);
        $saveArrayReturnType = Calculation::getArrayReturnType();
        Calculation::setArrayReturnType(Calculation::RETURN_ARRAY_AS_VALUE);

        $this->openFileHandle($filename);

        $maxRow = $sheet->getHighestDataRow();

        for ($row = 1; $row <= $maxRow; ++$row) {
            $maxCol = $sheet->getHighestDataColumn($row);
            $cellsArray = $sheet->rangeToArray('A' . $row . ':' . $maxCol . $row, '', $this->preCalculateFormulas);
            $this->writeLine($this->fileHandle, $cellsArray[0]);
        }

        $this->maybeCloseFileHandle();
        Calculation::setArrayReturnType($saveArrayReturnType);
        Calculation::getInstance($this->spreadsheet)->getDebugLog()->setWriteDebugLog($saveDebugLog);
    }
    private function writeLine($fileHandle, array $values): void
    {
        $delimiter = '';
        $line = '';
        foreach ($values as $element) {
            $line .= $delimiter;
            $delimiter = $this->delimiter;
            $line .= mb_strtoupper($element);
        }

        $line .= PHP_EOL;
        fwrite($fileHandle, /** @scrutinizer ignore-type */ $line);
    }
}
