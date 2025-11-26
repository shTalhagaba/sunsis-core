<?php

namespace App\Http\Controllers\Integration\Onefile;

use App\DTO\Enrolment\EnrolmentDTO;
use App\DTO\Enrolment\QualificationDTO;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\LookupManager;
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
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;	

class OnefileController_ extends Controller
{
    private $tokenId = 'ykEin2DLVAf19r0hq/vt4LwenIPqLkLg0meo7QBwUGNMaTMcD3ouAQq1HlYX4h3CuWY2LICcWKSBuRVP0+hhEdOlrw4zE793UruJ4o6RcIZW/gZun9IDS3RuUbpirO6IimW2ntvlc0Fm91y7Z3C9QIWx3GOIm21LJ9gmfWgclC0z1A5nn9Z4NuD/EVRcpPTGasuxth8I3BzuOgv5hm3tZEF+k/IeTAfUn5MXiDEgAK1X2chV0P8VnEbvOqPm2orVv/iQtPPcGHpXz1j6jktYx0D5oA5TCB98Lu9VuS7zC1Y=';
    private $assessors = [];

    public function index()	
    {
        $apiUrl = 'https://wsapi.onefile.co.uk/api/v2.1/User/2101673';			

        try {
            $client = new Client();

            $response = $client->get($apiUrl, [
                'headers' => [
                    'X-TokenID' => $this->tokenId,
                ],
            ]);

            $userData = json_decode($response->getBody()->getContents(), true);

            $training = $this->processUserData($userData);

            return redirect()->route('trainings.show', $training)->with(['alert-success' => 'All done successfully']);            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle API errors
            return response()->json([
                'error' => 'Learner API call failed',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'Something went wrong in Learner API call',
                'message' => $e->getMessage(),
            ], 500);
        }
        return back()->with(['alert-danger' => 'Something went wrong, process not completed.']);
    }

    protected function processUserData(array $userData)
    {
        if (!isset($userData['Email']) && !isset($userData['Username'])) {
            return;
        }

        DB::beginTransaction();
        try {

            $employer = Organisation::query()
                ->where('company_number', $userData['PlacementID'])
                ->where('vat_number', $userData['PlacementID'])
                ->orWhere('onefile_id', $userData['PlacementID'])
                ->first();
            if (is_null($employer) && isset($userData['PlacementID'])) {
                $employer = $this->createEmployer($userData['PlacementID']);
            }

            $homeAddressParts = $this->formatAddress($userData['HomeAddress']);

            $studentData = [
                'user_type' => UserTypeLookup::TYPE_STUDENT,
                'email' => $userData['Email'],
                'username' => $userData['Username'],
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

            $studentService = new StudentService();

            $student = $studentService->create($studentData);

            $programme = Programme::find(28);		
            $startDate = $this->formatOneFileDate($userData['StartOn']);
            $plannedEndDate = $this->formatOneFileDate($userData['PlannedEndDate']);
            $employerLocation = $employer->mainLocation()->id;

            $training = $this->enrolLearner($student, $programme, $startDate, $plannedEndDate, $employerLocation, 72, 211, $userData['EpisodeName']);	

            $this->fetchOtjLogs($training, $userData['ID']);

            $this->fetchReviews($training, $userData['ID']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $training;
    }

    private function fetchReviews(TrainingRecord $training, $learnerId)
    {
        $apiUrl = 'https://wsapi.onefile.co.uk/api/v2.1/Review/Search';

        try {
            $client = new Client();

            $response = $client->post($apiUrl, [
                'headers' => [
                    'X-TokenID' => $this->tokenId,
                ],
                'json' => [
                    'OrganisationID' => 2379,
                    'LearnerID' => $learnerId,
                ],
            ]);

            $reviewsData = json_decode($response->getBody()->getContents(), true);
            foreach ($reviewsData as $reviewBasicData) {
                $responseReview = $client->get('https://wsapi.onefile.co.uk/api/v2.1/Review/' . $reviewBasicData['ID'], [
                    'headers' => [
                        'X-TokenID' => $this->tokenId,
                    ],
                ]);
                $reviewDetail = json_decode($responseReview->getBody()->getContents(), true);

                $review = TrainingReview::create([
                    'tr_id' => $training->id,
                    'title' => 'Progress Review',
                    'due_date' => isset($reviewDetail['ScheduledFor']) ? $this->formatOneFileDate($reviewDetail['ScheduledFor']) : null,
                    'meeting_date' => isset($reviewDetail['StartedOn']) ? $this->formatOneFileDate($reviewDetail['StartedOn']) : null,
                    'meeting_date' => isset($reviewDetail['StartedOn']) ? $this->formatOneFileDate($reviewDetail['StartedOn']) : null,
                    'assessor_comments' => json_encode($reviewDetail),
                    'type_of_review' => 5,
                    'start_time' => isset($reviewDetail['StartedOn']) ? substr($this->formatOneFileDate($reviewDetail['StartedOn'], true), 11) : null,
                    'end_time' => isset($reviewDetail['StartedOn']) ? substr($this->formatOneFileDate($reviewDetail['StartedOn'], true), 11) : null,
                    'learner_signed_at' => isset($reviewDetail['LearnerSignedOn']) ? $this->formatOneFileDate($reviewDetail['LearnerSignedOn'], true) : null,
                    'assessor_signed_at' => isset($reviewDetail['AssessorSignedOn']) ? $this->formatOneFileDate($reviewDetail['AssessorSignedOn'], true) : null,
                    'employer_signed_at' => isset($reviewDetail['EmployerSignedOn']) ? $this->formatOneFileDate($reviewDetail['EmployerSignedOn'], true) : null,
                ]);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle API errors
            return response()->json([
                'error' => 'Review API call failed',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'Something went wrong in Review API call',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function fetchOtjLogs(TrainingRecord $training, $learnerId)
    {
        $apiUrl = 'https://wsapi.onefile.co.uk/api/v2.1/Timesheet/Learner/' . $learnerId;

        try {
            $client = new Client();

            $response = $client->get($apiUrl, [
                'headers' => [
                    'X-TokenID' => $this->tokenId,
                ],
            ]);

            $otjData = json_decode($response->getBody()->getContents(), true);
            $otjTypes = LookupManager::getOtjDdl();
            foreach ($otjData as $otjEntry) {
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
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle API errors
            return response()->json([
                'error' => 'OTJ log API call failed',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'Something went wrong in OTJ log API call',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function enrolLearner(Student $student, Programme $programme, $startDate, $plannedEndDate, $employerLocation, $assessorId, $verifierId, $episodeName = null)
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

        foreach ($programme->qualifications as $programmeQual) {
            $enrolmentDto->addQualification(
                new QualificationDTO($programmeQual->id, $startDate, $plannedEndDate)
            );
            $enrolmentDto->addUnitIds($programmeQual->units()->pluck('id')->toArray());
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
        $apiUrl = 'https://wsapi.onefile.co.uk/api/v2.1/Placement/' . $placementId;

        try {
            $client = new Client();

            $response = $client->get($apiUrl, [
                'headers' => [
                    'X-TokenID' => $this->tokenId,
                ],
            ]);

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
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle API errors
            return response()->json([
                'error' => 'Placement API call failed',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'Something went wrong in Placement API call',
                'message' => $e->getMessage(),
            ], 500);
        }

        return $employer;
    }

    private function formatAddress($inputAddress)
    {
        $normalizedAddress = str_replace("/\r\n|\r|\n/", "\n", $inputAddress);
        $addressParts = explode("\n", $normalizedAddress);
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
