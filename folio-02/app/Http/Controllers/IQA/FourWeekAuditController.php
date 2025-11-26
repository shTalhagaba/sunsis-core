<?php

namespace App\Http\Controllers\IQA;

use App\Http\Controllers\Controller;
use App\Models\IQA\FourWeekAudit;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FourWeekAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function show(TrainingRecord $training, FourWeekAudit $audit)
    {
        $questions = DB::table('lookup_questions_four_week_audit_form')->where('version', 'V2 March 25')->orderBy('order_pos')->get();
        $formData = !is_null($audit->form_data) ? json_decode($audit->form_data) : null;
        $users = User::staffUsers()->get();
        $usersNames = [];
        foreach ($users as $user) 
        {
            $usersNames[$user->id] = $user->full_name;
        }

        return view('trainings.iqa.four_week_audit.show', compact('training', 'audit', 'questions', 'formData', 'usersNames'));
    }

    private function getUsersList()
    {
        $usersList = User::select(['firstnames', 'surname', 'user_type', 'id'])
            ->staffUsers()
            ->withActiveAccess()
            ->orderBy('user_type')
            ->orderBy('firstnames');
        $usersCategorisedList = [];
        foreach ($usersList->get() as $user) 
        {
            $userTypeDesc = UserTypeLookup::getDescription($user->user_type);
            $userTypeDesc = Str::title(strtolower(str_replace('_', ' ', $userTypeDesc)));
            if (!isset($usersCategorisedList[$userTypeDesc])) 
            {
                $usersCategorisedList[$userTypeDesc] = [];
            }
            $usersCategorisedList[$userTypeDesc][$user->id] = $user->full_name;
        }

        return $usersCategorisedList;
    }
    public function create(TrainingRecord $training)
    {
        $audit = $training->four_week_audit ?? new FourWeekAudit();
        $questions = DB::table('lookup_questions_four_week_audit_form')->where('version', 'V2 March 25')->orderBy('order_pos')->get();
        $formData = !is_null($audit->form_data) ? json_decode($audit->form_data) : null;
        $usersCategorisedList = $this->getUsersList();

        return view('trainings.iqa.four_week_audit.create', compact('training', 'audit', 'questions', 'formData', 'usersCategorisedList'));
    }

    public function store(TrainingRecord $training, Request $request)
    {
        $audit = FourWeekAudit::create([
            'tr_id' => $training->id,
            'date_of_portfolio_audit' => $request->date_of_portfolio_audit,
            'form_data' => json_encode($request->except(['_token'])),
            'created_by' => auth()->user()->id,
            'iqa_signed' => $request->iqa_signed ? 1 : 0,
            'is_completed' => $request->iqa_signed ? 1 : 0,
            'completed_by_id' => $request->iqa_signed ? auth()->user()->id : null,
            'completed_by_date' => $request->iqa_signed ? now()->format('Y-m-d') : null,
        ]);

        return redirect()->route('trainings.four_week_audit.show', ['training' => $training, 'audit' => $audit])->with(['alert-success' => 'Form is saved successfully.']);
    }

    public function edit(TrainingRecord $training, FourWeekAudit $audit)
    {
        $questions = DB::table('lookup_questions_four_week_audit_form')->where('version', 'V2 March 25')->orderBy('order_pos')->get();
        $formData = !is_null($audit->form_data) ? json_decode($audit->form_data) : null;
        $usersCategorisedList = $this->getUsersList();

        return view('trainings.iqa.four_week_audit.edit', compact('training', 'audit', 'questions', 'formData', 'usersCategorisedList'));
    }

    public function update(TrainingRecord $training, FourWeekAudit $audit, Request $request)
    {
        $audit->update([
            'date_of_portfolio_audit' => $request->date_of_portfolio_audit,
            'form_data' => json_encode($request->except(['_token'])),
            'iqa_signed' => $request->iqa_signed ? 1 : 0,
            'is_completed' => $request->iqa_signed ? 1 : 0,
            'completed_by_id' => $request->iqa_signed ? auth()->user()->id : null,
            'completed_by_date' => $request->iqa_signed ? now()->format('Y-m-d') : null,
        ]);

        return redirect()->route('trainings.four_week_audit.show', ['training' => $training, 'audit' => $audit])->with(['alert-success' => 'Form is saved successfully.']);
    }
}