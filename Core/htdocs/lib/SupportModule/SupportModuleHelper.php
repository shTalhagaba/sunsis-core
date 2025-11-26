<?php

class SupportModuleHelper
{
    private $X_CustomerToken = '';
    private $X_TokenID = '';
    private $remoteHost = '';

	public function __construct()
	{
        	$this->X_CustomerToken = SystemConfig::get("support_v2_customer_token");
		$this->X_TokenID = substr(SystemConfig::get("support_v2_token_id"), 19);
		$this->remoteHost = "tickets.sunesis.uk.net";

		$refresh_token = false;
		if($this->X_TokenID != '')
		{
			$token_generation_timestamp = substr(SystemConfig::get("support_v2_token_id"), 0, 19);
			if($token_generation_timestamp != '')
			{
				$current_timestamp = date('Y-m-d H:i:s');
				$diff_in_hours = $this->differenceInHours($token_generation_timestamp, $current_timestamp);
				if($diff_in_hours > 23)
				{
					$refresh_token = true;
				}				
			}
		}
		else
		{
			$refresh_token = true;
		}

		if($refresh_token)
		{
            		$response = $this->api_authentication();
			if($response->getHttpCode() == 200)
			{
				SystemConfig::set("support_v2_token_id", date('Y-m-d H:i:s').$response->getBody());
				$this->X_TokenID = $response->getBody();
			}
			else
			{
				throw new Exception($response->getBody());
			}
		}
    }

    private function differenceInHours($startdate, $enddate)
	{
		$starttimestamp = strtotime($startdate);
		$endtimestamp = strtotime($enddate);
		$difference = abs($endtimestamp - $starttimestamp)/3600;
		return $difference;
	}

    private function api_authentication()
	{
		$restClient = new RestClient();
		$restClient->setRemoteHost($this->remoteHost)
			->setUriBase('/api/v1/')
			->setUseSsl(true)
			->setUseSslTestMode(true)	
			->setHeaders([
				'X-CustomerToken' => $this->X_CustomerToken,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			]);

		$response = null;	
		try
		{
			$response = $restClient->get('authentication');
		}
		catch(Exception $e)	
		{
			throw new WrappedException($e);
		}

		return $response;
	}
    
	public function api_tickets_for_account_contact($params = [])
	{
		$restClient = new RestClient();
		$restClient->setRemoteHost($this->remoteHost)
			->setUriBase('/api/v1/')
			->setUseSsl(true)
			->setUseSslTestMode(true)
			->setHeaders([
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'X-TokenID' => $this->getXTokenId(),
			]);

		$response = null;	
		try
		{
			$response = $restClient->post('stats/tickets_for_account_contact', json_encode($params));
		}
		catch(Exception $e)	
		{
			throw new WrappedException($e);
		}

		return $response;
	}

	public function api_ticket($ticket_id)
	{
		$restClient = new RestClient();
		$restClient->setRemoteHost($this->remoteHost)
			->setUriBase('/api/v1/')
			->setUseSsl(true)
			->setUseSslTestMode(true)
			->setHeaders([
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'X-TokenID' => $this->getXTokenId(),
			]);

		$response = null;	
		try
		{
			$response = $restClient->get('tickets/'.$ticket_id);
		}
		catch(Exception $e)	
		{pre($e);
			throw new WrappedException($e);
		}

		return $response;
	}

    public function getXCustomerToken()
    {
        return $this->X_CustomerToken;
    }

    public function getXTokenId()
    {
        $tid = json_decode($this->X_TokenID);
		$tkn = "X-TokenID";

        return $tid->$tkn;
    }

    public function getRemoteHost()
    {
        return $this->remoteHost;
    }
}