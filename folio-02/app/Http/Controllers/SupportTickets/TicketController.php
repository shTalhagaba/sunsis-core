<?php

namespace App\Http\Controllers\SupportTickets;

use App\Facades\AppConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Services\HttpClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public $httpClient;
    public $X_CustomerToken;
    public $X_TokenID;
    public $X_TokenIdTimestamp;
    public $baseUriSuffix = 'api/v1';
    public $headers = [];

    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
    
    private function setup()
    {
        $this->X_CustomerToken = AppConfig::get('X-CustomerToken');
        if( is_null($this->X_CustomerToken) )
        {
            throw new Exception('Missing Customer Token, please contact Support to resolve this issue.');
        }

        $this->httpClient = new HttpClient(config('services.assistpro.base_uri'));
        $this->headers = [
            'X-CustomerToken' => $this->X_CustomerToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        $this->setXInfo();
    }

    private function setXInfo()
    {
        $X_TokenIdTimestamp = AppConfig::get('X-TokenIdTimestamp');

        $refreshToken = true;
        if(! is_null($X_TokenIdTimestamp))
        {
            $tokenTimeStamp = Carbon::parse($X_TokenIdTimestamp);
            if ( now()->lt($tokenTimeStamp) )
            {
                // Token is still valid
                $refreshToken = false;
            }
        }

        if($refreshToken)
        {
            $response = $this->httpClient->get('/' . $this->baseUriSuffix . '/authentication', [], $this->headers);
            if ($response['status'] === Response::HTTP_OK) 
            {
                if( isset($response['body']['X-TokenID']) )
                {
                    Configuration::updateOrCreate(
                        ['entity' => 'X-TokenIdTimestamp' ],
                        ['entity' => 'X-TokenIdTimestamp', 'value' => now()->addHours(config('services.assistpro.token_valid_hours'))->format('Y-m-d H:i:s') ]
                    );
                    Configuration::updateOrCreate(
                        ['entity' => 'X-TokenID' ],
                        ['entity' => 'X-TokenID', 'value' => isset($response['body']['X-TokenID']) ? $response['body']['X-TokenID'] : null ]
                    );
                }
            }
            else
            {
                throw new Exception($response['status'] . ': ' . json_encode($response['body']));
            }

            AppConfig::loadConfiguration();
        }

        $this->X_TokenID = AppConfig::get('X-TokenID');
        $this->X_TokenIdTimestamp = AppConfig::get('X-TokenIdTimestamp');
    }

    public function index()
    {
        $this->setup();
        
        $X_TokenID = $this->X_TokenID;
        $statusList = $this->getStatusList();
        $typesList = $this->getTypesList();
		$prioritiesList = $this->getPrioritiesList();
        $filters = $_REQUEST;

        return view('support_tickets.index', compact('X_TokenID', 'statusList', 'typesList', 'prioritiesList', 'filters'));
    }

    public function create()
    {
        $this->setup();

        $X_TokenID = $this->X_TokenID;
        $typesList = $this->getTypesList();
		$prioritiesList = $this->getPrioritiesList();
		$statusList = $this->getStatusList();

        return view('support_tickets.create', compact('X_TokenID', 'typesList', 'prioritiesList', 'statusList'));
    }

    public function show($ticket)
    {
        $this->setup();

        $X_TokenID = $this->X_TokenID;
        $ticketResponse = null;

        $response = $this->httpClient->get('/' . $this->baseUriSuffix . '/tickets/' . $ticket, [], [
            'X-TokenID' => $X_TokenID,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
        if ($response['status'] === Response::HTTP_OK && isset($response['body']['data'])) 
        {
            $ticketResponse = $response['body']['data'];
        }
        else
        {
            throw new Exception($response['status'] . ': ' . json_encode($response['body']));
        }
        
        return view('support_tickets.show', compact('X_TokenID', 'ticketResponse'));
    }

    private function getTypesList()
    {
        return [
			// '1' => 'Documentation',
			'2' => 'Enhancement / Development',
			'3' => 'General Enquiry',
			// '4' => 'How to?',
			'13' => 'ILR Related',
			// '5' => 'Incident',
			'6' => 'Inputting / Data Collection',
			// '7' => 'Login Issue',
			// '8' => 'Non Technical',
			'10' => 'Reports',
			'9' => 'System Issue / Bug',
			'11' => 'Training',
			// '12' => 'UI (User Interface)',
            '14' => 'Other',
		];
    }

    private function getPrioritiesList()
    {
        return [
			'1' => 'Critical',
			'2' => 'High',
			'3' => 'Medium',
			'4' => 'Low',
		];
    }

    private function getStatusList()
    {
        return [
			'1' => 'Assigned',
			'3' => 'Awaiting Client',
			'4' => 'Awaiting Confirmation',
			'5' => 'Bespoke Development',
			'7' => 'Deployment',
			'8' => 'Duplicate',
			'9' => 'New',
			'10' => 'On Hold',
			'11' => 'Refused Development',
			'12' => 'Reopened',
			'2' => 'Requires Additional Requirements',
			'13' => 'Validation',
		];
    }

    public function saveAccountContactId(Request $request)
    {
        $request->validate(['support_contact_id' => 'required']);

        auth()->user()->update(['support_contact_id' => $request->support_contact_id]);
    }
}
