<?php

declare(strict_types=1);

if (! function_exists('clearInt')) {
    function clearInt(string $var): string|null
    {
        return preg_replace('/\D/', '', $var);
    }
}

if (! function_exists('convertSituationIEducarToEducacenso')) {
    function convertSituationIEducarToEducacenso(int $situation): int
    {
        return match ($situation) {
            App_Model_MatriculaSituacao::APROVADO => 5,
            App_Model_MatriculaSituacao::REPROVADO => 4,
            App_Model_MatriculaSituacao::EM_ANDAMENTO => 7,
            App_Model_MatriculaSituacao::TRANSFERIDO => 1,
            App_Model_MatriculaSituacao::RECLASSIFICADO => 7,
            App_Model_MatriculaSituacao::ABANDONO => 2,
            App_Model_MatriculaSituacao::EM_EXAME => 7,
            App_Model_MatriculaSituacao::APROVADO_APOS_EXAME => 5,
            App_Model_MatriculaSituacao::APROVADO_SEM_EXAME => 5,
            App_Model_MatriculaSituacao::PRE_MATRICULA => 7,
            App_Model_MatriculaSituacao::APROVADO_COM_DEPENDENCIA => 5,
            App_Model_MatriculaSituacao::APROVADO_PELO_CONSELHO => 5,
            App_Model_MatriculaSituacao::REPROVADO_POR_FALTAS => 4,
            App_Model_MatriculaSituacao::FALECIDO => 3,
        };
    }
}
