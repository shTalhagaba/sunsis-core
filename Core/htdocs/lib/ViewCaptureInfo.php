<?php
/**
 * User: Richard Elmes
 * Date: 10/05/12
 * Time: 14:07
 */

class ViewCaptureInfo extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	users_capture_info.*
FROM
	users_capture_info
ORDER BY
	users_capture_info.infogroupid asc
HEREDOC;

			$view = $_SESSION[$key] = new ViewCaptureInfo();
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

			// SELECT	DISTINCT(users_capture_info.infogroupname) ROM	users_capture_info
			$options = "SELECT users_capture_info.infogroupname, users_capture_info.infogroupname, null, CONCAT('WHERE users_capture_info.infogroupname=',char(39),users_capture_info.infogroupname,char(39)) FROM users_capture_info";
			$f = new DropDownViewFilter('filter_groupname', $options, null, true);
			$f->setDescriptionFormat("Question Group: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];

	}


	public function render(PDO $link) {
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if($st)	{

			$gn_dropdown = 'select users_capture_info.infogroupid, users_capture_info.infogroupname from users_capture_info group by users_capture_info.infogroupid order by users_capture_info.infogroupname asc;';
			$gn_dropdown = DAO::getResultset($link, $gn_dropdown);

			$opt_dropdown = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "users_capture_info" AND COLUMN_NAME = "userinfotype"';
			$opt_dropdown = DAO::getSingleValue($link, $opt_dropdown);
			$opt_replace = array('enum(','\'',')');
			$opt_dropdown = str_replace($opt_replace,'', $opt_dropdown);
			$opt_dropdown = explode(',', $opt_dropdown);
			$opt_formatted = array();
			foreach($opt_dropdown as $opt_id => $opt_value) {
				$opt_formatted[] = array($opt_value,$opt_value);
			}
			// echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>Section</th><th>Question</th><th>Type of Answer</th><th>Compulsory?</th><th>Values Available</th><th>Value Scores</th></tr></thead>';

			echo '<tbody>';
			while( $row = $st->fetch() ) {
				echo '<td align="left">'.HTML::select($row['userinfoid'].'_section', $gn_dropdown, $row['infogroupid'], true, false)."</td>";
				echo '<td align="left"><textarea cols="60" rows="5" name="'.$row['userinfoid'].'_question" >'.$row['userinfoname']."</textarea></td>";
				echo '<td align="left">'.HTML::select($row['userinfoid'].'_type', $opt_formatted, $row['userinfotype'], true, false)."</td>";
				echo '<td align="left">'.HTML::checkbox($row['userinfoid'].'_comp', $row['compulsory'], $row['compulsory'])."</td>";
				echo '<td align="left"><textarea name="'.$row['userinfoid'].'_lookup" >'.$row['lookupvalues']."</textarea></td>";
				echo '<td align="left"><textarea name="'.$row['userinfoid'].'_scores" >'.$row['scorevalues']."</textarea></td>";

				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
			// echo $this->getViewNavigator('left');

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function render_edit(PDO $link, $current_id = '') {
			/* @var $result pdo_result */
			$st = $link->query($this->getSQL());

			if($st)	{

				$gn_dropdown = $this->_getSectorSections($link);

				$opt_dropdown = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "users_capture_info" AND COLUMN_NAME = "userinfotype"';
				$opt_dropdown = DAO::getSingleValue($link, $opt_dropdown);
				$opt_replace = array('enum(','\'',')');
				$opt_dropdown = str_replace($opt_replace,'', $opt_dropdown);
				$opt_dropdown = explode(',', $opt_dropdown);
				$opt_formatted = array();
				foreach($opt_dropdown as $opt_id => $opt_value) {
					$opt_formatted[] = array($opt_value,$opt_value);
				}
				// echo $this->getViewNavigator('left');
				echo '<div align="left">';

				$current_group = '';

				while( $row = $st->fetch() ) {
					echo '<form action="do.php">';
					echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
					echo '<tbody>';

					if ( $current_group != $row['infogroupname'] ) {
						echo '<tr><td colspan="5"><h1>'.$row['infogroupname'].'</h1></td></tr>';
						echo '<tr><th>Question</th><th>Values Available</th><th>Value Scores</th><th>Question Options</th></tr>';
						$current_group = $row['infogroupname'];
					}
					$this_question = CaptureInfo::loadFromDatabase($link, $row['userinfoid']);

					// set highlights for amended / created row
					$this_row_style = '';
					if ( isset($current_id) && $current_id != '' && $current_id == $row['userinfoid'] ) {
						$this_row_style = 'background-color: #E0EAD0;';
					}
					echo '<tr style="'.$this_row_style.'">';
					echo '<td align="left" style="'.$this_row_style.'">';
					echo '<input type="hidden" name="update_question" value="'.$row['userinfoid'].'" />';
					echo '<input type="hidden" name="userinfoid" value="'.$row['userinfoid'].'" />';
					echo '<input type="hidden" name="_action" value="view_captureinfo" />';
					echo '<textarea cols="60" rows="5" name="userinfoname" >'.$row['userinfoname'].'</textarea></td>';
					echo '<td align="left" style="'.$this_row_style.'"><textarea rows="5" name="lookupvalues" >'.$row['lookupvalues'].'</textarea></td>';
					echo '<td align="left" style="'.$this_row_style.'"><textarea rows="5" name="scorevalues" >'.$row['scorevalues'].'</textarea></td>';
					echo '<td align="left" style="'.$this_row_style.'">';
					echo '<table>';
					echo '<tr><td style="'.$this_row_style.'">Section:</td><td colspan="4" style="'.$this_row_style.'">'.HTML::select('infogroupid', $gn_dropdown, $row['infogroupid'], true, false).'</td></tr>';
					echo '<tr>';
					echo '<td style="'.$this_row_style.'">Compulsory?</td><td style="'.$this_row_style.'">'.HTML::checkbox('compulsory', $row['compulsory'], $row['compulsory']).'</td>';
					echo '<td style="'.$this_row_style.'">Type of Answer:</td><td style="'.$this_row_style.'">'.HTML::select('userinfotype', $opt_formatted, $row['userinfotype'], false, false).'</td>';
					echo '<td><input type="submit" name="save" value="save" />';
					if ( $this_question->isSafeToDelete($link) ) {
						echo '<input type="submit" name="remove" value="remove" />';
					}
					echo '</td>';
					echo '</tr>';
					echo '</table>';
					echo "</td>";
					echo '</tr>';
					echo '</tbody>';
					echo '</table>';
					echo '</form>';
				}
				echo '</div>';
				// echo $this->getViewNavigator('left');

			}
			else
			{
				throw new DatabaseException($link, $this->getSQL());
			}

	}

	public function render_new_form(PDO $link) {

			$gn_dropdown = $this->_getSectorSections($link);

			$opt_dropdown = 'SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "users_capture_info" AND COLUMN_NAME = "userinfotype"';
			$opt_dropdown = DAO::getSingleValue($link, $opt_dropdown);
			$opt_replace = array('enum(','\'',')');
			$opt_dropdown = str_replace($opt_replace,'', $opt_dropdown);
			$opt_dropdown = explode(',', $opt_dropdown);
			$opt_formatted = array();
			foreach($opt_dropdown as $opt_id => $opt_value) {
				$opt_formatted[] = array($opt_value,$opt_value);
			}
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>Question</th><th>Values Available</th><th>Value Scores</th><th>Presentation Information</th></tr></thead>';

			echo '<tbody><tr><td>';
			echo '<form action="do.php">';
			echo '<input type="hidden" name="update_question" value="1" />';

			echo '<input type="hidden" name="_action" value="view_captureinfo" />';
			echo '<textarea cols="60" rows="5" name="userinfoname" ></textarea></td>';
			echo '<td align="left"><textarea rows="5" name="lookupvalues" ></textarea></td>';
			echo '<td align="left"><textarea rows="5" name="scorevalues" ></textarea></td>';
			echo '<td align="left">';
			echo '<table>';
			echo '<tr><td>Section:</td><td colspan="4">'.HTML::select('infogroupid', $gn_dropdown, '', true, false).'</td></tr>';
			echo '<tr>';
			echo '<td>Compulsory?</td><td>'.HTML::checkbox('compulsory', '', '').'</td>';
			echo '<td>Type of Answer:</td><td>'.HTML::select('userinfotype', $opt_formatted, '', true, false).'</td>';
			echo '<td><input type="submit" name="save" value="save" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';
			echo "</td>";
			echo '</tr>';
			echo '</tbody></table></div>';
	}

	private function _getSectorSections(PDO $link) {

		$gn_dropdown = 'select users_capture_info.infogroupid, users_capture_info.infogroupname from users_capture_info group by users_capture_info.infogroupid order by users_capture_info.infogroupname asc;';
		$gn_dropdown = DAO::getResultset($link, $gn_dropdown);

		$vt_dropdown = 'select concat("Additional Information - ", lookup_vacancy_type.description), concat("* Additional Information - ", lookup_vacancy_type.description) from lookup_vacancy_type';
		$vt_dropdown = DAO::getResultset($link, $vt_dropdown);

		$temp_array = array();
		foreach ( $gn_dropdown as $gn_pos => $gn_data ) {
			$temp_array[trim($gn_data[1])] = 1;
		}

		foreach ( $vt_dropdown as $vt_pos => $vt_data ) {
			if ( array_key_exists(trim($vt_data[0]), $temp_array) ) {
				unset($vt_dropdown[$vt_pos]);
			}
		}

		return array_merge($gn_dropdown,$vt_dropdown);
	}
}
?>