<?php

namespace iEducar\Modules\Educacenso\ExportRule;

use App\Models\Educacenso\Registro60;
use App\Models\Educacenso\RegistroEducacenso;
use iEducar\Modules\Educacenso\Model\TipoAtendimentoTurma;
use iEducar\Modules\Educacenso\Model\TipoMediacaoDidaticoPedagogico;

class RecebeEscolarizacaoOutroEspaco implements EducacensoExportRule
{
    /**
     * @param Registro60 $registro60
     * @return RegistroEducacenso
     */
    public static function handle(RegistroEducacenso $registro60): RegistroEducacenso
    {
        if ($registro60->tipoAtendimentoTurma != TipoAtendimentoTurma::AEE ||
            $registro60->tipoMediacaoTurma != TipoMediacaoDidaticoPedagogico::PRESENCIAL) {
            $registro60->recebeEscolarizacaoOutroEspacao = null;
        }

        return $registro60;
    }
}
