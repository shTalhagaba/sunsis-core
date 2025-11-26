<?php

namespace App\Http\Controllers\Training\Portfolios;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingPortfolioRequest;
use App\Models\LookupManager;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Training\Portfolio;
use App\Models\Training\TrainingRecord;
use App\Services\Students\Enrolment\EnrolmentService;
use Illuminate\Support\Facades\DB;

class TrainingPortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('create', [Portfolio::class, $training]);

        $availableQualifications = $training->programme
            ->qualifications()
            ->whereNotIn(
                'qan', $training->portfolios()->pluck('qan')->toArray()
            )
            ->with('units.pcs')
            ->get();

	    $tutors = LookupManager::getTutors();

        $verifiers = LookupManager::getVerifiers();

        return view('trainings.manage_elements.add_portfolios', compact('training', 'availableQualifications', 'tutors', 'verifiers'));
    }

    public function store(TrainingRecord $training, StoreTrainingPortfolioRequest $request)
    {
        $this->authorize('create', [Portfolio::class, $training]);

        foreach ($request->qualifications as $qualification) 
        {
            $programmeQualification = ProgrammeQualification::findOrFail($qualification);

            $additionalFields = [
                'start_date' => $request->input('start_date_qual_' . $qualification),
                'planned_end_date' => $request->input('planned_end_date_qual_' . $qualification),
                'fs_tutor_id' => $request->input('tutor_qual_' . $qualification),
                'fs_verifier_id' => $request->input('verifier_qual_' . $qualification),
            ];

            $portfolio = (new EnrolmentService)->addPortfolio($training, $programmeQualification, $request->chkUnit, $additionalFields);
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => \Str::plural('Portfolio', count($request->qualifications)) . ' have been added into the training record.']);
    }

    public function edit(TrainingRecord $training, Portfolio $portfolio)
    {
        $this->authorize('edit', [$portfolio, $training]);

        $subaction = request()->input('subaction', 'add_elements');
        $subaction = in_array($subaction, ["add_elements", "remove_elements"]) ? $subaction : "add_elements";

        $portfolio->load([
            'units',
            'units.pcs'
        ]);

        $view = 'trainings.manage_elements.add_portfolio_elements';
        if($subaction == 'remove_elements')
        {
            $view = 'trainings.manage_elements.remove_portfolio_elements';
        }

        $unitsSystemCodes = DB::table('portfolio_units')
            ->where('portfolio_id', $portfolio->id)
            ->pluck('system_code')
            ->toArray();

        $pcsSystemCodes = DB::table('portfolio_pcs')
            ->whereIn('portfolio_unit_id', $portfolio->units()->pluck('id')->toArray())
            ->pluck('system_code')
            ->toArray();

        $programmeQualification = ProgrammeQualification::where('programme_id', $training->programme_id)
            ->where('qan', $portfolio->qan)
            ->with(['units', 'units.pcs'])
            ->first();

        return view($view, compact('training', 'portfolio', 'programmeQualification', 'unitsSystemCodes', 'pcsSystemCodes'));
    }

    public function destroy(TrainingRecord $training, Portfolio $portfolio)
    {
        $this->authorize('delete', [$portfolio, $training]);

        $mappings = DB::table('pc_evidence_mappings')
            ->join('tr_evidences', 'tr_evidences.id', '=', 'pc_evidence_mappings.tr_evidence_id')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $portfolio->id)
            ->count();

        if($mappings > 0)
        {
            return response()->json([
                'success' => false,
                'message' => 'This portfolio one or more mapped evidences, it cannot be deleted.'
            ]);
        }

        if($training->portfolios()->count() == 1)
        {
            return response()->json([
                'success' => false,
                'message' => 'This is only portfolio in this training record, it cannot be deleted. You can add another and remove this.'
            ]);
        }

        $portfolio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Portfolio has been deleted.',
        ]);
    }
}