<?php

namespace iEducar\Packages\Educacenso\Services\Version2025\Models;

use App\Models\Educacenso\Registro30;
use iEducar\Modules\Educacenso\Model\Escolaridade;

class Registro30Model extends Registro30
{
    public function hydrateModel($arrayColumns): void
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        $this->inepEscola = $arrayColumns[2];
        $this->codigoPessoa = $arrayColumns[3];
        $this->inepPessoa = $arrayColumns[4];
        $this->cpf = $arrayColumns[5];
        $this->nomePessoa = $arrayColumns[6];
        $this->dataNascimento = $arrayColumns[7];
        $this->filiacao = $arrayColumns[8];
        $this->filiacao1 = $arrayColumns[9];
        $this->filiacao2 = $arrayColumns[10];
        $this->sexo = $arrayColumns[11];
        $this->raca = $arrayColumns[12];
        $this->povoIndigena = $arrayColumns[13];
        $this->nacionalidade = $arrayColumns[14];
        $this->paisNacionalidade = $arrayColumns[15];
        $this->municipioNascimento = $arrayColumns[16];
        $this->deficiencia = $arrayColumns[17];
        $this->deficienciaCegueira = $arrayColumns[18];
        $this->deficienciaBaixaVisao = $arrayColumns[19];
        $this->deficienciaVisaoMonocular = $arrayColumns[20];
        $this->deficienciaSurdez = $arrayColumns[21];
        $this->deficienciaAuditiva = $arrayColumns[22];
        $this->deficienciaSurdoCegueira = $arrayColumns[23];
        $this->deficienciaFisica = $arrayColumns[24];
        $this->deficienciaIntelectual = $arrayColumns[25];
        $this->deficienciaMultipla = $arrayColumns[26];
        $this->deficienciaAutismo = $arrayColumns[27];
        $this->deficienciaAltasHabilidades = $arrayColumns[28];
        $this->transtorno = $arrayColumns[29];
        $this->transtornoDiscalculia = $arrayColumns[30];
        $this->transtornoDisgrafia = $arrayColumns[31];
        $this->transtornoDislalia = $arrayColumns[32];
        $this->transtornoDislexia = $arrayColumns[33];
        $this->transtornoTdah = $arrayColumns[34];
        $this->transtornoTpac = $arrayColumns[35];
        $this->recursoLedor = $arrayColumns[36];
        $this->recursoTranscricao = $arrayColumns[37];
        $this->recursoGuia = $arrayColumns[38];
        $this->recursoTradutor = $arrayColumns[39];
        $this->recursoLeituraLabial = $arrayColumns[40];
        $this->recursoProvaAmpliada = $arrayColumns[41];
        $this->recursoProvaSuperampliada = $arrayColumns[42];
        $this->recursoAudio = $arrayColumns[43];
        $this->recursoLinguaPortuguesaSegundaLingua = $arrayColumns[44];
        $this->recursoVideoLibras = $arrayColumns[45];
        $this->recursoBraile = $arrayColumns[46];
        $this->provaBraile = $arrayColumns[47];
        $this->recursoTempoAdicional = $arrayColumns[48];
        $this->recursoNenhum = $arrayColumns[49];
        $this->certidaoNascimento = $arrayColumns[50];
        $this->paisResidencia = $arrayColumns[51];
        $this->cep = $arrayColumns[52];
        $this->municipioResidencia = $arrayColumns[53];
        $this->localizacaoResidencia = $arrayColumns[54];
        $this->localizacaoDiferenciada = $arrayColumns[55];
        $this->escolaridade = $arrayColumns[56];
        $this->tipoEnsinoMedioCursado = $arrayColumns[57];
        $this->formacaoCurso = [
            $arrayColumns[58],
            $arrayColumns[61],
            $arrayColumns[64],
        ];
        $this->formacaoAnoConclusao = [
            $arrayColumns[59],
            $arrayColumns[62],
            $arrayColumns[65],
        ];
        $this->formacaoInstituicao = [
            $arrayColumns[60],
            $arrayColumns[63],
            $arrayColumns[66],
        ];
        $this->complementacaoPedagogica = array_filter([
            $arrayColumns[67],
            $arrayColumns[68],
            $arrayColumns[69],
        ]);

        $this->posGraduacoes = [];
        if (! empty($arrayColumns[70])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[70],
                'area' => $arrayColumns[72],
                'ano_conclusao' => $arrayColumns[72],
            ];
        }

        if (! empty($arrayColumns[73])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[73],
                'area' => $arrayColumns[74],
                'ano_conclusao' => $arrayColumns[75],
            ];
        }

        if (! empty($arrayColumns[76])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[76],
                'area' => $arrayColumns[77],
                'ano_conclusao' => $arrayColumns[78],
            ];
        }

        if (! empty($arrayColumns[79])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[79],
                'area' => $arrayColumns[80],
                'ano_conclusao' => $arrayColumns[81],
            ];
        }

        if (! empty($arrayColumns[82])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[82],
                'area' => $arrayColumns[83],
                'ano_conclusao' => $arrayColumns[84],
            ];
        }

        if (! empty($arrayColumns[85])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[85],
                'area' => $arrayColumns[86],
                'ano_conclusao' => $arrayColumns[87],
            ];
        }

        $this->posGraduacaoNaoPossui = $arrayColumns[88];
        $this->formacaoContinuadaCreche = $arrayColumns[89];
        $this->formacaoContinuadaPreEscola = $arrayColumns[90];
        $this->formacaoContinuadaAnosIniciaisFundamental = $arrayColumns[91];
        $this->formacaoContinuadaAnosFinaisFundamental = $arrayColumns[92];
        $this->formacaoContinuadaEnsinoMedio = $arrayColumns[93];
        $this->formacaoContinuadaEducacaoJovensAdultos = $arrayColumns[94];
        $this->formacaoContinuadaEducacaoEspecial = $arrayColumns[95];
        $this->formacaoContinuadaEducacaoIndigena = $arrayColumns[96];
        $this->formacaoContinuadaEducacaoCampo = $arrayColumns[97];
        $this->formacaoContinuadaEducacaoAmbiental = $arrayColumns[98];
        $this->formacaoContinuadaEducacaoDireitosHumanos = $arrayColumns[99];
        $this->formacaoContinuadaEducacaoBilingueSurdos = $arrayColumns[100];
        $this->formacaoContinuadaEducacaoTecnologiaInformacaoComunicacao = $arrayColumns[101];
        $this->formacaoContinuadaGeneroDiversidadeSexual = $arrayColumns[102];
        $this->formacaoContinuadaDireitosCriancaAdolescente = $arrayColumns[103];
        $this->formacaoContinuadaEducacaoRelacoesEticoRaciais = $arrayColumns[104];
        $this->formacaoContinuadaEducacaoGestaoEscolar = $arrayColumns[105];
        $this->formacaoContinuadaEducacaoOutros = $arrayColumns[106];
        $this->formacaoContinuadaEducacaoNenhum = $arrayColumns[107];
        $this->email = $arrayColumns[108];

        if (in_array($this->escolaridade, [Escolaridade::EDUCACAO_SUPERIOR, Escolaridade::ENSINO_MEDIO], true)) {
            $this->tipos[self::TIPO_TEACHER] = true;
            $this->tipos[self::TIPO_MANAGER] = true;
        } else {
            $this->tipos[self::TIPO_STUDENT] = true;
        }
    }
}
