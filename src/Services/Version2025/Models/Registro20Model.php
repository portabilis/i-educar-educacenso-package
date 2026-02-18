<?php

namespace iEducar\Packages\Educacenso\Services\Version2025\Models;

use App\Models\Educacenso\Registro20;
use iEducar\Modules\Educacenso\Model\FormaOrganizacaoTurma;
use iEducar\Modules\Educacenso\Model\OrganizacaoCurricular;
use iEducar\Modules\Educacenso\Model\TipoItinerarioFormativo;
use iEducar\Packages\Educacenso\Services\Version2019\Registro20Import;
use Illuminate\Validation\ValidationException;

class Registro20Model extends Registro20
{
    public function hydrateModel($arrayColumns): void
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        if (is_null($arrayColumns[4]) || $arrayColumns[4] === '') {
            throw ValidationException::withMessages([
                'error' => 'Você está tentando importar um arquivo com turmas inválidas. o i-Educar aceita apenas arquivos oriundos do sistema do MEC.',
            ]);
        }

        $this->registro = $arrayColumns[1];
        $this->codigoEscolaInep = $arrayColumns[2];
        $this->codTurma = $arrayColumns[3];
        $this->inepTurma = $arrayColumns[4];
        $this->nomeTurma = mb_convert_encoding($arrayColumns[5], 'ISO-8859-1', 'UTF-8');
        $this->tipoMediacaoDidaticoPedagogico = $arrayColumns[6];

        // A HORA DA TURMA SERÁ CONTABILIZADA PELO HORÁRIO DA SEGUNDA FEIRA
        $this->horaInicial = $this->getHoraInicial($arrayColumns[8]);
        $this->horaInicialMinuto = $this->getMinutoInicial($arrayColumns[8]);
        $this->horaFinal = $this->getHoraFinal($arrayColumns[8]);
        $this->horaFinalMinuto = $this->getMinutoFinal($arrayColumns[8]);

        $this->diaSemanaDomingo = $arrayColumns[7] ?? null;
        $this->diaSemanaSegunda = $arrayColumns[8] ?? null;
        $this->diaSemanaTerca = $arrayColumns[9] ?? null;
        $this->diaSemanaQuarta = $arrayColumns[10] ?? null;
        $this->diaSemanaQuinta = $arrayColumns[11] ?? null;
        $this->diaSemanaSexta = $arrayColumns[12] ?? null;
        $this->diaSemanaSabado = $arrayColumns[13] ?? null;

        $this->tipoAtendimentoEscolarizacao = $arrayColumns[14];
        $this->tipoAtendimentoAtividadeComplementar = $arrayColumns[15];
        $this->tipoAtendimentoAee = $arrayColumns[16];

        $this->tipoAtividadeComplementar1 = $arrayColumns[17];
        $this->tipoAtividadeComplementar2 = $arrayColumns[18];
        $this->tipoAtividadeComplementar3 = $arrayColumns[19];
        $this->tipoAtividadeComplementar4 = $arrayColumns[20];
        $this->tipoAtividadeComplementar5 = $arrayColumns[21];
        $this->tipoAtividadeComplementar6 = $arrayColumns[22];

        $this->localFuncionamentoDiferenciado = $arrayColumns[23];
        $this->classeEspecial = $arrayColumns[24];
        $this->etapaAgregada = $arrayColumns[25];
        $this->etapaEducacenso = $arrayColumns[26];
        $this->codCurso = $arrayColumns[27];

        $this->formasOrganizacaoTurma = array_filter([
            $arrayColumns[28] ? FormaOrganizacaoTurma::SERIE_ANO : null,
            $arrayColumns[29] ? FormaOrganizacaoTurma::SEMESTRAL : null,
            $arrayColumns[30] ? FormaOrganizacaoTurma::CICLOS : null,
            $arrayColumns[31] ? FormaOrganizacaoTurma::NAO_SERIADO : null,
            $arrayColumns[32] ? FormaOrganizacaoTurma::MODULES : null,
        ]);

        $this->formacaoAlternancia = $arrayColumns[33];

        $this->estruturaCurricular = array_filter([
            $arrayColumns[34] ? OrganizacaoCurricular::FORMACAO_GERAL_BASICA : null,
            $arrayColumns[35] ? OrganizacaoCurricular::ITINERARIO_FORMATIVO_APROFUNDAMENTO : null,
            $arrayColumns[36] ? OrganizacaoCurricular::ITINERARIO_FORMACAO_TECNICA_PROFISSIONAL : null,
        ]);

        $this->areaItinerario = array_filter([
            $arrayColumns[37] ? TipoItinerarioFormativo::LINGUANGENS : null,
            $arrayColumns[38] ? TipoItinerarioFormativo::MATEMATICA : null,
            $arrayColumns[39] ? TipoItinerarioFormativo::CIENCIAS_NATUREZA : null,
            $arrayColumns[40] ? TipoItinerarioFormativo::CIENCIAS_HUMANAS : null,
        ]);

        $this->tipoCursoIntinerario = $arrayColumns[41];
        $this->codCursoProfissionalIntinerario = $arrayColumns[42];

        $this->componentes = $this->getComponentesByImportFile(array_slice($arrayColumns, 42, 26));
        $this->classeComLinguaBrasileiraSinais = $arrayColumns[70];
    }

    private function getComponentesByImportFile($componentesImportacao)
    {
        $arrayComponentes = array_keys(Registro20Import::getComponentes());

        $componentesExistentes = [];
        foreach ($componentesImportacao as $key => $value) {
            if ($value != '1') {
                continue;
            }

            $componentesExistentes[] = $arrayComponentes[$key];
        }

        return $componentesExistentes;
    }

    private function getHoraInicial($horario)
    {
        $hora = explode('-', $horario);
        $return = explode(':', $hora[0]);

        return $return[0];
    }

    private function getMinutoInicial($horario)
    {
        $hora = explode('-', $horario);
        $return = explode(':', $hora[0]);

        return $return[1];
    }

    private function getHoraFinal($horario)
    {
        $hora = explode('-', $horario);
        $return = explode(':', $hora[1]);

        return $return[0];
    }

    private function getMinutoFinal($horario)
    {
        $hora = explode('-', $horario);
        $return = explode(':', $hora[1]);

        return $return[1];
    }

}
