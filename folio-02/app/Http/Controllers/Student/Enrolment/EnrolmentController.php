<?php

namespace App\Http\Controllers\Student\Enrolment;

use App\DTO\Enrolment\EnrolmentDTO;
use App\DTO\Enrolment\QualificationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Enrolment\StoreStep1Request;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Organisation;
use App\Models\Programmes\Programme;
use App\Models\Qualifications\Qualification;
use App\Models\Student;
use App\Models\User;
use App\Services\Students\Enrolment\EnrolmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnrolmentController extends Controller
{
    public $enrolmentService;

    public function __construct(EnrolmentService $enrolmentService)
    {
        $this->enrolmentService = $enrolmentService;   
        $this->middleware(['auth', 'is_staff']); 
    }

    public function showStep1(Student $student)
    {
        $this->permissionCheck();

        $employers = Organisation::select('legal_name', 'id')
            ->employers()
            ->active()
            ->orderBy('legal_name', 'asc')
            ->pluck('legal_name', 'id')
            ->toArray();

        $assessors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $tutors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_TUTOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $programmes = Programme::where('status', 1)
            ->active()
            ->orderBy('title')
            ->pluck('title', 'id')
            ->toArray();

        $qualOwners = DB::table('lookup_qual_owners')
            ->select("lookup_qual_owners.owner_org_name", "lookup_qual_owners.owner_org_rn")
            ->join('qualifications', 'qualifications.owner_org_rn', '=', 'lookup_qual_owners.owner_org_rn')
            ->distinct()
            ->orderBy('owner_org_name', 'asc')
            ->pluck('owner_org_name', 'owner_org_rn')->toArray();

        $qualifications = [];
        foreach($qualOwners AS $key => $value)
        {
            $qualifications[$value] = Qualification::select(DB::raw("CONCAT(qan, ' - ', title) AS qual_title"), "id")
                ->active()
                ->where('owner_org_rn', $key)
                ->orderBy('qual_title', 'asc')
                ->pluck('qual_title', 'id')->toArray();
        }

        // in case if user has pressed Back to Step 1 button
        $enrolmentDto = request()->session()->get("enrolment.{$student->id}");

        return view('students.enrolment.step1', compact('student', 'assessors', 'employers', 'verifiers', 'qualifications', 'tutors', 'programmes', 'enrolmentDto'));
    }

    public function postStep1(StoreStep1Request $request, Student $student)
    {
        $this->permissionCheck();

        $enrolmentDto = new EnrolmentDTO();
        $enrolmentDto
            ->setStudent($student->id)
            ->setProgramme($request->programme_id)
            ->setStartDate($request->start_date)
            ->setPlannedEndDate($request->planned_end_date)
            ->setEpaDate($request->epa_date)
            ->setEmployerLocation($request->employer_location)
            ->setPrimaryAssessor($request->primary_assessor)
            ->setVerifier($request->verifier);

        if(!is_null($request->secondary_assessor))
        {
            $enrolmentDto->setSecondaryAssessor($request->secondary_assessor);
        }    

        if(!is_null($request->tutor))
        {
            $enrolmentDto->setTutor($request->tutor);
        }
        
        $request->session()->put("enrolment.{$student->id}", $enrolmentDto);

        return redirect()->route('students.singleEnrolment.step2', $student);
    }

    public function showStep2(Request $request, Student $student)
    {
        $this->permissionCheck();

        $enrolmentDto = $request->session()->get("enrolment.{$student->id}");

	    $tutors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_TUTOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
	    
        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        if (!$enrolmentDto) 
        {
            return redirect()->route('students.singleEnrolment.step1', $student);
        }

        return view('students.enrolment.step2', compact('enrolmentDto', 'student', 'tutors', 'verifiers'));
    }

    public function postStep2(Request $request, Student $student)
    {
        $this->permissionCheck();

        Validator::make(
            $request->all(),
            [
                'qualifications' => ['required', 'array', 'min:1'],
                'chkUnit' => ['required', 'array', 'min:1'],
            ], 
            [
                'chkUnit.required' => 'You must select at least one unit.',
            ]
        )->validate();

        $enrolmentDto = $request->session()->get("enrolment.{$student->id}");
        // make sure enrolment DTO qualifications and units are blank otherwise it will just push
        $enrolmentDto->qualifications = [];
        $enrolmentDto->unitIds = [];

        foreach($request->qualifications AS $selectedQualificationId)
        {
            $sd = $request->input('start_date_qual_'.$selectedQualificationId);
            $ped = $request->input('planned_end_date_qual_'.$selectedQualificationId);
            $tutor = $request->input('tutor_qual_'.$selectedQualificationId);
            $verifier = $request->input('verifier_qual_'.$selectedQualificationId);

            $enrolmentDto->addQualification(new QualificationDTO($selectedQualificationId, $sd, $ped, $tutor, $verifier));
        }
        $enrolmentDto->addUnitIds($request->chkUnit);

        $request->session()->put("enrolment.{$student->id}", $enrolmentDto);

        return redirect()->route('students.singleEnrolment.review', $student);
    }

    public function review(Request $request, Student $student)
    {
        $this->permissionCheck();

        $enrolmentDto = $request->session()->get("enrolment.{$student->id}");

        if (!$enrolmentDto) 
        {
            return redirect()->route('students.singleEnrolment.step1', $student);
        }

        return view('students.enrolment.review', compact('enrolmentDto', 'student'));
    }

    public function confirm(Request $request, Student $student)
    {
        $this->permissionCheck();

        $enrolmentDto = $request->session()->get("enrolment.{$student->id}");

        if (!$enrolmentDto) 
        {
            return redirect()->route('students.singleEnrolment.step1', $student);
        }

        $training = $this->enrolmentService->enrolStudent($student, $enrolmentDto);

        $request->session()->forget("enrolment.{$student->id}");

        if(is_null($training))
        {
            return redirect()
                ->route('students.show', $student)
                ->with(['alert-success' => 'Student is not enrolled, please try again.']);
        }

        return redirect()->route('trainings.show', $training);
    }

    private function permissionCheck()
    {
        abort_if(! auth()->user()->can('enrol-student'), Response::HTTP_UNAUTHORIZED );
        abort_if(! $this->enrolmentService->enrolmentAllowed(), Response::HTTP_FORBIDDEN, 'You have exceeded the number of licenses purchased, please contact Perspective (UK) Ltd. to enrol more learners.');
    }
}