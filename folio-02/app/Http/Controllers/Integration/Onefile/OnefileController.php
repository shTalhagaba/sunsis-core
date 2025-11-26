<?php

namespace App\Http\Controllers\Integration\Onefile;

use App\DTO\Enrolment\EnrolmentDTO;
use App\DTO\Enrolment\QualificationDTO;
use App\Facades\AppConfig;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LookupManager;
use App\Models\Lookups\TrainingReviewLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Organisation;
use App\Models\Programmes\Programme;
use App\Models\Student;
use App\Models\Training\Otj;
use App\Models\Training\TrainingRecord;
use App\Models\Training\TrainingReview;
use App\Models\User;
use App\Services\Students\Enrolment\EnrolmentService;
use App\Services\Students\StudentService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Session;

class OnefileController extends Controller
{
    private $client;
    private $baseUri;
    private $xTokenID;
    private $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
        $this->middleware(function ($request, $next) { 
            abort_if(is_null(AppConfig::get('onefile-X-CustomerToken')), 403, 'This functionality is not enabled for your system.'); 
            return $next($request); 
        });

        $this->baseUri = 'https://wsapi.onefile.co.uk/api/v2.1';
        $this->client = new Client([
            'verify' => false,
            'headers' => $this->defaultHeaders,
        ]);
        
        $this->xTokenID = $this->getXToken();
    }

    private function getXToken() 
    {
        $tokenExpiryTime = AppConfig::get('onefile-X-TokenExpiry'); 
        if( !is_null($tokenExpiryTime) && Carbon::parse($tokenExpiryTime)->isFuture() )
        {
            return AppConfig::get('onefile-X-TokenID');
        }

        try
        {
            $response = $this->client->post(
                $this->baseUri . '/authentication',
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-CustomerToken' => AppConfig::get('onefile-X-CustomerToken')]
                    )
                ] 
            );

            if ($response->getStatusCode() == 200) 
            {
                $newToken = $response->getBody()->getContents();
                $newExpiryTime = now()->addHours(23);

                AppConfig::set('onefile-X-TokenID', $newToken);
                AppConfig::set('onefile-X-TokenExpiry', $newExpiryTime);

                return $newToken;
            }
        }
        catch(RequestException $e)
        {
            throw $e;
        }
    }

    public function showForm(Request $request)
    {
        $learnersResult = Session::get('learnersResult') ?? [];
        $programmes = [];
        $assessors = [];
        $verifiers = [];
        $tutors = [];
        $FirstName = $request->FirstName ?? null;
        $LastName = $request->LastName ?? null;
        $MISID = $request->MISID ?? null;
        if($learnersResult)
        {
            $learnersResult = json_decode($learnersResult);
            foreach ($learnersResult as &$item) 
            {
                $exists = User::where('onefile_id', $item->ID)->exists();
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
            'integration.onefile.fetch_learner', 
            compact('learnersResult', 'programmes', 'assessors', 'tutors', 'verifiers', 'noRecordFound', 'FirstName', 'LastName', 'MISID')
        );
    }

    public function searchLearners(Request $request)
    {
        $request->validate([ 
            'FirstName' => 'nullable|string', 
            'LastName' => 'nullable|string', 
            'MISID' => 'nullable|string', 
        ], [], [ 
            'FirstName' => 'First Name', 
            'LastName' => 'Last Name', 
            'MISID' => 'MIS ID', 
        ]); 
        
        if (empty($request->input('FirstName')) && empty($request->input('LastName')) && empty($request->input('MISID'))) 
        { 
            return back()->withErrors(['error' => 'At least one of the following fields is required: First Name, Last Name, or MIS ID.'])->withInput(); 
        }

        $searchFields = array_filter([ 
            'FirstName' => $request->FirstName, 
            'LastName' => $request->LastName, 
            'MISID' => $request->MISID 
        ]);
/*
        $str = '[ 
        {
            "ID": 1250552,
            "FirstName": "Sandra",
            "LastName": "Coelho",
            "MISID": "0002N"
        },
        {
            "ID": 1250554,
            "FirstName": "Luca",
            "LastName": "Leonardi",
            "MISID": "0003F"
        },
        {
            "ID": 1250555,
            "FirstName": "Matthew",
            "LastName": "Manning",
            "MISID": "0002K"
        },
        {
            "ID": 1250556,
            "FirstName": "Roberto",
            "LastName": "Nino",
            "MISID": "0002P"
        }]';

        return redirect()->route('onefile.showFetchLearnerForm')->with(['learnersResult' => $str, 'no_record_found' => 1]);
*/

        try
        {
            $response = $this->client->post(
                $this->baseUri . '/User/Search',
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                    'json' => array_merge(
                        $searchFields, 
                        ['Role' => 1]
                    ),
                ] 
            );

            if ($response->getStatusCode() == 200) 
            {
                $result = $response->getBody()->getContents();
                return redirect()->route('onefile.showFetchLearnerForm')->with(['learnersResult' => $result]);
            }
            if ($response->getStatusCode() == 204) 
            {
                $result = $response->getBody()->getContents();
                return redirect()->route('onefile.showFetchLearnerForm')->with(['learnersResult' => $result, 'no_record_found' => 1]);
            }
        }
        catch(RequestException $e)
        {
            throw $e;
        }
    }

    public function fetchLearner(Request $request)
    {
        $request->validate(['onefileLearnerID' => 'required|numeric'], [], ['onefileLearnerID' => 'Missing Onefile Learner ID']);
        $onefileLearnerID = $request->onefileLearnerID;
        $request->validate([
            'programme_for_' . $onefileLearnerID => 'required|numeric',
            'assessor_for_' . $onefileLearnerID => 'required|numeric',
            'tutor_for_' . $onefileLearnerID => 'nullable|numeric',
            'verifier_for_' . $onefileLearnerID => 'required|numeric',
        ], [], [
            'programme_for_' . $onefileLearnerID => 'Programme',
            'assessor_for_' . $onefileLearnerID => 'Assessor',
            'tutor_for_' . $onefileLearnerID => 'Tutor',
            'verifier_for_' . $onefileLearnerID => 'Verifier',
        ]);

        $programmeId = $request->input('programme_for_' . $onefileLearnerID);
        $assessorId = $request->input('assessor_for_' . $onefileLearnerID);
        $verifierId = $request->input('verifier_for_' . $onefileLearnerID);
        $tutorId = $request->input('tutor_for_' . $onefileLearnerID);

        try 
        {
            $response = $this->client->get(
                $this->baseUri . '/User/' . $onefileLearnerID,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                ] 
            );

            $userData = json_decode($response->getBody()->getContents(), true);

            $training = $this->processUserData($userData, $programmeId, $assessorId, $verifierId, $tutorId);

            return redirect()->route('trainings.show', $training)->with(['alert-success' => 'All done successfully']);            
        } 
        catch (\Exception $e) 
        {
            return back()->with(['alert-danger' => 'Learner API call: ' . $e->getMessage()]);
        }

        return back()->with(['alert-danger' => 'Something went wrong, process not completed.']);
    }

    protected function processUserData(array $userData, $programmeId, $assessorId, $verifierId, $tutorId)
    {
        if (!isset($userData['Email']) && !isset($userData['Username'])) {
            return;
        }

        $studentService = new StudentService();

        $studentUsername = $userData['Username'];
        if(Student::where('username', $userData['Username'])->exists())
        {
            $studentUsername = $studentService->generateUniqueUsername($userData['FirstName'], $userData['LastName']);
        }

        DB::beginTransaction();
        try {

            $employer = Organisation::query()
                ->where('company_number', $userData['PlacementID'])
                ->where('vat_number', $userData['PlacementID'])
                ->orWhere('onefile_id', $userData['PlacementID'])
                ->first();

            if (is_null($employer) && isset($userData['PlacementID'])) 
            {
                $employer = $this->createEmployer($userData['PlacementID']);
            }

            $homeAddressParts = $this->formatAddress($userData['HomeAddress']);

            $studentData = [
                'user_type' => UserTypeLookup::TYPE_STUDENT,
                'email' => $userData['Email'],
                'username' => $studentUsername, //$userData['Username'],
                'password' => bcrypt(AppHelper::generatePassword()),
                'firstnames' => $userData['FirstName'] ?? null,
                'surname' => $userData['LastName'] ?? null,
                'gender' => $userData['L13'] ?? null,
                'primary_email' => $userData['Email'],
                'ni' => $userData['NINO'] ?? null,
                'uln' => $userData['ULN'] ?? null,
                'date_of_birth' => isset($userData['DOB']) ? $this->formatOneFileDate($userData['DOB']) : null,
                'ethnicity' => $userData['L12'] ?? null,
                'employer_location' => $employer->mainLocation()->id,
                'home_postcode' => end($homeAddressParts),
                'home_address_line_4' => isset($homeAddressParts[count($homeAddressParts) - 2]) ? $homeAddressParts[count($homeAddressParts) - 2] : null,
                'home_address_line_3' => isset($homeAddressParts[count($homeAddressParts) - 3]) ? $homeAddressParts[count($homeAddressParts) - 3] : null,
                'home_address_line_2' => isset($homeAddressParts[1]) ? $homeAddressParts[1] : null,
                'home_address_line_1' => isset($homeAddressParts[0]) ? $homeAddressParts[0] : null,
                'home_telephone' => $userData['Telephone'] ?? null,
                'home_mobile' => $userData['MobileNumber'] ?? null,
                'onefile_id' => $userData['ID'],
            ];

            $student = $studentService->create($studentData);

            $programme = Programme::find($programmeId);		
            $startDate = $this->formatOneFileDate($userData['StartOn']);
            $plannedEndDate = $this->formatOneFileDate($userData['PlannedEndDate']);
            $employerLocation = $employer->mainLocation()->id;

            $training = $this->enrolLearner($student, $programme, $startDate, $plannedEndDate, $employerLocation, $assessorId, $verifierId, $tutorId, $userData['EpisodeName']);	

            $this->fetchOtjLogs($training, $userData['ID']);

            $this->fetchReviews($training, $userData['ID']);

            $this->fetchOtjPlannedHours($training, $userData['ID']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $training;
    }

    private function fetchReviews(TrainingRecord $training, $learnerId)
    {
        try 
        {
            $response = $this->client->post(
                $this->baseUri . '/Review/Search',
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                    'json' => [
                        'OrganisationID' => 2379,
                        'LearnerID' => $learnerId,
                    ],
                ] 
            );
            
            $reviewsData = json_decode($response->getBody()->getContents(), true);
            foreach ($reviewsData as $reviewBasicData) 
            {
                $responseReview = $this->client->get(
                    $this->baseUri . '/Review/' . $reviewBasicData['ID'],
                    [
                        'headers' => array_merge(
                            $this->defaultHeaders, 
                            ['X-TokenID' => $this->xTokenID]
                        ),
                    ]
                );
                $reviewDetail = json_decode($responseReview->getBody()->getContents(), true);

                $review = TrainingReview::create([
                    'tr_id' => $training->id,
                    'title' => 'Progress Review',
                    'due_date' => isset($reviewDetail['ScheduledFor']) ? $this->formatOneFileDate($reviewDetail['ScheduledFor']) : null,
                    'meeting_date' => isset($reviewDetail['StartedOn']) ? $this->formatOneFileDate($reviewDetail['StartedOn']) : null,
                    'assessor_comments' => json_encode($reviewDetail),
                    'type_of_review' => TrainingReviewLookup::TYPE_PROGRESS_REVIEW,
                    'start_time' => isset($reviewDetail['StartedOn']) ? substr($this->formatOneFileDate($reviewDetail['StartedOn'], true), 11) : null,
                    'end_time' => isset($reviewDetail['StartedOn']) ? substr($this->formatOneFileDate($reviewDetail['StartedOn'], true), 11) : null,
                    'learner_signed_at' => isset($reviewDetail['LearnerSignedOn']) ? $this->formatOneFileDate($reviewDetail['LearnerSignedOn'], true) : null,
                    'assessor_signed_at' => isset($reviewDetail['AssessorSignedOn']) ? $this->formatOneFileDate($reviewDetail['AssessorSignedOn'], true) : null,
                    'employer_signed_at' => isset($reviewDetail['EmployerSignedOn']) ? $this->formatOneFileDate($reviewDetail['EmployerSignedOn'], true) : null,
                ]);
            }
        }
        catch (\Exception $e) 
        {
            return back()->with(['alert-danger' => 'Review API call: ' . $e->getMessage()]);
        }
    }

    private function fetchOtjLogs(TrainingRecord $training, $learnerId)
    {
        try 
        {
            $response = $this->client->get(
                $this->baseUri . '/Timesheet/Learner/' . $learnerId,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                ] 
            );

            $otjData = json_decode($response->getBody()->getContents(), true);
            $otjTypes = LookupManager::getOtjDdl();
            foreach ($otjData as $otjEntry) 
            {
		if(strlen($otjEntry['Comments']) > 65535)
                {
                    continue;
                }

                $otj = Otj::create([
                    'tr_id' => $training->id,
                    'title' => $otjEntry['TimesheetCategory'] ?? null,
                    'date' => isset($otjEntry['FromDate']) ? $this->formatOneFileDate($otjEntry['FromDate']) : null,
                    'start_time' => isset($otjEntry['FromDate']) ? substr($this->formatOneFileDate($otjEntry['FromDate'], true), 11) : null,
                    'duration' => isset($otjEntry['FromDate']) ? $this->getDuration($otjEntry['FromDate'], $otjEntry['ToDate']) : null,
                    'type' => (array_search($otjEntry['TimesheetCategory'], $otjTypes)) ? array_search($otjEntry['TimesheetCategory'], $otjTypes) : 19,
                    'details' => $otjEntry['Comments'] ?? null,
                    'is_otj' => ($otjEntry['IsOffTheJob'] || $otjEntry['IsOffTheJob'] == 'true') ? 1 : 0,
                    'assessor_comments' => json_encode($otjEntry),
                    'status' => 'Accepted',
                ]);
            }
        }
        catch (\Exception $e) 
        {
            return back()->with(['alert-danger' => 'OTJ log API call: ' . $e->getMessage()]);
        }
    }

    private function fetchOtjPlannedHours(TrainingRecord $training, $learnerId)
    {
        try 
        {
            $response = $this->client->get(
                $this->baseUri . '/User/' . $learnerId . '/offthejob',
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                ] 
            );

            $otjHoursData = json_decode($response->getBody()->getContents(), true);
            if(is_array($otjHoursData) && isset($otjHoursData['PlannedOTJ']))
            {
                $training->update([
                    'otj_hours' => round($otjHoursData['PlannedOTJ'])
                ]);
            }
        }
        catch (\Exception $e) 
        {
            return back()->with(['alert-danger' => 'OTJ hours API call: ' . $e->getMessage()]);
        }
    }

    private function enrolLearner(Student $student, Programme $programme, $startDate, $plannedEndDate, $employerLocation, $assessorId, $verifierId, $tutorId = null, $episodeName = null)
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
        if (!is_null($episodeName)) {
            $training->update([
                'onefile_episode' => $episodeName
            ]);
        }

        return $training;
    }

    protected function createEmployer($placementId)
    {
        try 
        {
            $response = $this->client->get(
                $this->baseUri . '/Placement/' . $placementId,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders, 
                        ['X-TokenID' => $this->xTokenID]
                    ),
                ] 
            );

            $employerData = json_decode($response->getBody()->getContents(), true);

            $employer = Organisation::create([
                'org_type' => Organisation::TYPE_EMPLOYER,
                'legal_name' => $employerData['Name'],
                'trading_name' => $employerData['Name'],
                'active' => ($employerData['Active'] || $employerData['Active'] == "true") ? 1 : 0,
                'onefile_id' => $employerData['ID'],
                'company_number' => $employerData['ID'],
            ]);

            $addressParts = $this->formatAddress($employerData['Address']);

            $employer->locations()->create([
                'is_legal_address' => 1,
                'title' => 'Main Site',
                'postcode' => end($addressParts),
                'address_line_4' => isset($addressParts[count($addressParts) - 2]) ? $addressParts[count($addressParts) - 2] : null,
                'address_line_3' => isset($addressParts[count($addressParts) - 3]) ? $addressParts[count($addressParts) - 3] : null,
                'address_line_2' => isset($addressParts[count($addressParts) - 4]) ? $addressParts[count($addressParts) - 4] : null,
                'address_line_1' => isset($addressParts[0]) ? $addressParts[0] : null,
            ]);
        }
        catch (RequestException $e) 
        {
            return back()->with(['alert-danger' => 'Placement API call: ' . $e->getMessage()]);
        }
        catch (\Exception $e) 
        {
            return back()->with(['alert-danger' => 'Placement API call: ' . $e->getMessage()]);
        }

        return $employer;
    }

    private function formatAddress($inputAddress)
    {
        $normalizedAddress = str_replace("/\r\n|\r|\n/", "\n", $inputAddress);
        $addressParts = explode("\n", $normalizedAddress);
        if(count($addressParts) == 1)
        {
            $addressParts = explode(",", $normalizedAddress);
        }
        //$addressParts = array_filter($addressParts, fn($line) => trim($line) !== '');

        return $addressParts;
    }

    private function formatOneFileDate($apiDate, $withTime = false)
    {
        $dateTime = new DateTime($apiDate, new DateTimeZone('UTC'));

        $dateTime->setTimezone(new DateTimeZone('Europe/London'));

        return !$withTime ? $dateTime->format('Y-m-d') : $dateTime->format('Y-m-d H:i:s');
    }

    private function getDuration($fromDate, $toDate)
    {
        $start = new DateTime($fromDate, new DateTimeZone('UTC'));
        $end = new DateTime($toDate, new DateTimeZone('UTC'));

        $start->setTimezone(new DateTimeZone('Europe/London'));
        $end->setTimezone(new DateTimeZone('Europe/London'));

        $interval = $start->diff($end);

        $hours = $interval->days * 24 + $interval->h; // Include hours for multi-day durations
        $minutes = $interval->i;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

}