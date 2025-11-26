<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\ReportHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class ViewReportController extends Controller
{
    public function showDrillDown(Request $request)
    {
        if(auth()->user()->isStudent())
        {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $trainings = collect();

        if( $request->report_type == 1 )
        {
            $query = ReportHelper::continuingLearnersWithEvidencesToAssess(auth()->user(), false);
            $query = $query->with(['student', 'student.latestAuth'])
                ->orderBy(
                    DB::raw("(SELECT firstnames FROM users WHERE users.id = tr.student_id)"),
                    'asc'
                );

        }

        if( $request->report_type == 2 )
        {
            $query = ReportHelper::continuingLearnersNotLoggedIn(auth()->user(), 30);
            $query = $query->with(['student', 'student.latestAuth'])
                ->withCount([
                    'evidences as evidence_count' => function ($query) {
                        $query->where('evidence_status', TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED);
                    }
                ])
                ->orderBy(
                    DB::raw("(SELECT firstnames FROM users WHERE users.id = tr.student_id)"),
                    'asc'
                );
        }



        $trainings = $query->get();

        return view('reports.drilldown', compact('trainings'));
    }
}
