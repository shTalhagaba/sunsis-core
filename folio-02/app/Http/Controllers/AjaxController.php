<?php

namespace App\Http\Controllers;

use App\Models\IQA\IqaSamplePlan;
use App\Models\Lookups\UserTypeLookup;
use App\Models\MediaSection;
use App\Models\Organisations\Location;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeDeliveryPlanSession;
use App\Models\Programmes\ProgrammeDeliveryPlanSessionTask;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use App\Models\Qualifications\Qualification;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\PortfolioUnitIqa;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use App\Models\UserEvents\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;

class AjaxController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth', 'is_staff'])->except(['saveTabInSession']);
    }

    public function getIPGeoLocationFromIPStackDotCom(Request $request)
    {
        if (!isset($request->ip) || is_null($request->ip))
            return false;

        $client = new \GuzzleHttp\Client();
        $request = $client->get('http://api.ipstack.com/' . $request->ip . '?access_key=' . config('app.ipstack_key') . '');
        $response = $request->getBody()->getContents();

        echo $response;
    }

    public function updateUnitAssessmentStatus(Request $request)
    {
        $tr = TrainingRecord::findOrFail($request->training_record);
        $portfolio_unit = PortfolioUnit::findOrFail($request->unit_id);
        $portfolio_unit->update([
            'assessment_complete' => $request->assessment_complete == 1 ? 1 : 0,
        ]);

        $totalUnits = 0;
        $acUnits = $tr->units->where('assessment_complete', 1)->count();
        // if($acUnits > 1)
        // {
        //     $totalUnits = $tr->units->count();
        //     $signedOffUnits = 0;
        //     foreach($tr->units AS $unit)
        //     {
        //         if($unit->isSignedOff())
        //         {
        //             $signedOffUnits++;
        //         }
        //     }
        //     if($signedOffUnits+$acUnits == $totalUnits)
        //     {
        //         $tr->update([
        //             'status_code' => TrainingRecord::STATUS_ASSESSMENT_COMPLETED,
        //         ]);
        //     }
        // }

        return response()->json([
            'success' => true,
            'code' => Response::HTTP_OK,
            'ac_units_count' => $acUnits,
            'totalUnits' => $totalUnits,
        ]);
    }

    public function getOrganisationLocation(Request $request)
    {
        abort_if(auth()->user()->isStudent(), Response::HTTP_UNAUTHORIZED);

        $location = Location::findOrFail($request->location_id);
        return response()->json($location, 200);
    }

    public function saveTabInSession(Request $request)
    {
        if($request->has('screen') && $request->has('selectedTab'))
        {
            session()->put($request->screen . '_tab', $request->selectedTab);
        }
    }

    public function createMediaSection(Request $request)
    {
        $request->validate([
            'model_id' => 'required|numeric',
            'model_type' => 'required|string|max:255',
            'new_media_section_name' => 'required|max:70',
        ]);

        $modelType = $request->model_type;
        $modelId = $request->model_id;
        $entity = $modelType::findOrFail($modelId);

        $modelType = new ReflectionClass($modelType);

        $mediaSection = MediaSection::create([
            'name' => $request->new_media_section_name,
            'type' => $modelType->getShortName(),
            'slug' => \Str::slug($request->new_media_section_name),
        ]);
        $entity->mediaSections()->attach($mediaSection);

        return back()->with(['alert-success' => 'Section is created successfully']);
    }

    public function createSelectOption(Request $request)
    {
        $request->validate([
            'newOption' => 'required|string|max:50',
            'optionTable' => 'required|string|max:50',
        ]);

        $newOptionDescription = $request->newOption;
        $optionTable = $request->optionTable;

        $newOptionId = DB::table($optionTable)
            ->insertGetId([
                'description' => trim(preg_replace('/\s\s+/', ' ', $newOptionDescription))
            ]);

        $newRow = DB::table($optionTable)->where('id', $newOptionId)->first();

        return response()->json([
            'value' => $newRow->id,
            'text' => $newRow->description,
        ]);
    }

    public function getProgrammeQualificationsForIqaSample(Request $request)
    {
        $validProgrammeIds = Programme::pluck('id')->implode(',');
        $request->validate([
            'programme_id' => 'required|numeric|in:' . $validProgrammeIds,
            'plan_id' => 'nullable|numeric',
        ]);

        $programme = Programme::findOrFail($request->programme_id);

        $data = $programme->qualifications()->select(DB::raw("CONCAT('[', qan, '] ', title) AS qual_title"), 'id')->pluck('qual_title', 'id');
	    if(!empty($request->plan_id))
        {
            $plan = IqaSamplePlan::findOrFail($request->plan_id);
            $existingQuals = $plan->qualifications()->pluck('qan')->toArray();
            $data = $programme
                ->qualifications()
                ->select(DB::raw("CONCAT('[', qan, '] ', title) AS qual_title"), 'id')
                ->whereNotIn('qan', $existingQuals)
                ->pluck('qual_title', 'id');
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function searchParticipantsForEvent(UserEvent $event, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|numeric|in:' . $event->id,
            'participant_name' => 'required|string|max:70',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        if($event->user_id !== auth()->user()->id)
        {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request parameters'
            ], Response::HTTP_BAD_REQUEST);
        }

        $participantName = $request->participant_name;
        $query = User::where(function ($q) use ($participantName) {
            return $q->where('firstnames', 'LIKE', '%' . $participantName . '%')
                ->orWhere('surname', 'LIKE', '%' . $participantName . '%')
                ->orWhere(DB::raw('CONCAT(firstnames, " ", surname)'), 'LIKE', '%' . $participantName . '%');
        })
            ->leftjoin('tr', 'users.id', '=', 'tr.student_id')
            ->select([
                'users.id AS user_id',
                'tr.id AS tr_id',
                'users.firstnames',
                'users.surname',
                'users.user_type',
                DB::raw('(SELECT lookup_user_types.description FROM lookup_user_types WHERE lookup_user_types.id = users.user_type) AS user_type_description')
            ])
            ->distinct('users.id')
            ->where('users.id', '!=', $event->user_id)
            ;

        switch( auth()->user()->user_type )
        {
            case UserTypeLookup::TYPE_ADMIN:
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $query->where(function ($q) {
                    $q->where('tr.primary_assessor', '=', auth()->user()->id)
                        ->orWhere('tr.secondary_assessor', '=', auth()->user()->id);
                });
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $tutor = auth()->user();
                $trIds = DB::table('portfolios')->where('fs_tutor_id', $tutor->id)->pluck('tr_id')->toArray();

                $query->where(function ($q) use ($trIds, $tutor) {
                    $q->where('tr.tutor', '=', $tutor->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $verifier = auth()->user();
                $trIds = DB::table('portfolios')->where('fs_verifier_id', $verifier->id)->pluck('tr_id')->toArray();

                $query->where(function ($q) use ($trIds, $verifier) {
                    $q->where('tr.verifier', '=', $verifier->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            default:
                $query->where('users.id', false);
                break;
        }

        $existingIds = $event->participants()->pluck('user_id')->toArray();
        if(count($existingIds) > 0)
        {
            $query->whereNotIn('users.id', $existingIds);
        }

        $users = $query->get()->transform(function ($user) {
            $user->user_id = encrypt($user->user_id);
            $user->tr_id = !is_null($user->tr_id) ? encrypt($user->tr_id) : '';
            return $user;
        });

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function calcualteEndDate(Request $request)
    {
        $programmeId = $request->input('programme_id');
        $startDate = $request->input('start_date');
        if( $programmeId != '' && ($startDate != '' && \Carbon\Carbon::createFromFormat('Y-m-d', $startDate) !== false) )
        {
            $programme = Programme::find($programmeId);
            if(!is_null($programme))
            {
                if(is_int($programme->duration))
                {
                    $plannedEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->addMonths($programme->duration);
                    if(is_int($programme->epa_duration))
                    {
                        $epaDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->addMonths($programme->duration + $programme->epa_duration);
                    }
                }
            }
        }

        return response()->json([
            'planned_end_date' => isset($plannedEndDate) ? $plannedEndDate->format('Y-m-d') : '',
            'epa_date' => isset($epaDate) ? $epaDate->format('Y-m-d') : '',
        ]);
    }

    public function getProgrammeDeliveryPlanSessionTemplate(Request $request)
    {
        $templateSessionId = $request->input('templateSessionId');
        $trainingId = $request->input('trainingId');

        $templateSession = ProgrammeDeliveryPlanSession::find($templateSessionId);
        if (!$templateSession) 
        {
            return response()->json([
                'error' => 'Template session not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $pcsOfTrainingRecord = DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $trainingId)
            ->get(['portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.system_code']);

        $result = [
            'session_number' => $templateSession->session_number,
            'session_details_1' => $templateSession->session_details_1,
            'session_details_2' => $templateSession->session_details_2,
            'session_planned_hours' => $templateSession->session_planned_hours,
            'pc_ids' => [],
        ];

        $decodedPcs = json_decode($templateSession->session_pcs);
        if (!is_array($decodedPcs)) 
        {
            $templateSessionPcs = collect([]);
        } 
        else 
        {
            $templateSessionPcs = ProgrammeQualificationUnitPC::whereIn('id', $decodedPcs)
                ->orderBy('pc_sequence')
                ->get();
        }

        foreach ($templateSessionPcs as $templateSessionPc) 
        {
            $trainingPc = $pcsOfTrainingRecord->where('system_code', $templateSessionPc->system_code)->first();
            // $result['pc_ids'][] = [
            //     'tr_pc_id' => optional($trainingPc)->id,
            //     'system_code' => optional($trainingPc)->system_code,
            //     'sequence' => $templateSessionPc->pc_sequence,
            //     'pc_title' => $templateSessionPc->title,
            //     'delivery_hours' => $templateSessionPc->delivery_hours,
            // ];
            $result['pc_ids'][] = optional($trainingPc)->id;
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function getProgrammeDeliveryPlanSessionTaskTemplate(Request $request)
    {
        $templateTaskId = $request->input('templateTaskId');
        $trainingId = $request->input('trainingId');

        $templateTask = ProgrammeDeliveryPlanSessionTask::find($templateTaskId);
        if (!$templateTask) {
            return response()->json([
                'error' => 'Template session not found.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $pcsOfTrainingRecord = DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $trainingId)
            ->get(['portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.system_code']);

        $result = [
            'title' => $templateTask->title,
            'details' => $templateTask->details,
            'pc_ids' => [],
        ];

        $templateTaskPcs = $templateTask->pcs;


        foreach ($templateTaskPcs as $templateTaskPc) {
            $trainingPc = $pcsOfTrainingRecord->where('system_code', $templateTaskPc->system_code)->first();
            $result['pc_ids'][] = optional($trainingPc)->id;
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function showAlsTabToEmployer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tr_id' => 'required|numeric',
            'show' => 'required|boolean',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        $training = TrainingRecord::findOrFail($request->tr_id);
        $training->update([
            'show_als_tab_to_employer' => !$training->show_als_tab_to_employer,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Changes saved successfully.',
        ], Response::HTTP_OK);
    }

    public function updateTrainingEvidenceIqaCheckStatus(Request $request)
    {
        if(!auth()->user()->isStaff())
        {
            return;
        }

        $validator = Validator::make($request->all(), [
            'tr_evidence_id' => 'required|numeric',
            'iqa_plan_id' => 'nullable|numeric',
            'checked' => 'required|boolean',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::table('tr_evidence_iqa_checked_status')->updateOrInsert(
            ['tr_evidence_id' => $request->tr_evidence_id, 'user_id' => auth()->user()->id],
            [
                'iqa_plan_id' => $request->input('iqa_plan_id'),
                'checked' => $request->input('checked', 0)
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Changes saved successfully.',
        ], Response::HTTP_OK);
    }

    public function getVerifierLinkedAssessors($verifierId)
    {
        $assessorIds = TrainingRecord::where('verifier', $verifierId)
            ->pluck('primary_assessor', 'secondary_assessor')
            ->flatMap(function ($primary, $secondary) {
                return [$primary, $secondary];
            })
            ->filter()
            ->unique()
            ->values();

        if ($assessorIds->isEmpty()) {
            return response()->json([]);
        }

        $assessors = User::whereIn('id', $assessorIds)
            ->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->pluck('name', 'id');

        return response()->json($assessors);
    }

    public function getVerifierAndAssessorLinkedQualifications(Request $request)
    {
        $trIds = TrainingRecord::where('verifier', $request->verifier_id)
            ->where(function ($query) use ($request) {
                $query->where('primary_assessor', $request->assessor_id)
                    ->orWhere('secondary_assessor', $request->assessor_id);
            })
            ->pluck('id');
        if ($trIds->isEmpty()) {
            return response()->json([]);
        }

        $qanList = Portfolio::whereIn('tr_id', $trIds)
            ->distinct()
            ->pluck('qan');

        $qualifications = Qualification::whereIn('qan', $qanList)
            ->where('status', 1)
            ->select(DB::raw("CONCAT(qan, ' ', title) AS qual_title"), "id")
            ->orderBy('qual_title')
            ->pluck('qual_title', 'id');

        return response()->json($qualifications);
    }

    public function showPortfolioUnitHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TrainingID' => 'required|numeric',
            'portfolio_unit_id' => 'required|numeric',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        $portfolioUnit = PortfolioUnit::findOrFail($request->unit_id);
        $history = $portfolioUnit->iqa()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    public function getIqaNotes($trainingId, $portfolioUnitId)
    {
        $trainingRecord = TrainingRecord::with('student')
            ->findOrFail($trainingId);

        $learnerName = $trainingRecord->student->full_name ?? 'Unknown Learner';

        $portfolioUnit = PortfolioUnit::findOrFail($portfolioUnitId);

        $notes = PortfolioUnitIqa::where('portfolio_unit_id', $portfolioUnitId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('iqav2.partials.iqa_notes', compact('notes', 'learnerName', 'portfolioUnit'))->render();
    }
}	
