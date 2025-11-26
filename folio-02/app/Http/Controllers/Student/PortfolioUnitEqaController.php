<?php

namespace App\Http\Controllers\Student;

use App\Models\Notification;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Training\PortfolioUnitEqa;

class PortfolioUnitEqaController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth']);
    }
    public function showUnitForEqa(TrainingRecord $training, PortfolioUnit $unit)
    {
        if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }

        $student = $training->student;
        $training_record = $training;

        $distinct_evidences = \DB::table('pc_evidence_mappings')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->select('pc_evidence_mappings.tr_evidence_id')
            ->distinct()
            ->where('portfolio_units.id', '=', $unit->id)
            ->get();

        $latest_iqa = $unit->iqa()->latest()->first();
        $accepted_pcs_in_last_assessment = !is_null($latest_iqa) ? explode(",", $latest_iqa->accepted_pcs) : [];
        $rejected_pcs_in_last_assessment = !is_null($latest_iqa) ? explode(",", $latest_iqa->rejected_pcs) : [];

        return view('trainings.unit_eqa',
            compact('student', 'training_record', 'unit', 'distinct_evidences', 'accepted_pcs_in_last_assessment', 'rejected_pcs_in_last_assessment', 'training'));
    }

    public function storeUnitForEqa(Request $request, TrainingRecord $training, PortfolioUnit $unit)
    {
        if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }

        $student = $training->student;
        $training_record = $training;

        $request->validate([
            'comments' => 'required|max:2000',
        ]);

        abort_if($unit->id != $request->portfolio_unit_id, 403);

        $port_folio_unit_eqa = new PortfolioUnitEqa([
            'id' => null,
            'portfolio_unit_id' => $unit->id,
            'comments' => $request->comments,
            'user_id' => \Auth::user()->id,
        ]);

        \DB::beginTransaction();
        try
        {
            $unit->save();

            $port_folio_unit_eqa->save();

            \DB::commit();
        }
        catch(\Throwable $exception)
        {
            \Session::flash('alert-danger', $exception->getMessage());
            \DB::rollBack();
            return redirect()->back();
        }

        return redirect()->route('trainings.show', $training);
    }
}
