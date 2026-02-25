<?php

namespace iEducar\Packages\Educacenso\Services\Version2025;

use App\Models\Educacenso\RegistroEducacenso;
use iEducar\Modules\Educacenso\Model\AbastecimentoAgua;
use iEducar\Modules\Educacenso\Model\AcoesAmbientais;
use iEducar\Modules\Educacenso\Model\AreasExternas;
use iEducar\Modules\Educacenso\Model\InstrumentosPedagogicos;
use iEducar\Modules\Educacenso\Model\RecursosAcessibilidade;
use iEducar\Modules\Educacenso\Model\SalasAtividades;
use iEducar\Packages\Educacenso\Services\Version2022\LegacySchool;
use iEducar\Packages\Educacenso\Services\Version2024\Registro10Import as Registro10Import2024;
use iEducar\Packages\Educacenso\Services\Version2025\Models\Registro10Model;

class Registro10Import extends Registro10Import2024
{
    public function import(RegistroEducacenso $model, $year, $user): void
    {
        parent::import($model, $year, $user);

        $schoolInep = parent::getSchool();

        if (empty($schoolInep)) {
            return;
        }

        /** @var LegacySchool $school */
        $school = $schoolInep->school;
        $model = $this->model;

        $school->numero_salas_cantinho_leitura = $model->numeroSalasCantinhoLeitura ?: null;

        $school->save();
    }

    public static function getModel($arrayColumns)
    {
        $registro = new Registro10Model();
        $registro->hydrateModel($arrayColumns);

        return $registro;
    }

    protected function getArraySalasAtividades()
    {
        $salasAtividades = parent::getArraySalasAtividades();
        $arraySalas = \transformStringFromDBInArray($salasAtividades) ?: [];

        if ($this->model->dependenciaSalaEstudioGravacaoEdicao) {
            $arraySalas[] = SalasAtividades::ESTUDIO_GRAVACAO_EDICAO;
        }

        return parent::getPostgresIntegerArray($arraySalas);
    }

    protected function getArrayAbastecimentoAgua()
    {
        $arrayAbastecimentoAgua = parent::getArrayAbastecimentoAgua();

        $arrayAbastecimento = \transformStringFromDBInArray($arrayAbastecimentoAgua) ?: [];

        if ($this->model->aguaCarroPipa) {
            $arrayAbastecimento[] = AbastecimentoAgua::CARRO_PIPA;
        }

        return $this->getPostgresIntegerArray($arrayAbastecimento);
    }

    protected function getArrayRecursosAcessibilidade()
    {
        $arrayRecursosAcessibilidade = parent::getArrayRecursosAcessibilidade();

        $arrayRecursos = \transformStringFromDBInArray($arrayRecursosAcessibilidade) ?: [];

        if ($this->model->recursoSinalizacaoLuminosa) {
            $arrayRecursos[] = RecursosAcessibilidade::SINALIZACAO_LUMINOSA;
        }

        return $this->getPostgresIntegerArray($arrayRecursos);
    }

    protected function getArrayInstrumentosPedagogicos()
    {
        $instrumentos = parent::getArrayInstrumentosPedagogicos();
        $arrayInstrumentos = \transformStringFromDBInArray($instrumentos) ?: [];

        if ($this->model->instrumentosPedagogicosAreaHorta) {
            $arrayInstrumentos[] = InstrumentosPedagogicos::MATERIAIS_AREA_HORTA;
        }

        if ($this->model->instrumentosPedagogicosEducacaoQuilombola) {
            $arrayInstrumentos[] = InstrumentosPedagogicos::MATERIAL_EDUCACAO_QUILOMBOLA;
        }

        if ($this->model->instrumentosPedagogicosEducacaoEspecial) {
            $arrayInstrumentos[] = InstrumentosPedagogicos::MATERIAL_EDUCACAO_ESPECIAL;
        }

        return parent::getPostgresIntegerArray($arrayInstrumentos);
    }

    protected function getArrayAcoesAreaAmbiental()
    {
        $arrayAcoesAreaAmbiental = [];

        if ($this->model->acaoConteudoComponente) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::CONTEUDO_COMPONENTE;
        }

        if ($this->model->acaoConteudoCurricular) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::CONTEUDO_CURRICULAR;
        }

        if ($this->model->acaoEixoCurriculo) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::EIXO_CURRICULO;
        }

        if ($this->model->acaoEventos) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::EVENTOS;
        }

        if ($this->model->acaoProjetoInterdisciplinares) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::PROJETOS_INTERDISCIPLINARES;
        }

        if ($this->model->acaoAmbientalNenhuma) {
            $arrayAcoesAreaAmbiental[] = AcoesAmbientais::NENHUMA_DAS_ACOES_LISTADAS;
        }

        return $this->getPostgresIntegerArray($arrayAcoesAreaAmbiental);
    }

    protected function getArrayAreasExternas()
    {
        $arrayAreasExternas = parent::getArrayRecursosAcessibilidade();

        $arrayAreas = \transformStringFromDBInArray($arrayAreasExternas) ?: [];

        if ($this->model->dependenciaAreaHorta) {
            $arrayAreas[] = AreasExternas::HORTA;
        }

        return $this->getPostgresIntegerArray($arrayAreas);
    }
}
