<?php

namespace iEducar\Packages\Educacenso\Services\Version2023\Models;

use App\Models\Educacenso\Registro30;

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
        $this->nacionalidade = $arrayColumns[13];
        $this->paisNacionalidade = $arrayColumns[14];
        $this->municipioNascimento = $arrayColumns[15];
        $this->deficiencia = $arrayColumns[16];
        $this->deficienciaCegueira = $arrayColumns[17];
        $this->deficienciaBaixaVisao = $arrayColumns[18];
        $this->deficienciaVisaoMonocular = $arrayColumns[19];
        $this->deficienciaSurdez = $arrayColumns[20];
        $this->deficienciaAuditiva = $arrayColumns[21];
        $this->deficienciaSurdoCegueira = $arrayColumns[22];
        $this->deficienciaFisica = $arrayColumns[23];
        $this->deficienciaIntelectual = $arrayColumns[24];
        $this->deficienciaMultipla = $arrayColumns[25];
        $this->deficienciaAutismo = $arrayColumns[26];
        $this->deficienciaAltasHabilidades = $arrayColumns[27];
        $this->recursoLedor = $arrayColumns[28];
        $this->recursoTranscricao = $arrayColumns[29];
        $this->recursoGuia = $arrayColumns[30];
        $this->recursoTradutor = $arrayColumns[31];
        $this->recursoLeituraLabial = $arrayColumns[32];
        $this->recursoProvaAmpliada = $arrayColumns[33];
        $this->recursoProvaSuperampliada = $arrayColumns[34];
        $this->recursoAudio = $arrayColumns[35];
        $this->recursoLinguaPortuguesaSegundaLingua = $arrayColumns[36];
        $this->recursoVideoLibras = $arrayColumns[37];
        $this->recursoBraile = $arrayColumns[38];
        $this->recursoNenhum = $arrayColumns[39];
        $this->certidaoNascimento = $arrayColumns[40];
        $this->paisResidencia = $arrayColumns[41];
        $this->cep = $arrayColumns[42];
        $this->municipioResidencia = $arrayColumns[43];
        $this->localizacaoResidencia = $arrayColumns[44];
        $this->localizacaoDiferenciada = $arrayColumns[45];
        $this->escolaridade = $arrayColumns[46];
        $this->tipoEnsinoMedioCursado = $arrayColumns[47];
        $this->formacaoCurso = [
            $arrayColumns[48],
            $arrayColumns[51],
            $arrayColumns[54],
        ];
        $this->formacaoAnoConclusao = [
            $arrayColumns[49],
            $arrayColumns[52],
            $arrayColumns[55],
        ];
        $this->formacaoInstituicao = [
            $arrayColumns[50],
            $arrayColumns[53],
            $arrayColumns[56],
        ];
        $this->complementacaoPedagogica = array_filter([
            $arrayColumns[57],
            $arrayColumns[58],
            $arrayColumns[59],
        ]);

        $this->posGraduacoes = [];
        if (! empty($arrayColumns[60])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[60],
                'area' => $arrayColumns[61],
                'ano_conclusao' => $arrayColumns[62],
            ];
        }

        if (! empty($arrayColumns[63])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[63],
                'area' => $arrayColumns[64],
                'ano_conclusao' => $arrayColumns[65],
            ];
        }

        if (! empty($arrayColumns[66])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[66],
                'area' => $arrayColumns[67],
                'ano_conclusao' => $arrayColumns[68],
            ];
        }

        if (! empty($arrayColumns[69])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[69],
                'area' => $arrayColumns[70],
                'ano_conclusao' => $arrayColumns[71],
            ];
        }

        if (! empty($arrayColumns[72])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[72],
                'area' => $arrayColumns[73],
                'ano_conclusao' => $arrayColumns[74],
            ];
        }

        if (! empty($arrayColumns[75])) {
            $this->posGraduacoes[] = [
                'tipo' => $arrayColumns[75],
                'area' => $arrayColumns[76],
                'ano_conclusao' => $arrayColumns[77],
            ];
        }

        $this->posGraduacaoNaoPossui = $arrayColumns[78];
        $this->formacaoContinuadaCreche = $arrayColumns[79];
        $this->formacaoContinuadaPreEscola = $arrayColumns[80];
        $this->formacaoContinuadaAnosIniciaisFundamental = $arrayColumns[81];
        $this->formacaoContinuadaAnosFinaisFundamental = $arrayColumns[82];
        $this->formacaoContinuadaEnsinoMedio = $arrayColumns[83];
        $this->formacaoContinuadaEducacaoJovensAdultos = $arrayColumns[84];
        $this->formacaoContinuadaEducacaoEspecial = $arrayColumns[85];
        $this->formacaoContinuadaEducacaoIndigena = $arrayColumns[86];
        $this->formacaoContinuadaEducacaoCampo = $arrayColumns[87];
        $this->formacaoContinuadaEducacaoAmbiental = $arrayColumns[88];
        $this->formacaoContinuadaEducacaoDireitosHumanos = $arrayColumns[89];
        $this->formacaoContinuadaEducacaoBilingueSurdos = $arrayColumns[90];
        $this->formacaoContinuadaEducacaoTecnologiaInformacaoComunicacao = $arrayColumns[91];
        $this->formacaoContinuadaGeneroDiversidadeSexual = $arrayColumns[92];
        $this->formacaoContinuadaDireitosCriancaAdolescente = $arrayColumns[93];
        $this->formacaoContinuadaEducacaoRelacoesEticoRaciais = $arrayColumns[94];
        $this->formacaoContinuadaEducacaoGestaoEscolar = $arrayColumns[95];
        $this->formacaoContinuadaEducacaoOutros = $arrayColumns[96];
        $this->formacaoContinuadaEducacaoNenhum = $arrayColumns[97];
        $this->email = $arrayColumns[98];

        if ($this->escolaridade) {
            $this->tipos[self::TIPO_TEACHER] = true;
            $this->tipos[self::TIPO_MANAGER] = true;
        } else {
            $this->tipos[self::TIPO_STUDENT] = true;
        }
    }
}
