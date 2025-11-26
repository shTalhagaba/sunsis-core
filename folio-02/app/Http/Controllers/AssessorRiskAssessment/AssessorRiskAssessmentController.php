<?php

namespace App\Http\Controllers\AssessorRiskAssessment;

use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Models\AssessorRiskAssessment\AssessorRiskAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessorRiskAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index()
    {
        $this->authorize('index', AssessorRiskAssessment::class);

        $query = AssessorRiskAssessment::query()
            ->with(['assessor', 'creator']);

        if(auth()->user()->isVerifier())
        {
            $query->where('created_by', auth()->id())
                ->orWhere('completed_by', auth()->id());
        }
        elseif(auth()->user()->isAssessor())
        {
            $query->where('assessor_id', auth()->id())
                ->where('completed', 1);
        }

        $records = $query->paginate(session('ara_per_page', config('model_filters.default_per_page')));

        return view('assessor_risk_assessment.index', compact('records'));
    }

    public function show(AssessorRiskAssessment $riskAssessment )
    {
        $this->authorize('show', $riskAssessment );

        $grades = AssessorRiskAssessment::getGradesList();

        $form = !is_null($riskAssessment->detail) ? json_decode($riskAssessment->detail, true) : [];

        return view('assessor_risk_assessment.show', compact('riskAssessment', 'grades', 'form'));
    }

    public function create()
    {
        $this->authorize('create', AssessorRiskAssessment::class);

        $assessorList = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        return view('assessor_risk_assessment.create', compact('assessorList'));
    }

    public function edit(AssessorRiskAssessment $riskAssessment)
    {
        $this->authorize('edit', $riskAssessment);

        $assessorList = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        return view('assessor_risk_assessment.edit', compact('riskAssessment', 'assessorList'));
    }

    public function store(AssessorRiskAssessment $riskAssessment, Request $request)
    {
        $this->authorize('create', AssessorRiskAssessment::class);

        $data = $request->validate([
            'assessor_id' => 'required|exists:users,id',
            'date_of_observation' => 'required|date',
            'date_of_last_observation' => 'nullable|date',
        ]);

        $data['created_by'] = auth()->id();

        $riskAssessment = AssessorRiskAssessment::create($data);

        return redirect()
            ->route('assessor_risk_assessment.show', $riskAssessment)
            ->with('success', 'Assessor Risk Assessment created successfully.');
    }

    public function update(Request $request, AssessorRiskAssessment $riskAssessment)
    {
        $this->authorize('edit', $riskAssessment);

        $data = $request->validate([
            'assessor_id' => 'required|exists:users,id',
            'date_of_observation' => 'required|date',
        ]);

        $riskAssessment->update($data);

        return redirect()
            ->route('assessor_risk_assessment.show', $riskAssessment)
            ->with('success', 'Assessor Risk Assessment updated successfully.');
    }

    public function destroy(AssessorRiskAssessment $riskAssessment)
    {
        $this->authorize('destroy', $riskAssessment);

        $riskAssessment->delete();

        return redirect()
            ->route('assessor_risk_assessment.index')
            ->with('success', 'Assessor Risk Assessment deleted successfully.');
    }

    public function editForm(AssessorRiskAssessment $riskAssessment)
    {
        $this->authorize('edit', $riskAssessment);

        $form = !is_null($riskAssessment->detail) ? json_decode($riskAssessment->detail, true) : [];

        $grades = AssessorRiskAssessment::getGradesList();

        return view('assessor_risk_assessment.assessment_form', compact('riskAssessment', 'form', 'grades'));
    }

    public function saveForm(Request $request, AssessorRiskAssessment $riskAssessment)
    {
        $this->authorize('edit', $riskAssessment);

        $totalScore = 0;
        foreach($request->except(['_token', '_method', 'completed']) as $key => $value) 
        {
            if (strpos($key, 'grade_') !== -1) 
            {
                $totalScore += (int)$value;
            }
        }
        $overallGrade = AssessorRiskAssessment::getOverallGrade($totalScore);

        $riskAssessment->update([
            'detail' => json_encode($request->except(['_token', '_method'])),
            'completed_by' => $request->completed == '1' ? auth()->id() : null,
            'completion_date' => $request->completed == '1' ? now()->format('Y-m-d') : null,
            'total_score' => $totalScore,
            'overall_grade' => $overallGrade,
            'completed' => $request->completed == '1',
        ]);

        return redirect()
            ->route('assessor_risk_assessment.show', $riskAssessment)
            ->with('success', 'Assessor Risk Assessment updated successfully.');
    }
}