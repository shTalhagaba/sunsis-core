<?php
class ViewEmployerContacts extends View
{

	public static function getInstance($link, $id)
	{	
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = "SELECT contact_title, contact_name, contact_mobile, contact_telephone, contact_email, contact_department FROM organisation_contact WHERE org_id = '".addslashes((string)$id)."' ";
			$sql .= "UNION ";
			$sql .= "SELECT 'contact_title', contact_name, contact_mobile, contact_telephone, contact_email, 'contact department' FROM locations WHERE organisations_id = '".addslashes((string)$id)."'";
			//$sql .= "UNION ";
			//$sql .= "SELECT '', concat(firstname,' ',surname) as contact_name,  '', telephone, email1, '' from central.emp_pool where auto_id = '".addslashes((string)$id)."'";
					
			$view = $_SESSION[$key] = new ViewEmployerContacts();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$view->org_id = $id;
		}
		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{	
		/* @var $result pdo_result */
		$sql = "SELECT contact_title, contact_name, contact_mobile, contact_telephone, contact_email, contact_department FROM organisation_contact WHERE org_id = '".addslashes((string)$this->org_id)."' ";
		$sql .= "UNION ";
		$sql .= "SELECT '', contact_name, contact_mobile, contact_telephone, contact_email, '' FROM locations WHERE organisations_id = '".addslashes((string)$this->org_id)."'";
		//$sql .= "UNION ";
		//$sql .= "SELECT '', concat(firstname,' ',surname) as contact_name,  '', telephone, email1, job from central.emp_pool where auto_id = '".addslashes((string)$this->org_id)."'";


		$st = $link->query($sql);
		if($st) {
			echo '<form action="do.php" method="post" name="new_contact" >';
			echo '<input type="hidden" name="_action" value="edit_crm_note" />';
			echo '<input type="hidden" name="create" value="contact" />';
			if( isset($_REQUEST['pool_id']) ) {
				echo '<input type="hidden" name="pool_id" value="'.$_REQUEST['pool_id'].'" />';
				//echo '<input type="hidden" name="organisations_id" value="'.$_REQUEST['organisations_id'].'" />';
			}
			elseif( isset($_REQUEST['organisations_id']) ) {
				echo '<input type="hidden" name="organisations_id" value="'.$_REQUEST['organisations_id'].'" />';
				echo '<input type="hidden" name="org_id" value="'.$_REQUEST['organisations_id'].'" />';
			}

			echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-left: auto;">';
			echo '<tr style="font-weight:bold" ><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr>';
			echo '<tbody>';
			$row_count = 0;
			$row_style = '';
			while( $contact = $st->fetch() ) {
				if ( $row_count%2 == 0 ) {
					$row_style = 'background-color: #E0EAD0;';
				}
				else {
					$row_style = '';
				}
				echo '<tr style="'.$row_style.'" >';
				echo '<td>'.HTML::cell($contact['contact_title']);
				echo ' '.HTML::cell($contact['contact_name']) . '</td>';
				echo '<td>'.HTML::cell($contact['contact_department']) . '</td>';
				echo '<td>'.HTML::cell($contact['contact_telephone']) . '</td>';
				echo '<td>'.HTML::cell($contact['contact_mobile']) . '</td>';
				echo '<td>'.HTML::cell($contact['contact_email']) . '</td>';
				echo '<td>&nbsp;</td>';
				echo '</tr>';
				$row_count++;
			}
			echo '<tr style="background-color: #E0EAD0; font-weight: bold;" >';
			echo '<td style="border-top: 1px solid #999; border-left: 1px solid #999;" colspan="5">Add a new contact</td>';
			echo '<td style="border-top: 1px solid #999;  border-right: 1px solid #999;">&nbsp;</td>';
			
			echo '</tr>';
			echo '<tr style="background-color: #E0EAD0;" >';
			echo '<td style="border-bottom: 1px solid #999; border-left: 1px solid #999;" ><input class="optional" type="text" name="contact_title" value="Title..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="4" maxlength="10" />';
			echo '&nbsp;<input class="optional" type="text" name="contact_name" value="* Name..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="16" maxlength="200" /></td>';
			echo '<td style="border-bottom: 1px solid #999;"><input class="optional" type="text" name="contact_department" value="Dept..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="10" maxlength="50" />';
			echo '</td>';
			echo '<td style="border-bottom: 1px solid #999;"><input class="optional" type="text" name="contact_telephone" value="Phone..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="10" maxlength="40" /></td>';
			echo '<td style="border-bottom: 1px solid #999;"><input class="optional" type="text" name="contact_mobile" value="Mobile..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="10" maxlength="40" /></td>';
			echo '<td style="border-bottom: 1px solid #999;"><input class="optional" type="text" name="contact_email" value="Email..." onfocus="if (this.value==this.defaultValue) this.value = \'\'" size="10" maxlength="200" /></td>';
			echo '<td style="border-bottom: 1px solid #999; border-right: 1px solid #999;"><input type="submit" name="save" value="save &raquo;" class="submit" style="font-size: 0.9em;" /></td>';
			
			echo '</tr>';				
			echo '</tbody></table>';
			echo '</form>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}	
	}

	public function renderContacts(PDO $link)
	{
		$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = (SELECT dpn FROM central.emp_pool WHERE auto_id = '".$this->org_id."')");
		/* @var $result pdo_result */
		$sql = "SELECT contact_id, contact_title, contact_name, contact_mobile, contact_telephone, contact_email, contact_department FROM organisation_contact WHERE org_id = '".addslashes((string)$this->org_id)."' ";

		$st = $link->query($sql);
		if($st)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			echo '<thead><tr>';
			echo '<th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($exists == 0)
					echo HTML::viewrow_opening_tag('/do.php?_action=edit_prospect_crm_contact&contact_id=' . $row['contact_id']);
				else
					echo '<tr>';
				echo '<td align="left">' . HTML::cell($row['contact_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_department']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_mobile']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_email']) . '</td>';

				echo '</tr>';
			}

			echo '</tbody></table></div>';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
	
	public function contact_dropdown(PDO $link, $pre_selected = '') {

		/*
		 * contact_data including:
		 *  - drop down of contacts
		 *  	* that prepopulates the 'position' section for the CRM note.
		 *  - javascript function to do the prepopulation
		 */
		$contact_data = array('contact_drop' => '', 'contact_java' => '');
		
		$compulsory = DB_NAME == "am_baltic" ? 'optional' : 'compulsory';
		$contact_data['contact_drop'] = '<select name="name_of_person" class="'.$compulsory.'" onchange="javascript:populateDepartment(this);"><option value="">Please select...</option>';
		$contact_data['contact_js'] = "<script type=\"text/javascript\">" . "\nfunction populateDepartment( selection ) { \n";
		
		/* @var $result pdo_result */
		$sql = "SELECT contact_title, contact_name, contact_mobile, contact_telephone, contact_email, contact_department FROM organisation_contact WHERE org_id = '".addslashes((string)$this->org_id)."' ";
		$sql .= "UNION ";
		$sql .= "SELECT '', contact_name, contact_mobile, contact_telephone, contact_email, '' FROM locations WHERE organisations_id = '".addslashes((string)$this->org_id)."'";
		//$sql .= "UNION ";
		//$sql .= "SELECT title, concat(firstname,' ',surname) as contact_name,  '', telephone, email1, job from central.emp_pool where auto_id = '".addslashes((string)$this->org_id)."'";
			
		$st = $link->query($sql);
		if($st) {
			$row_count = 0;
			$row_style = '';
			while( $contact = $st->fetch() ) {
				if($pre_selected != '' && trim($pre_selected) == trim($contact['contact_title']." ".$contact['contact_name']))
				//{pre($pre_selected . ',' . $contact['contact_title']." ".$contact['contact_name']);}
					$selected = ' selected = selected';
				else
					$selected = ' ';
				$contact_data['contact_drop'] .= '<option value="'.$contact['contact_title'].' '.$contact['contact_name'].'" ' . $selected .'>'.$contact['contact_title'].' '.$contact['contact_name']."</option>";
				$contact_data['contact_js'] .= "if(selection.value == '".$contact['contact_title']." ".$contact['contact_name']."'){ document.getElementById('position').value = '".$contact['contact_department']."' };\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
		$contact_data['contact_drop'] .= '</select>';
		$contact_data['contact_js'] .= "\n}\n</script>";
		return $contact_data;
	}

	public $org_id = NULL;
}
?>