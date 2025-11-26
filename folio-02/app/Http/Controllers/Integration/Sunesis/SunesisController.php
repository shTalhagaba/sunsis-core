<?php

namespace App\Http\Controllers\Integration\Sunesis;

use App\Facades\AppConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\TrainingRecord;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class SunesisController extends Controller
{
    private $client;
    private $baseUri;
    private $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
	'timeout' => 1.0
    ];
    
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
        $this->baseUri = AppConfig::get('SUNESIS-API-URI');
        $this->client = new Client([
            'verify' => false,
            'headers' => $this->defaultHeaders,
        ]);
    }

    private function refreshToken(Client $client) 
    {
        $response = $client->post(
            $this->baseUri . '/authentication',
            [
                'headers' => array_merge(
                    $this->defaultHeaders, 
                    ['SUNESIS-API-KEY' => AppConfig::get('SUNESIS-API-KEY')]
                )
            ] 
        );
        $data = json_decode($response->getBody()->getContents(), true);

        if( isset($data['data']['token']) )
        {
            AppConfig::set('SUNESIS-API-TOKEN', $data['data']['token']);
        }

        return $data['data']['token'];
    }

    public function fetchDataWithTokenRetry(Client $client, &$token, $endpoint, $query = []) 
    {
        try 
        {
            $response = $client->get(
                $this->baseUri . $endpoint,
                [
                    'headers' => array_merge($this->defaultHeaders, ['Authorization' => 'Bearer ' . $token]),
                    'query' => $query,
                ] 
            );

            return $response;
        } 
        catch (RequestException $e) 
        {
            // Check if token has expired
            if ($e->getResponse() && $e->getResponse()->getStatusCode() == 401) 
            {
                // Call authenticate endpoint to get a new token
                $token = $this->refreshToken($client);

    
                // Retry the original request with the new token
                $response = $client->get(
                    $this->baseUri . $endpoint,
                    ['headers' => array_merge($this->defaultHeaders, ['Authorization' => 'Bearer ' . $token])] 
                );

                return $response;
            }

            throw $e;
        }
    }

    public function showPushLearnerForm(TrainingRecord $training, Request $request)
    {
        abort_if(! auth()->user()->isAdmin(), 401);
        
        $folioSunesisRecord = DB::table('folio_sunesis_learners')->where('folio_tr_id', $training->id)->first();

        return view('integration.sunesis.push_form', compact('training', 'folioSunesisRecord'));
    }

    public function fetchOptions(Request $request)
    {
        $options = [];
        $token = AppConfig::get('SUNESIS-API-TOKEN');
        $endpoints = [
            'employers' => '/employers',
            'providers' => '/providers',
            'courses' => '/courses',
            'contracts' => '/contracts?ContractYear=' . $request->input('year', ''),
            'assessors' => '/users?UserType=3',
            'tutors' => '/users?UserType=2',
            'verifiers' => '/users?UserType=4',
        ];
        $endpointKey = $request->endpoint;
        if(!array_key_exists($endpointKey, $endpoints))
        {
            return response()->json($options);
        }

        $endpoint = $endpoints[$endpointKey];
        $query = [];
        parse_str( parse_url($endpoint, PHP_URL_QUERY), $query );
        
        try
        {
            $response = $this->fetchDataWithTokenRetry($this->client, $token, $endpoint, $query);
        }
        catch(Exception $e)
        {
            // TODO: throw an exception silently
            return response()->json($options);
        }

        if($response->getStatusCode() == 200)
        {
            $contents = json_decode($response->getBody()->getContents(), true);
            
            if( in_array($endpointKey, ['contracts', 'courses']) )
            {
                $options = $contents['data'];
            }
            if( in_array($endpointKey, ['assessors', 'tutors', 'verifiers']) && isset($contents['data']) )
            {
                foreach($contents['data'] AS $entry)
                {
                    $options[] = $entry;
                }
            }
            if( in_array($endpointKey, ['providers', 'employers']) )
            {
                foreach($contents['data'] AS $entry)
                {
                    $providerName = $endpointKey == 'providers' ? $entry["ProviderName"] : $entry["EmployerName"];
                    $locations = [];
                    foreach($entry['Locations'] AS $locationEntry)
                    {
                        $locationId = $locationEntry['LocationID'];
                        $locationAddress = $locationEntry['LocationTitle'];
                        $locationAddress .= ' [' . $locationEntry['AddressLine1'];
                        $locationAddress .= $locationEntry['AddressLine2'] != '' ? ' ' . $locationEntry['AddressLine2'] : '';
                        $locationAddress .= $locationEntry['AddressLine3'] != '' ? ' ' . $locationEntry['AddressLine3'] : '';
                        $locationAddress .= $locationEntry['AddressLine4'] != '' ? ' ' . $locationEntry['AddressLine4'] : '';
                        $locationAddress .= ', ' . $locationEntry['Postcode'] . ']';

                        $locations[] = ["id" => $locationId, "address" => $locationAddress];
                    }

                    $options[] = ['optgroup' => $providerName, 'options' => $locations];
                }
            }
        }

        return response()->json($options);
    }

    public function pushLearner(TrainingRecord $training, Request $request)
    {
        $request->validate([
            'ProviderLocationID' => 'required|numeric',
            'EmployerLocationID' => 'required|numeric',
            'CourseID' => 'required|numeric',
            'ContractID' => 'required|numeric',
            'AssessorID' => 'nullable|numeric',
            'TutorID' => 'nullable|numeric',
            'VerifierID' => 'nullable|numeric',
        ], [
            'ProviderLocationID' => 'The provider field is required',
            'EmployerLocationID' => 'The employer field is required',
            'CourseID' => 'The course field is required',
            'ContractID.required' => 'The contract field is required',
        ]);

        $student = $training->student;
        $homeAddress = $student->homeAddress();
        $workAddress = $student->workAddress();

        $data = array_merge(
            $request->only(['ProviderLocationID', 'EmployerLocationID', 'CourseID', 'ContractID', 'AssessorID', 'TutorID', 'VerifierID']),
            [
                'GivenNames' => $student->firstnames,
                'FamilyName' => $student->surname,
                'Gender' => $student->gender,
                'DateOfBirth' => optional($student->date_of_birth)->format('Y-m-d'),
                'HomeAddressLine1' => $homeAddress->address_line_1,
                'HomeAddressLine2' => $homeAddress->address_line_2,
                'HomeAddressLine3' => $homeAddress->address_line_3,
                'HomeAddressLine4' => $homeAddress->address_line_4,
                'HomePostcode' => $homeAddress->postcode,
                'HomeMobile' => $homeAddress->mobile,
                'HomeEmail' => $student->primary_email,
                'HomeTelephone' => $homeAddress->telephone,
                'WorkAddressLine1' => $workAddress->address_line_1,
                'WorkAddressLine2' => $workAddress->address_line_2,
                'WorkAddressLine3' => $workAddress->address_line_3,
                'WorkAddressLine4' => $workAddress->address_line_4,
                'WorkPostcode' => $workAddress->postcode,
                'WorkMobile' => $workAddress->mobile,
                'WorkEmail' => $student->primary_email,
                'WorkTelephone' => $workAddress->telephone,
                'Ethnicity' => $student->ethnicity,
                'NationalInsurance' => $student->ni,
                'ULN' => $student->uln,
                'TrainingStartDate' => $training->start_date->format('Y-m-d'),
                'TrainingPlannedEndDate' => $training->planned_end_date->format('Y-m-d'),
            ]
        );

        $token = AppConfig::get('SUNESIS-API-TOKEN');

        try
        {
            $response = $this->client->post(
                $this->baseUri . '/learners',
                [
                    'headers' => array_merge($this->defaultHeaders, ['Authorization' => 'Bearer ' . $token]),
                    'json' => $data,
                ] 
            );

            $data = json_decode($response->getBody()->getContents(), true);
            $data = $data['data'];            
        }
        catch(RequestException $e)
        {
            if ($e->getResponse() && $request->ajax()) 
            {
                return response()->json([
                    'code' => $e->getResponse()->getStatusCode(),
                    'message' => $e->getResponse()->getReasonPhrase(),
                ]);
            }

            return back()->with(['alert-danger' => $e->getResponse()->getStatusCode() . ': ' . $e->getResponse()->getReasonPhrase()]);
        }

        $timestamp = now();
        DB::table('folio_sunesis_learners')->insert([
            'folio_student_id' => $student->id,
            'folio_tr_id' => $training->id,
            'sunesis_learner_id' => $data['SunesisLearnerID'],
            'sunesis_tr_id' => $data['SunesisTrainingID'],
            'sunesis_course_id' => $data['CourseID'],
            'sunesis_provider_id' => $data['ProviderID'],
            'sunesis_provider_location_id' => $data['ProviderLocationID'],
            'sunesis_employer_id' => $data['EmployerID'],
            'sunesis_employer_location_id' => $data['EmployerLocationID'],
            'sunesis_assessor_id' => $data['AssessorID'],
            'sunesis_tutor_id' => $data['TutorID'],
            'sunesis_verifier_id' => $data['VerifierID'],
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
        return redirect()->route('trainings.show', $training)->with(['alert-success' => 'Record is created in Sunesis Successfully.']);
    }
}
