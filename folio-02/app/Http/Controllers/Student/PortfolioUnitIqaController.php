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

class PortfolioUnitIqaController extends Controller
{

    public function showIqaHistory(Student $student, TrainingRecord $training, PortfolioUnit $unit)
    {
        $latest_iqa = $unit->iqa()->latest()->first();
        $accepted_pcs_in_last_assessment = explode(",", $latest_iqa->accepted_pcs);
        $rejected_pcs_in_last_assessment = explode(",", $latest_iqa->rejected_pcs);

        return view('trainings.unit_iqa_history',
            compact('student', 'training', 'unit', 'accepted_pcs_in_last_assessment', 'rejected_pcs_in_last_assessment', 'latest_iqa'));
    }

    public function storeIqaUnitReply(Request $request, Student $student, TrainingRecord $training, PortfolioUnit $unit)
    {
        $request->validate([
            'comments' => 'required|min:50|max:2000',
        ]);

        abort_if($unit->id != $request->portfolio_unit_id, 403);

        $latest_iqa = $unit->iqa()->latest()->first();

        $port_folio_unit_iqa = new PortfolioUnitIqa([
            'id' => null,
            'portfolio_unit_id' => $unit->id,
            'accepted_pcs' => $latest_iqa->accepted_pcs,
            'rejected_pcs' => $latest_iqa->rejected_pcs,
            'comments' => $request->comments,
            'user_id' => \Auth::user()->id,
        ]);

        $port_folio_unit_iqa->save();

        $notification = new Notification();
        $notification->notifier_id = $training->verifier;
        $notification->actor_id = \Auth::user()->id;
        $notification->type = 1;
        $notification->checked = 0;
        $notification->detail = "Assessor has replied to your assessment for the following learner.<br>";
        $notification->detail .= "Learner Name: <strong>{$training->student->full_name}</strong><br>";
        $notification->detail .= "Unit: <strong>{$unit->title}</strong>";
        $unit->notifications()->save($notification);


        return redirect()->route('trainings.show', $training);
    }

    public function showUnitForEqa(Student $student, TrainingRecord $training, PortfolioUnit $unit)
    {
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
            compact('student', 'training', 'unit', 'distinct_evidences', 'accepted_pcs_in_last_assessment', 'rejected_pcs_in_last_assessment'));
    }

}
