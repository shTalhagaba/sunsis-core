<?php

namespace App\Http\Controllers\Integration\Sunesis;

use App\DTO\Enrolment\EnrolmentDTO;
use App\DTO\Enrolment\QualificationDTO;
use App\Facades\AppConfig;
use App\Helpers\SunesisHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\PortfolioStatusLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Location;
use App\Models\Organisations\Organisation;
use App\Models\Programmes\Programme;
use App\Models\Student;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use App\Services\Students\Enrolment\EnrolmentService;
use App\Services\Students\StudentService;
use App\Services\Students\Trainings\Reviews\TrainingRecordReviewService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SunesisDirectController extends Controller
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->middleware(['auth', 'is_staff']);
        $this->studentService = $studentService;
    }

    public function showFetchLearnerForm()
    {
        $learnersResult = Session::get('learnersResult') ?? [];
        $FirstName = Session::get('firstnames') ?? null;
        $LastName = Session::get('surname') ?? null;
        $SunesisUsername = Session::get('username') ?? null;
        $SunesisTrainingID = Session::get('tr_id') ?? null;
        $programmes = [];
        $assessors = [];
        $verifiers = [];
        $tutors = [];
        $programmesIdsToSelect = [];
        $usersIdsToSelect = [];
        
        if($learnersResult)
        {
            $programmesIdsToSelect = Programme::whereNotNull('sunesis_framework_id')
                ->pluck('id', 'sunesis_framework_id')
                ->toArray();

            $usersIdsToSelect = User::whereNotNull('sunesis_id')
                ->pluck('id', 'sunesis_id')
                ->toArray();

            $learnersResult = json_decode($learnersResult);
            foreach ($learnersResult as &$item) 
            {
                $exists = TrainingRecord::where('sunesis_id', $item->tr_id)->exists();
                $item->AlreadyLinked = $exists ? 1 : 0;
            }

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
        }
        $noRecordFound = Session::get('no_record_found');

        return view(
            'integration.sunesis.fetch_learner', 
            compact(
                'learnersResult', 
                'programmes', 
                'assessors', 
                'tutors', 
                'verifiers', 
                'noRecordFound', 
                'FirstName', 
                'LastName', 
                'SunesisUsername', 
                'SunesisTrainingID', 
                'programmesIdsToSelect',
                'usersIdsToSelect'
            )
        );
    }

    public function searchLearners(Request $request)
    {
        $request->validate([ 
            'firstnames' => 'nullable|string', 
            'surname' => 'nullable|string', 
            'username' => 'nullable|string', 
            'tr_id' => 'nullable|numeric', 
        ], [], [ 
            'firstnames' => 'First Name', 
            'surname' => 'Last Name', 
            'username' => 'Sunesis Username', 
            'tr_id' => 'Sunesis Training ID', 
        ]); 
        
        if (empty($request->input('firstnames')) && empty($request->input('surname')) && empty($request->input('username')) && empty($request->input('tr_id'))) 
        { 
            return back()->withErrors(['error' => 'At least one of the following fields is required: First Name, Last Name, Sunesis Username, or Sunesis Training ID.'])->withInput(); 
        }

        $result = SunesisHelper::searchLearner($request->only(['firstnames', 'surname', 'username', 'tr_id']), true);
        
        return redirect()
            ->route('sunesis.showFetchLearnerForm')
            ->with([
                'learnersResult' => $result, 
                'no_record_found' => empty($result),
                'firstnames' => $request->input('firstnames'),
                'surname' => $request->input('surname'),
                'username' => $request->input('username'),
                'tr_id' => $request->input('tr_id'),
            ]);
    }

    public function fetchLearner(Request $request)
    {
        $request->validate(['sunesisTrainingID' => 'required|numeric'], [], ['sunesisTrainingID' => 'Missing Sunesis Learner ID']);
        $sunesisTrainingID = $request->sunesisTrainingID;
        $request->validate([
            'programme_for_' . $sunesisTrainingID => 'required|numeric',
            'assessor_for_' . $sunesisTrainingID => 'required|numeric',
            'tutor_for_' . $sunesisTrainingID => 'nullable|numeric',
            'verifier_for_' . $sunesisTrainingID => 'required|numeric',
        ], [], [
            'programme_for_' . $sunesisTrainingID => 'Programme',
            'assessor_for_' . $sunesisTrainingID => 'Assessor',
            'tutor_for_' . $sunesisTrainingID => 'Tutor',
            'verifier_for_' . $sunesisTrainingID => 'Verifier',
        ]);

        $programmeId = $request->input('programme_for_' . $sunesisTrainingID);
        $programme = Programme::find($programmeId);
        $assessorId = $request->input('assessor_for_' . $sunesisTrainingID);
        $verifierId = $request->input('verifier_for_' . $sunesisTrainingID);
        $tutorId = $request->input('tutor_for_' . $sunesisTrainingID);

        $sunTrainingRecord = SunesisHelper::getSingleRow('tr', $sunesisTrainingID, 'id');

        DB::beginTransaction();
        try 
        {
            $employerAndLocation = $this->createEmployerIfNeeded($sunTrainingRecord);

            $folioStudent = $this->createStudentIfNeeded($sunTrainingRecord);
            
            $training = $this->enrolLearner(
                $folioStudent, 
                $programme, 
                $sunTrainingRecord->start_date,
                $sunTrainingRecord->target_date,
                $employerAndLocation['employer_location']->id,
                $assessorId,
                $verifierId,
                $sunesisTrainingID
            );

            $training = $this->postProcessing($training, $sunesisTrainingID);

            DB::commit();

            return redirect()->route('trainings.show', $training)->with(['alert-success' => 'All done successfully']);            
        } 
        catch (Exception $e) 
        {
            DB::rollBack();
            return back()->with(['alert-danger' => 'Learner API call: ' . $e->getMessage()]);
        }

        return back()->with(['alert-danger' => 'Something went wrong, process not completed.']);
    }

    private function createEmployerIfNeeded($sunTrainingRecord)
    {
        $folioEmployer = Organisation::where('sunesis_id', $sunTrainingRecord->employer_id)->first();
        if(!$folioEmployer)
        {
            $sunesisEmployer = SunesisHelper::getSingleRow('organisations', $sunTrainingRecord->employer_id, 'id');

            $folioEmployer = Organisation::create([
                'org_type' => Organisation::TYPE_EMPLOYER,
                'legal_name' => $sunesisEmployer->legal_name ?? null,
                'trading_name' => $sunesisEmployer->trading_name ?? null,
                'company_number' => $sunesisEmployer->company_number ?? null,
                'vat_number' => $sunesisEmployer->vat_number ?? null,
                'edrs' => $sunesisEmployer->edrs ?? null,
                'active' => $sunesisEmployer->active ?? 1,
                'sunesis_id' => $sunesisEmployer->id,
            ]);
        }

        $folioEmployerLocation = Location::where('sunesis_id', $sunTrainingRecord->employer_location_id)->first();
        if(!$folioEmployerLocation)
        {
            $sunesisEmployerLocation = SunesisHelper::getSingleRow('locations', $sunTrainingRecord->employer_location_id, 'id');

            $folioEmployerLocation = $folioEmployer
                ->locations()
                ->create([
                    'title' => $sunesisEmployerLocation->full_name ?? null,
                    'is_legal_address' => 1,
                    'address_line_1' => $sunesisEmployerLocation->address_line_1 ?? null,
                    'address_line_2' => $sunesisEmployerLocation->address_line_2 ?? null,
                    'address_line_3' => $sunesisEmployerLocation->address_line_3 ?? null,
                    'address_line_4' => $sunesisEmployerLocation->address_line_4 ?? null,
                    'postcode' => $sunesisEmployerLocation->postcode ?? null,
                    'telephone' => $sunesisEmployerLocation->telephone ?? null,
                    'fax' => $sunesisEmployerLocation->fax ?? null,
                    'sunesis_id' => $sunesisEmployerLocation->id,
                ]);            
        }
        
        return [
            'employer' => $folioEmployer,
            'employer_location' => $folioEmployerLocation,
        ];
    }

    private function createStudentIfNeeded($sunTrainingRecord)
    {
        $folioStudent = User::where('sunesis_id', $sunTrainingRecord->username)->first();
        if(!$folioStudent)
        {
            $sunesisStudent = SunesisHelper::getSingleRow('users', $sunTrainingRecord->username, 'username');

            $folioStudent = $this->studentService->create([
                'username' => $this->studentService->generateUniqueUsername($sunesisStudent->firstnames, $sunesisStudent->surname),
                'user_type' => UserTypeLookup::TYPE_STUDENT,
                'firstnames' => $sunesisStudent->firstnames ?? null,
                'surname' => $sunesisStudent->surname ?? null,
                'email' => $sunesisStudent->home_email ?? $sunesisStudent->work_email ?? null,
                'primary_email' => $sunesisStudent->work_email ?? $sunesisStudent->home_email ?? null,
                'gender' => $sunesisStudent->gender ?? null,
                'web_access' => 0,
                'ni' => $sunesisStudent->ni ?? null,
                'uln' => $sunTrainingRecord->uln ?? null,
                'date_of_birth' => $sunesisStudent->dob ?? null,
                'ethnicity' => $sunTrainingRecord->ethnicity ?? null,
                'employer_location' => Location::where('sunesis_id', $sunTrainingRecord->employer_location_id)->first()->id ?? null,
                'work_address_line_1' => $sunTrainingRecord->work_address_line_1 ?? null,
                'work_address_line_2' => $sunTrainingRecord->work_address_line_2 ?? null,
                'work_address_line_3' => $sunTrainingRecord->work_address_line_3 ?? null,
                'work_address_line_4' => $sunTrainingRecord->work_address_line_4 ?? null,
                'work_postcode' => $sunTrainingRecord->work_postcode ?? null,
                'work_telephone' => $sunTrainingRecord->work_telephone ?? null,
                'work_mobile' => $sunTrainingRecord->work_mobile ?? null,
                'home_address_line_1' => $sunTrainingRecord->home_address_line_1 ?? null,
                'home_address_line_2' => $sunTrainingRecord->home_address_line_2 ?? null,
                'home_address_line_3' => $sunTrainingRecord->home_address_line_3 ?? null,
                'home_address_line_4' => $sunTrainingRecord->home_address_line_4 ?? null,
                'home_postcode' => $sunTrainingRecord->home_postcode ?? null,
                'home_telephone' => $sunTrainingRecord->home_telephone ?? null,
                'home_mobile' => $sunTrainingRecord->home_mobile ?? null,

                'sunesis_id' => $sunesisStudent->id,
            ]);
        }

        return $folioStudent;
    }

    private function enrolLearner(Student $student, Programme $programme, $startDate, $plannedEndDate, $employerLocation, $assessorId, $verifierId, $sunesisId = null)
    {
        $enrolmentDto = new EnrolmentDTO();
        $enrolmentDto
            ->setStudent($student->id)
            ->setProgramme($programme->id)
            ->setStartDate($startDate)
            ->setPlannedEndDate($plannedEndDate)
            ->setEmployerLocation($employerLocation)
            ->setPrimaryAssessor($assessorId)
            ->setVerifier($verifierId);

        foreach ($programme->qualifications as $programmeQual) 
        {
            $sd = Carbon::parse($startDate);
            $ped = Carbon::parse($plannedEndDate);
            if($programmeQual->offset != '' && (int)$programmeQual->offset > 0)
            {
                $sd->addMonths($programmeQual->offset);
            }
            if($programmeQual->duration != '' && (int)$programmeQual->duration > 0)
            {
                $ped = Carbon::parse($sd->format('Y-m-d'));
                $ped->addMonths($programmeQual->duration);
            }

            $enrolmentDto->addQualification(
                new QualificationDTO($programmeQual->id, $sd->format('Y-m-d'), $ped->format('Y-m-d'))
            );
            $enrolmentDto->addUnitIds($programmeQual->units()->where('unit_group', 1)->pluck('id')->toArray());
        }

        $enrolmentService = new EnrolmentService();

        $training = $enrolmentService->enrolStudent($student, $enrolmentDto);
        if (!is_null($sunesisId)) {
            $training->update([
                'sunesis_id' => $sunesisId
            ]);
        }

        return $training;
    }

    private function postProcessing(TrainingRecord $training, int $sunesisTrainingID)
    {
        $sunTrainingRecord = SunesisHelper::getSingleRow('tr', $sunesisTrainingID, 'id');

        $training->update([
            'otj_hours' => $sunTrainingRecord->otj_hours ?? null,
        ]);

        foreach($training->portfolios as $portfolio)
        {
            $sunStudentQualification = SunesisHelper::getSingleRow('student_qualifications', $sunesisTrainingID, 'tr_id', [['id', '=', $portfolio->qan]]);
            if($sunStudentQualification)
            {
                $portfolio->update([
                    'start_date' => $sunStudentQualification->start_date ?? $portfolio->start_date,
                    'planned_end_date' => $sunStudentQualification->end_date ?? $portfolio->planned_end_date,
                    'actual_end_date' => !empty($sunStudentQualification->actual_end_date) ? $sunStudentQualification->actual_end_date : null,
                    'ab_registration_number' => $sunStudentQualification->awarding_body_reg ?? null,
                    'ab_registration_date' => $sunStudentQualification->awarding_body_date ?? null,
                    'certificate_no' => $sunStudentQualification->certificate_no ?? null,
                    'cert_applied' => !empty(trim($sunStudentQualification->certificate_applied ?? '')) ? $sunStudentQualification->certificate_applied : null,
                    'cert_received' => !empty(trim($sunStudentQualification->certificate_received ?? '')) ? $sunStudentQualification->certificate_received : null,
                    'cert_expiry_date' => !empty(trim($sunStudentQualification->awarding_body_expiry_date ?? '')) ? $sunStudentQualification->awarding_body_expiry_date : null,
                    'batch_no' => $sunStudentQualification->awarding_body_batch ?? null,
                    'candidate_no' => $sunStudentQualification->candidate_no ?? null,
                    'cert_sent_to_learner' => $sunStudentQualification->certificate_sent ?? ($sunStudentQualification->certificate_post_date ?? null),
                    'status_code' => isset($sunStudentQualification->aptitude) && $sunStudentQualification->aptitude == 1 ? 
                        PortfolioStatusLookup::STATUS_EXEMPT : PortfolioStatusLookup::STATUS_CONTINUING
                ]);
            }
        }
        
        $sunesisFrameworkId = $training->programme->sunesis_framework_id;
        $sunFramework = SunesisHelper::getSingleRow('frameworks', $sunesisFrameworkId, 'id');
        if(isset($sunFramework->id))
        {
            (new TrainingRecordReviewService)->generateBlankReviews($training, $sunFramework->first_review, $sunFramework->review_frequency);
        }

        return $training;
    }
}