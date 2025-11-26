<?php

namespace App\Http\Controllers\Training;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\OtjTypeLookup;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use App\Models\Training\Otj;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingDeliveryPlanSession;
use App\Models\Training\TrainingDeliveryPlanSessionKSB;
use App\Models\Training\TrainingRecord;
use App\Notifications\Otj\OtjLogCreated;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class TrainingSessionController__ extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create(TrainingRecord $training)
	{
	    $this->authorize('edit', $training);

        $elements = DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $training->id)
            // ->where('portfolios.main', true)
            // ->whereIn('portfolio_pcs.category', [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS])
            ->select('portfolio_pcs.id', 'portfolio_pcs.title', 'portfolio_pcs.delivery_hours', 'portfolio_pcs.category')
            ->orderBy('portfolio_pcs.pc_sequence')
            ->get();
        
        $selectedElements = [];    

		return view('trainings.sessions.create', compact('training', 'elements', 'selectedElements'));
    }

    // store the new session
    public function store(TrainingRecord $training, Request $request)
	{
	    $this->authorize('edit', $training);

        $session = $training->sessions()
            ->create([
                'session_number' => $request->input('session_number'),
                'session_sequence' => $request->input('session_sequence'),
                'session_details_1' => $request->input('session_details_1'),
                'session_details_2' => $request->input('session_details_2'),
            ]);

        if( is_array($request->input('elements')) && count($request->input('elements')) > 0 )
        {
            $portfolioPcs = PortfolioPC::whereIn('id', $request->input('elements'))
                ->get();
            
            foreach($portfolioPcs AS $portfolioPc)
            {
                $session->ksb()
                    ->create([
                        'sequence' => $portfolioPc->pc_sequence,
                        'pc_title' => $portfolioPc->title,
                        'delivery_hours' => $portfolioPc->delivery_hours,
                    ]);
            }
        }
        
        return redirect()->route('trainings.show', $training);
    }

    // refresh from the programme
    public function refresh(Request $request)
    {
        $validatedData = $request->validate([
            'tr_id' => 'required',
        ], [
            'tr_id.required' => 'Missing querystring argument: Training ID',
        ]);

        $tr = TrainingRecord::find($validatedData['tr_id']);

        $programme = $tr->programme;

        DB::beginTransaction();
        try 
        {
            // first remove all the sessions and ksbs
            $existingSessionIds = $tr->sessions()->pluck('id')->toArray();
            TrainingDeliveryPlanSessionKSB::whereIn('dp_session_id', $existingSessionIds)->delete();
            $tr->sessions()->delete();

            // now populate afresh
            foreach ($programme->sessions  as $programmeSession) 
            {
                $trSession = $tr->sessions()->create([
                    'session_number' => $programmeSession->session_number,
                    'session_sequence' => $programmeSession->session_sequence,
                    'session_details_1' => $programmeSession->session_details_1,
                    'session_details_2' => $programmeSession->session_details_2,
                    'session_planned_hours' => $programmeSession->session_planned_hours,
                ]);

                $programmeSessionPcs = !is_array(json_decode($programmeSession->session_pcs)) ?
                    collect([]) :
                    ProgrammeQualificationUnitPC::whereIn('id', json_decode($programmeSession->session_pcs))
                    ->orderBy('pc_sequence')
                    ->get();

                foreach ($programmeSessionPcs as $programmeSessionPc) 
                {
                    $trSession->ksb()->create([
                        'sequence' => $programmeSessionPc->pc_sequence,
                        'pc_title' => $programmeSessionPc->title,
                        'delivery_hours' => $programmeSessionPc->delivery_hours,
                    ]);
                }
            }

            DB::commit();
        } 
        catch (Exception $ex) 
        {
            DB::rollBack();
            return response()->json([
                'alert-danger' => $ex->getMessage(),
            ]);
        }

        return response()->json([
            'alert-success' => 'Delivery plan has been refreshed successfully.',
        ]);
    }

    public function showViewOrSign(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if(
            auth()->user()->isStudent() && auth()->user()->id !== $training->student_id, 
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        return view('trainings.sessions.view_or_sign', compact('training', 'session'));
    }

    // saving the form - learner and assessor signs
    public function saveViewOrSign(TrainingRecord $training, TrainingDeliveryPlanSession $session, Request $request)
    {
        abort_if(
            auth()->user()->isStudent() && auth()->user()->id !== $training->student_id, 
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            auth()->user()->isStudent() && !$training->isEditableByStudent(), 
            Response::HTTP_UNAUTHORIZED
        );

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        if(!auth()->user()->isStudent())
        {
            $request->validate([
                'actual_date' => 'required|date_format:"Y-m-d"',
                'session_start_time' => 'required|date_format:"H:i"',
                'session_end_time' => 'required|date_format:"H:i"|after:session_start_time',
                'assessor_comments' => 'required',
                'session_evidence' => ['file', 'mimes:pdf,doc,docx,txt,zip,xls,xlsx', 'max:12288']
            ], [
                'session_start_time.required' => 'Please enter a start time.',
                'session_start_time.date_format' => 'Start time must be in the format HH:MM.',
                'session_end_time.required' => 'Please enter an end time.',
                'session_end_time.date_format' => 'End time must be in the format HH:MM.',
                'session_end_time.after' => 'End time must be after the start time.',
            ]);
        }

        $session->update([
            'student_comments' => $request->input('student_comments', $session->student_comments),
            'assessor_comments' => $request->input('assessor_comments', $session->assessor_comments),
            'student_sign' => $request->input('student_sign', $session->student_sign),
            'assessor_sign' => $request->input('assessor_sign', $session->assessor_sign),
            'student_sign_date' => $request->has('student_sign') && $session->student_sign_date == '' ? now()->format('Y-m-d') : $session->student_sign_date,
            'assessor_sign_date' => $request->has('assessor_sign') && $session->assessor_sign_date == '' ? now()->format('Y-m-d') : $session->assessor_sign_date,
            'actual_date' => $request->input('actual_date', $session->actual_date),
            'session_start_time' => $request->input('session_start_time', $session->session_start_time),
            'session_end_time' => $request->input('session_end_time', $session->session_end_time),
        ]);

        if($request->has('session_evidence'))
        {
            if($session->media->count() > 0)
            {
                $session->media->first->delete();
            }

            $this->uploadOtjEvidence($request->file('session_evidence'), $session);
        }

	if($session->student_sign && $session->assessor_sign)
        {
            $this->createOtjAndNotifyLearner($training, $session);
            AppHelper::cacheUnreadCountForUser($training->student);
        }

        return redirect()
            ->route('trainings.show', $training);
    }

    private function uploadOtjEvidence(UploadedFile $sessionEvidence, TrainingDeliveryPlanSession $session)
    {
        $ext = pathinfo( trim($sessionEvidence->getClientOriginalName()), PATHINFO_EXTENSION );
        $customFileName = md5(env('APP_KEY') . now() . $session->id) . '.' . $ext;

        $session->addMediaFromRequest('session_evidence')
            ->usingFileName( $customFileName )
            ->withCustomProperties(['uploaded_by' => auth()->user()->id])
            ->toMediaCollection('session_evidences', 's3');
    }

    private function createOtjAndNotifyLearner(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        $otjDuration = null;
        if( !is_null($session->actual_date) && !is_null($session->session_start_time) && !is_null($session->session_end_time) )
        {
            $startTime = Carbon::parse($session->actual_date->format('Y-m-d') . ' ' . $session->session_start_time);
            $endTime = Carbon::parse($session->actual_date->format('Y-m-d') . ' ' . $session->session_end_time);
            $otjDuration = $endTime->diff($startTime)->format('%H:%I');
        }

        $otj = $training->otj()->create([
            'title' => substr($session->session_details_1, 0, 500),
            'date' => $session->actual_date,
            'start_time' => $session->session_start_time,
            'duration' => $otjDuration,
            'type' => OtjTypeLookup::OTJ_TYPE_DP_SESSION,
            'status' => Otj::STATUS_AWAITING,
        ]);

        $training->student->notify(new OtjLogCreated($training, $otj, $session->id));
    }

    public function destroy(TrainingRecord $training, TrainingDeliveryPlanSession $session)
    {
        abort_if(! auth()->user()->isAssessor() && ! auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        abort_if(
            !in_array($session->id, $training->sessions()->pluck('id')->toArray()),
            Response::HTTP_UNAUTHORIZED
        );

        if($session->hasStudentSigned() || $session->hasAssessorSigned())
        {
            return back()->with(['alert-danger' => 'This session cannot be deleted.']);
        }

        $session->delete();

        return redirect()
            ->route('trainings.show', [$training])
            ->with(['alert-success' => 'Delivery plan session has been deleted successfully.']);
    }
}
