<?php

declare(strict_types=1);

if (! function_exists('clearInt')) {
    function clearInt(string $var): ?string
    {
        return preg_replace('/\D/', '', $var);
    }
}

if (! function_exists('convertSituationIEducarToEducacenso')) {
    function convertSituationIEducarToEducacenso(int $situation, int $etapaTurma = 0, ?int $etapaSerie = null): int
    {
        $etapasMultiEtapas = [56];
        if (in_array($etapaTurma, $etapasMultiEtapas, true)) {
            $etapaTurma = $etapaSerie;
        }

        $etapasConcluintes = [27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 41, 67, 68, 70, 71, 73, 74];
        $situacoesAprovado = [
            App_Model_MatriculaSituacao::APROVADO,
            App_Model_MatriculaSituacao::APROVADO_APOS_EXAME,
            App_Model_MatriculaSituacao::APROVADO_SEM_EXAME,
            App_Model_MatriculaSituacao::APROVADO_COM_DEPENDENCIA,
            App_Model_MatriculaSituacao::APROVADO_PELO_CONSELHO,
        ];

        if (in_array($situation, $situacoesAprovado, true) && in_array($etapaTurma, $etapasConcluintes, true)) {
            return 6;
        }

        $etapasEducacaoInfantil = [1, 2, 3];

        if (in_array($situation, $situacoesAprovado, true) && in_array($etapaTurma, $etapasEducacaoInfantil, true)) {
            return 7;
        }

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
            default => 7,
        };
    }
}

if (! function_exists('convertSituationEducacensoToIeducar')) {
    function convertSituationEducacensoToIeducar(int  $situation, int $etapaTurma = 0, ?int $etapaSerie = null): int
    {
        $etapasMultiEtapas = [56];
        if (in_array($etapaTurma, $etapasMultiEtapas, true)) {
            $etapaTurma = $etapaSerie;
        }

        $etapasEducacaoInfantil = [1, 2, 3];
        if (in_array($etapaTurma, $etapasEducacaoInfantil, true) && $situation === 7) {
            return App_Model_MatriculaSituacao::APROVADO;
        }

        return match ($situation) {
            1 => App_Model_MatriculaSituacao::TRANSFERIDO,
            2 => App_Model_MatriculaSituacao::ABANDONO,
            3 => App_Model_MatriculaSituacao::FALECIDO,
            4 => App_Model_MatriculaSituacao::REPROVADO,
            5, 6 => App_Model_MatriculaSituacao::APROVADO,
            default => App_Model_MatriculaSituacao::EM_ANDAMENTO,
        };
    }
}
