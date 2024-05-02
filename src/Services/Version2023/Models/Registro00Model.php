<?php

namespace iEducar\Packages\Educacenso\Services\Version2023\Models;

use App\Models\Educacenso\Registro00;
use iEducar\Modules\Educacenso\Model\FormasContratacaoPoderPublico;
use iEducar\Modules\Educacenso\Model\PoderPublicoConveniado;
use Illuminate\Validation\ValidationException;

class Registro00Model extends Registro00
{
    public function hydrateModel($arrayColumns): void
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        if (is_null($arrayColumns[2]) || $arrayColumns[2] === '') {
            throw ValidationException::withMessages([
                'error' => 'Você está tentando importar um arquivo com escola(s) inválida(s). o i-Educar aceita apenas arquivos oriundos do sistema do MEC.',
            ]);
        }

        $this->registro = $arrayColumns[1];
        $this->codigoInep = $arrayColumns[2];
        $this->situacaoFuncionamento = $arrayColumns[3];
        $this->inicioAnoLetivo = $arrayColumns[4];
        $this->fimAnoLetivo = $arrayColumns[5];
        $this->nome = $arrayColumns[6];
        $this->cep = $arrayColumns[7];
        $this->codigoIbgeMunicipio = $arrayColumns[8];
        $this->codigoIbgeDistrito = $arrayColumns[9];
        $this->logradouro = $arrayColumns[10];
        $this->numero = $arrayColumns[11];
        $this->complemento = $arrayColumns[12];
        $this->bairro = $arrayColumns[13];
        $this->ddd = $arrayColumns[14];
        $this->telefone = $arrayColumns[15];
        $this->telefoneOutro = $arrayColumns[16];
        $this->email = $arrayColumns[17];
        $this->orgaoRegional = $arrayColumns[18];
        $this->zonaLocalizacao = $arrayColumns[19];
        $this->localizacaoDiferenciada = $arrayColumns[20];
        $this->dependenciaAdministrativa = $arrayColumns[21];
        $this->orgaoEducacao = $arrayColumns[22];
        $this->orgaoSeguranca = $arrayColumns[23];
        $this->orgaoSaude = $arrayColumns[24];
        $this->orgaoOutro = $arrayColumns[25];
        $this->mantenedoraEmpresa = $arrayColumns[26];
        $this->mantenedoraSindicato = $arrayColumns[27];
        $this->mantenedoraOng = $arrayColumns[28];
        $this->mantenedoraInstituicoes = $arrayColumns[29];
        $this->mantenedoraSistemaS = $arrayColumns[30];
        $this->mantenedoraOscip = $arrayColumns[31];
        $this->categoriaEscolaPrivada = $arrayColumns[32];

        $this->poderPublicoConveniado = array_filter([
            $arrayColumns[33] ? PoderPublicoConveniado::ESTADUAL : null,
            $arrayColumns[34] ? PoderPublicoConveniado::MUNICIPAL : null,
            $arrayColumns[35] ? PoderPublicoConveniado::NAO_POSSUI : null,
        ]);

        $this->formasContratacaoPoderPublicoEstadual = array_filter([
            $arrayColumns[36] ? FormasContratacaoPoderPublico::TERMO_COLABORACAO : null,
            $arrayColumns[37] ? FormasContratacaoPoderPublico::TERMO_FOMENTO : null,
            $arrayColumns[38] ? FormasContratacaoPoderPublico::ACORDO_COOPERACAO : null,
            $arrayColumns[39] ? FormasContratacaoPoderPublico::CONTRATO_PRESTACAO_SERVICO : null,
            $arrayColumns[40] ? FormasContratacaoPoderPublico::TERMO_COOPERACAO_TECNICA : null,
            $arrayColumns[41] ? FormasContratacaoPoderPublico::CONTRATO_CONSORCIO : null,
        ]);

        $this->formasContratacaoPoderPublicoMunicipal = array_filter([
            $arrayColumns[42] ? FormasContratacaoPoderPublico::TERMO_COLABORACAO : null,
            $arrayColumns[43] ? FormasContratacaoPoderPublico::TERMO_FOMENTO : null,
            $arrayColumns[44] ? FormasContratacaoPoderPublico::ACORDO_COOPERACAO : null,
            $arrayColumns[45] ? FormasContratacaoPoderPublico::CONTRATO_PRESTACAO_SERVICO : null,
            $arrayColumns[46] ? FormasContratacaoPoderPublico::TERMO_COOPERACAO_TECNICA : null,
            $arrayColumns[47] ? FormasContratacaoPoderPublico::CONTRATO_CONSORCIO : null,
        ]);

        $this->cnpjMantenedoraPrincipal = $arrayColumns[48];
        $this->cnpjEscolaPrivada = $arrayColumns[49];
        $this->regulamentacao = $arrayColumns[50];
        $this->esferaFederal = $arrayColumns[51];
        $this->esferaEstadual = $arrayColumns[52];
        $this->esferaMunicipal = $arrayColumns[53];
        $this->unidadeVinculada = (int) $arrayColumns[54];
        $this->inepEscolaSede = $arrayColumns[55];
        $this->codigoIes = $arrayColumns[56];
    }
}
