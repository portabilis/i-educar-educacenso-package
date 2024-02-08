<?php

namespace iEducar\Packages\Educacenso\Services\Version2023\Models;

use App\Models\Educacenso\Registro60;

class Registro60Model extends Registro60
{
    public function hydrateModel($arrayColumns): void
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        $this->inepEscola = $arrayColumns[2];
        $this->inepAluno = $arrayColumns[4];
        $this->inepTurma = $arrayColumns[6];
        $this->etapaAluno = $arrayColumns[8] ?: null;
        $this->tipoItinerarioLinguagens = $arrayColumns[9];
        $this->tipoItinerarioMatematica = $arrayColumns[10];
        $this->tipoItinerarioCienciasNatureza = $arrayColumns[11];
        $this->tipoItinerarioCienciasHumanas = $arrayColumns[12];
        $this->tipoItinerarioFormacaoTecnica = $arrayColumns[13];
        $this->tipoItinerarioIntegrado = $arrayColumns[14];
        $this->composicaoItinerarioLinguagens = $arrayColumns[15];
        $this->composicaoItinerarioMatematica = $arrayColumns[16];
        $this->composicaoItinerarioCienciasNatureza = $arrayColumns[17];
        $this->composicaoItinerarioCienciasHumanas = $arrayColumns[18];
        $this->composicaoItinerarioFormacaoTecnica = $arrayColumns[19];
        $this->cursoItinerario = $arrayColumns[20] ?: null;
        $this->codCursoProfissional = $arrayColumns[21];
        $this->itinerarioConcomitante = $arrayColumns[22];
        $this->tipoAtendimentoDesenvolvimentoFuncoesGognitivas = $arrayColumns[23];
        $this->tipoAtendimentoDesenvolvimentoVidaAutonoma = $arrayColumns[24];
        $this->tipoAtendimentoEnriquecimentoCurricular = $arrayColumns[25];
        $this->tipoAtendimentoEnsinoInformaticaAcessivel = $arrayColumns[26];
        $this->tipoAtendimentoEnsinoLibras = $arrayColumns[27];
        $this->tipoAtendimentoEnsinoLinguaPortuguesa = $arrayColumns[28];
        $this->tipoAtendimentoEnsinoSoroban = $arrayColumns[29];
        $this->tipoAtendimentoEnsinoBraile = $arrayColumns[30];
        $this->tipoAtendimentoEnsinoOrientacaoMobilidade = $arrayColumns[31];
        $this->tipoAtendimentoEnsinoCaa = $arrayColumns[32];
        $this->tipoAtendimentoEnsinoRecursosOpticosNaoOpticos = $arrayColumns[33];
        $this->recebeEscolarizacaoOutroEspacao = $arrayColumns[34];
        $this->transportePublico = $arrayColumns[35] ?: null;
        $this->poderPublicoResponsavelTransporte = $arrayColumns[36] ?: null;
        $this->veiculoTransporteBicicleta = $arrayColumns[37];
        $this->veiculoTransporteMicroonibus = $arrayColumns[38];
        $this->veiculoTransporteOnibus = $arrayColumns[39];
        $this->veiculoTransporteTracaoAnimal = $arrayColumns[40];
        $this->veiculoTransporteVanKonbi = $arrayColumns[41];
        $this->veiculoTransporteOutro = $arrayColumns[42];
        $this->veiculoTransporteAquaviarioCapacidade5 = $arrayColumns[43];
        $this->veiculoTransporteAquaviarioCapacidade5a15 = $arrayColumns[44];
        $this->veiculoTransporteAquaviarioCapacidade15a35 = $arrayColumns[45];
        $this->veiculoTransporteAquaviarioCapacidadeAcima35 = (int) $arrayColumns[46];
    }
}
