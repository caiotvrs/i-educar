<?php

namespace App\Http\Controllers;

require_once 'Reports/Tipos/TipoBoletim.php';

use App\Http\Requests\UpdateSchoolClassReportCardRequest;
use App\Models\LegacySchoolClass;
use App\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Portabilis_Model_Report_TipoBoletim;

class UpdateSchoolClassReportCardController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $this->breadcrumb('Alterar tipo de boletim de turmas', [
            url('intranet/educar_configuracoes_index.php') => 'Configurações',
        ]);

        $this->menu(Process::UPDATE_SCHOOL_CLASS_REPORT_CARD);

        return view('classroom.update-school-class-report-card.index', [
            'user' => $request->user(),
            'reportCards' => Portabilis_Model_Report_TipoBoletim::getInstance()->getEnums(),
        ]);
    }

    /**
     *
     * @param UpdateSchoolClassReportCardRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSchoolClassReportCardRequest $request)
    {
        $query = LegacySchoolClass::query();

        $classes = $this->addFilters($request, $query);

        if (count($classes) == 0) {
            return redirect()->route('update-school-class-report-card.index')->with('error', 'Nenhuma turma encontrada com os filtros selecionados');
        }

        if (empty($request->get('confirmation'))) {
            return redirect()->route('update-school-class-report-card.index')->withInput()->with('show-confirmation', ['count' => count($classes)]);
        }

        DB::beginTransaction();

        $result = [];

        foreach ($classes as $key => $schoolClass) {
            $result[$key] = [
                'name' => $schoolClass->name,
                'old_report' => $schoolClass->tipo_boletim,
                'new_report' => $request->get('new_report_card'),
            ];

            $schoolClass->tipo_boletim = $request->get('new_report_card');

            if ($request->get('new_alternative_report_card')) {
                $result[$key]['old_alternative_report'] = $schoolClass->tipo_boletim_diferenciado;
                $result[$key]['new_alternative_report'] = $request->get('new_alternative_report_card');

                $schoolClass->tipo_boletim_diferenciado = $request->get('new_alternative_report_card');
            }

            $schoolClass->save();
        }

        //DB::commit();

        return redirect()->route('update-school-class-report-card.index')
            ->with('success', count($classes) . ' turmas atualizadas com sucesso.')
            ->with('classrooms', $result);
    }

    private function addFilters(UpdateSchoolClassReportCardRequest $request, $query)
    {
        if ($request->get('ano')) {
            $query->where('ano', $request->get('ano'));
        }

        if ($request->get('ref_cod_escola')) {
            $query->where('ref_ref_cod_escola', $request->get('ref_cod_escola'));
        }

        if ($request->get('ref_cod_curso')) {
            $query->where('ref_cod_curso', $request->get('ref_cod_curso'));
        }

        if ($request->get('ref_cod_serie')) {
            $query->where('ref_ref_cod_serie', $request->get('ref_cod_serie'));
        }

        if ($request->get('ref_cod_turma')) {
            $query->whereIn('cod_turma', $request->get('ref_cod_turma'));
        }

        if ($request->get('old_report_card')) {
            $query->where('tipo_boletim', $request->get('old_report_card'));
        }

        return $query->orderBy('nm_turma')->get();
    }
}
