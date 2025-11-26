<?php
class save_tracking_management implements IAction
{
	public function execute(PDO $link)
	{
//		/pre($_REQUEST);

		DAO::transaction_start($link);
		try
		{
			if($_REQUEST['formName'] == 'frmInductionAssessors')
			{
				if(isset($_REQUEST['induction_assessors']))
				{
					DAO::execute($link, "UPDATE lookup_induction_assessors SET enabled = 'N'");
					foreach($_REQUEST['induction_assessors'] AS $assessor_id)
					{
						$sql = "REPLACE INTO lookup_induction_assessors (user_id, enabled) VALUES ('{$assessor_id}', 'Y')";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_induction_assessors SET enabled = 'N'");
				}

				if(isset($_REQUEST['assigned_assessors']))
				{
					DAO::execute($link, "UPDATE lookup_assigned_assessors SET enabled = 'N'");
					foreach($_REQUEST['assigned_assessors'] AS $assessor_id)
					{
						$sql = "REPLACE INTO lookup_assigned_assessors (user_id, enabled) VALUES ('{$assessor_id}', 'Y')";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_assigned_assessors SET enabled = 'N'");
				}

				if(isset($_REQUEST['op_trainers']))
				{
					DAO::execute($link, "UPDATE lookup_op_trainers SET enabled = 'N'");
					foreach($_REQUEST['op_trainers'] AS $assessor_id)
					{
						$sql = "REPLACE INTO lookup_op_trainers (user_id, enabled) VALUES ('{$assessor_id}', 'Y')";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_op_trainers SET enabled = 'N'");
				}
				if(isset($_REQUEST['induction_coords']))
				{
					DAO::execute($link, "UPDATE lookup_induction_assigned_coord SET enabled = 'N'");
					foreach($_REQUEST['induction_coords'] AS $assessor_id)
					{
						$sql = "REPLACE INTO lookup_induction_assigned_coord (user_id, enabled) VALUES ('{$assessor_id}', 'Y')";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_induction_assigned_coord SET enabled = 'N'");
				}
				if(isset($_REQUEST['induction_owners']))
				{
					DAO::execute($link, "UPDATE lookup_induction_owners SET enabled = 'N'");
					foreach($_REQUEST['induction_owners'] AS $assessor_id)
					{
						$sql = "REPLACE INTO lookup_induction_owners (user_id, enabled) VALUES ('{$assessor_id}', 'Y')";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_induction_owners SET enabled = 'N'");
				}
			}
			elseif($_REQUEST['formName'] == 'frmDeliveryLocations')
			{
				if(isset($_REQUEST['delivery_locations']))
				{
					DAO::execute($link, "UPDATE lookup_delivery_locations SET enabled = 'N'");
					foreach($_REQUEST['delivery_locations'] AS $location_id)
					{
						$sql = "UPDATE lookup_delivery_locations SET enabled = 'Y' WHERE id = '{$location_id}'";
						DAO::execute($link, $sql);
					}
				}
				else
				{
					DAO::execute($link, "UPDATE lookup_delivery_locations SET enabled = 'N'");
				}
			}
			elseif($_REQUEST['formName'] == 'frmAddNewAssessor')
			{
				$vo = new User();
				$vo->populate($_REQUEST);
				$username_check = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.username = '{$vo->username}'");
				if($username_check > 0)
					throw new Exception('The username already exists, please try different username.');
				if(isset($_REQUEST['web_access']) && $_REQUEST['web_access'] == 'on')
					$vo->web_access = 1;
				$vo->type = User::TYPE_ASSESSOR;

				$vo->save($link);
			}
			elseif($_REQUEST['formName'] == 'frmAddNewDeliveryLocation')
			{
				$description = isset($_REQUEST['description'])?$_REQUEST['description']:'';
				if($description == '')
					throw new Exception('No location description specified');
				$description = addslashes(trim($description));
				$exists = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM lookup_delivery_locations WHERE description = '{$description}'");
				if($exists > 0)
					throw new Exception('Location description already exists');
				DAO::execute($link, "INSERT INTO lookup_delivery_locations (description, enabled) VALUES ('{$description}', 'Y')");
			}
			elseif($_REQUEST['formName'] == 'frmInductionCapacity')
			{
				foreach($_REQUEST AS $key => $value)
				{
					if(substr($key, 0, 3) == 'fn_')
					{
						$month = str_replace('fn_', '', $key);
						$objCapacity = DAO::getObject($link, "SELECT * FROM lookup_induction_capacity WHERE month = '{$month}'");
						if(!isset($objCapacity->month))
						{
							$objCapacity = new stdClass();
							$objCapacity->month = $month;
							$objCapacity->capacity = $value;
						}
						else
						{
							$objCapacity->capacity = $value;
						}
						DAO::saveObjectToTable($link, 'lookup_induction_capacity', $objCapacity);
					}
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect("do.php?_action=tracking_management");
		}

	}
}