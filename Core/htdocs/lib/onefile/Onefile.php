<?php

class Onefile
{
    private $X_CustomerToken = '';
    private $X_TokenID = '';
    private $remoteHost = '';

    /**
	 * 
	 * @throws Exception
	 */
	public function __construct()
	{
		$enabled = SystemConfig::get("onefile.integration");
		if(is_null($enabled) || !$enabled)
		{
			throw new Exception("Integration is not enabled for your system, please contact Sunesis Support.");
		}

		$this->X_CustomerToken = SystemConfig::get("onefile.X-CustomerToken");
		$this->X_TokenID = substr(SystemConfig::get("onefile.X-TokenID"), 19);
		$this->remoteHost = "wsapi.onefile.co.uk"; // "wsapibeta.onefile.co.uk"

		$refresh_token = false;
		if($this->X_TokenID != '')
		{
			$token_generation_timestamp = substr(SystemConfig::get("onefile.X-TokenID"), 0, 19);
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
				SystemConfig::set("onefile.X-TokenID", date('Y-m-d H:i:s').$response->getBody());
				$this->X_TokenID = $response->getBody();
			}
			else
			{
				throw new Exception($response->getBody());
			}
		}
	}

	private function execute($api_end_point, $params = [], $method = self::HTTP_POST)
	{
		$restClient = new RestClient();
		$restClient->setRemoteHost($this->remoteHost)
			->setUriBase('/api/v2.1/')
			->setUseSsl(true)
			->setUseSslTestMode(false)
			->setHeaders([
				'X-TokenID' => $this->X_TokenID,
				'Content-Type' => 'application/json'
			]);

		$response = null;	
		try
		{
			if( $method == self::HTTP_GET )
			{
				$response = $restClient->get($api_end_point);
			}
			else
			{
				$response = $restClient->post($api_end_point, json_encode($params));
			}			
		}
		catch(Exception $e)	
		{
			throw new WrappedException($e);
		}

		return $response;
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
			->setUriBase('/api/v2.1/')
			->setUseSsl(true)
			->setUseSslTestMode(false)
			->setHeaders([
				'X-CustomerToken' => $this->X_CustomerToken,
				'Content-Type' => 'application/json'
			]);

		$response = null;	
		try
		{
			$response = $restClient->post('Authentication', json_encode([]));
		}
		catch(Exception $e)	
		{
			throw new WrappedException($e);
		}

		return $response;
	}

	public function api_UserSearch(array $params)
	{
		$response = $this->execute('User/Search', $params);

		return $response;
	}

	public function api_UserDetail($userID)
	{
		$response = $this->execute('User/' . $userID, [], self::HTTP_GET);

		return $response;
	}
	
	public function api_UserCreate(array $params)
	{
		$response = $this->execute('User', $params);

		return $response;
	}

	public function api_UserUpdate(array $params, $userID)
	{
		$response = $this->execute('User/'.$userID, $params);

		return $response;
	}

	public function api_ClassroomSearch(array $params)
	{
		$response = $this->execute('Classroom/Search', $params);

		return $response;
	}
	
	public function api_PlacementSearch(array $params)
	{
		$response = $this->execute('Placement/Search', $params);

		return $response;
	}

	public function api_PlacementCreate(array $params)
	{
		$response = $this->execute('Placement', $params);

		return $response;
	}

	public function api_Customer()
	{
		$response = $this->execute('Customer', [], self::HTTP_GET);

		return $response;
	}

	public function api_StandardSearch(array $params)
	{
		$response = $this->execute('Standard/Search', $params);

		return $response;
	}

	public function api_OrganisationSearch(array $params)
	{
		$response = $this->execute('Organisation/Search', $params);

		return $response;
	}

	public function api_FrameworkTemplateSearch(array $params)
	{
		$response = $this->execute('FrameworkTemplate/Search', $params);

		return $response;
	}
	
	public function api_FrameworkTemplateAssign(array $params, $userID, $fwk_tpl_id)
	{
		$response = $this->execute('FrameworkTemplate/'.$fwk_tpl_id.'/Assign/'.$userID.'/true', $params);

		return $response;
	}

	public function api_StandardAssign(array $params, $userID, $onefile_standard_id)
	{
		$response = $this->execute('Standard/'.$onefile_standard_id.'/Assign/'.$userID, $params);

		return $response;
	}

	public function api_LearningAim($learningAimId)
	{
		$response = $this->execute('LearningAim/'.$learningAimId, [], self::HTTP_GET);

		return $response;
	}

	public function api_LearningAimSearch(array $params)
	{
		$response = $this->execute('LearningAim/Search/', $params);

		return $response;
	}
	
	public function api_UpdatesLearningAim(array $params, $onefile_learning_aim_id)
	{
		$response = $this->execute('LearningAim/'.$onefile_learning_aim_id, $params);

		return $response;
	}

	public function api_VisitCreate(array $params)
	{
		$response = $this->execute('Visit', $params);

		return $response;
	}
	
	public function api_ReviewCreate(array $params)
	{
		$response = $this->execute('Review', $params);

		return $response;
	}
	
	public static function getOnefileOrganisationsDdl(PDO $link)
	{
		$onefile_organisations_list = [];
		$onefile_customer = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'onefile.customer'");
		$onefile_customer = json_decode($onefile_customer);
		if(isset($onefile_customer->ID))
		{
			$onefile = new Onefile();

			$response = $onefile->api_OrganisationSearch([
				'CustomerID' => $onefile_customer->ID,
			]);

			if($response->getHttpCode() == 200)
			{
				$temp = new stdClass();
				$temp->key = "organisations_{$onefile_customer->ID}";
				$temp->value = $response->getBody();
				DAO::saveObjectToTable($link, "onefile", $temp);
			}
			
			$onefile_organisations_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'organisations_{$onefile_customer->ID}'");

			if($onefile_organisations_list_from_db != '')
			{
				$onefile_organisations_list_from_db = json_decode($onefile_organisations_list_from_db);
				foreach($onefile_organisations_list_from_db AS $onefile_organisation)
				{
					$onefile_organisations_list[] = [$onefile_organisation->ID, $onefile_organisation->Name];
				}
			}
		}
		return $onefile_organisations_list;
	}

	public function api_ReviewSearch(array $params)
	{
		$response = $this->execute('Review/Search/', $params);

		return $response;
	}

	public function api_Review($reviewId)
	{
		$response = $this->execute('Review/' . $reviewId, [], self::HTTP_GET);

		return $response;
	}

	public function api_PlanSearch(array $params)
	{
		$response = $this->execute('Plan/Search/', $params);

		return $response;
	}

	public function api_Plan($planId)
	{
		$response = $this->execute('Plan/' . $planId, [], self::HTTP_GET);

		return $response;
	}


	const TYPE_LEARNER = 1;
	const TYPE_TRAINEE_ASSESSOR = 4;
	const TYPE_ASSESSOR_TUTOR = 5;
	const TYPE_IV_IQA = 10;
	const TYPE_EMPLOYER = 40;
	const TYPE_OBSERVER = 45;

	const REVIEW_SCHEDULED = 1;

	const HTTP_GET = 'get';
	const HTTP_POST = 'post';
}