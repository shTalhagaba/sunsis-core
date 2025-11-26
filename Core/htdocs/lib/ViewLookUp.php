<?php
/**
 * User: Richard Elmes
 * Date: 11/05/12
 * Time: 14:07
 */

class ViewLookUp extends View
{

	public static function getInstance($link, $table_name = '' )
	{
		$key = 'view_'.__CLASS__;

		$key_desc = 'id';
		$val_desc = 'description';

		// check if the table has an id or code as the incrementor
		$column_definition_sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "lookup_'.$table_name.'"';
		$column_definition = DAO::getResultset($link, $column_definition_sql, DAO::FETCH_ASSOC);
		// use the first column as the key? bit wooly this...
		if ( isset($column_definition[0]['COLUMN_NAME']) ) {
			$key_desc = $column_definition[0]['COLUMN_NAME'];
		}

		// Create new view object
		$sql = <<<HEREDOC
SELECT
	'{$table_name}' as table_name,
	lookup_{$table_name}.*
FROM
	lookup_{$table_name}
ORDER BY
	lookup_{$table_name}.{$key_desc} asc
HEREDOC;
		$view = $_SESSION[$key] = new ViewLookUp();
		$view->setSQL($sql);

		foreach ( $column_definition as $col_position => $col_data ) {
			$view->table_makeup[] = $col_data;
		}

		// Add view filters
		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $_SESSION[$key];
	}


	public function render(PDO $link) {
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if($st)	{

			echo $this->getViewNavigator('left');
			echo '<div align="left" id="existing_entries">';
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th>Save<p style="font-weight: normal; font-size: 10px;">&nbsp;</p></th>';
			foreach ( $this->table_makeup as $col_id => $col_name ) {
				echo '<th>'.$col_name['COLUMN_NAME'].'<p style="font-weight: normal; font-size: 10px;" >'.$col_name['DATA_TYPE'].' '.$col_name['COLUMN_COMMENT'].'</p></th>';
			}
			echo '<th>Save<p style="font-weight: normal; font-size: 10px;">&nbsp;</p></th>';
			echo '</tr></thead>';
			echo '<tbody>';

			while( $row = $st->fetch() ) {
				$row_style = '';
				if ( isset($_REQUEST['id']) && $row['id'] == $_REQUEST['id'] ) {
					$row_style = 'background-color: #E0EAD0';
				}

				echo '<tr style="'.$row_style.'" >';
				echo '<td style="'.$row_style.'" align="left">';
				echo '<form action="do.php">';
				echo '<input type="submit" name="save_value" value="save" />';
				echo '<input type="hidden" name="_action" value="view_lookups" />';
				echo '<input type="hidden" name="table_name" value="'.$row['table_name'].'" />';
				echo '</td>';
				foreach ( $this->table_makeup as $col_id => $col_name ) {
					if ( $col_name['EXTRA'] == 'auto_increment' ) {
						echo '<td style="'.$row_style.'" align="left">'.$row[$col_name['COLUMN_NAME']].'<input type="hidden" name="'.$col_name['COLUMN_NAME'].'" value="'.$row[$col_name['COLUMN_NAME']].'" /></td>';
					}
					else {
						echo '<td style="'.$row_style.'" align="left"><textarea rows="1" cols="20" name="'.$col_name['COLUMN_NAME'].'">'.$row[$col_name['COLUMN_NAME']].'</textarea></td>';
					}

					if ( $col_name['COLUMN_KEY'] == 'PRI' ) {
						$this->table_has_primary_key = 1;
					}
				}
				echo '<td style="'.$row_style.'" >';
				echo '<input type="submit" name="save_value" value="save" />';
				echo '</form>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo $this->getViewNavigator('left');
		}
		else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function render_new_form(PDO $link, $table_name = '') {
		echo '<div align="left">';
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr>';
		echo '<th>Save<p style="font-weight: normal; font-size: 10px;">&nbsp;</p></th>';
		foreach ( $this->table_makeup as $col_id => $col_name ) {
			echo '<th>'.$col_name['COLUMN_NAME'].'<p style="font-weight: normal; font-size: 10px;" >'.$col_name['DATA_TYPE'].' '.$col_name['COLUMN_COMMENT'].'</p></th>';
		}
		echo '<th>Save<p style="font-weight: normal; font-size: 10px;">&nbsp;</p></th>';
		echo '</tr></thead>';
		echo '<tbody>';
		echo '<tr>';
		echo '<td align="left">';
		echo '<form action="do.php">';
		echo '<input type="submit" name="save_value" value="save" />';
		echo '<input type="hidden" name="_action" value="view_lookups" />';
		echo '<input type="hidden" name="table_name" value="'.$table_name.'" />';
		echo '<input type="hidden" name="id_col" value="" />';
		echo '<input type="hidden" name="desc_col" value="" />';
		echo '</td>';
		foreach ( $this->table_makeup as $col_id => $col_name ) {
			if ( $col_name['EXTRA'] == 'auto_increment' ) {
				echo '<td align="left">auto generated</td>';
			}
			else {
				echo '<td align="left"><textarea rows="1" cols="20" name="'.$col_name['COLUMN_NAME'].'"></textarea></td>';
			}
		}
		echo '<td ><input type="submit" name="save_value" value="save" />';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';

	}

	public function display_lookup_tables(PDO $link) {

		$table_sql = 'SELECT REPLACE(TABLE_NAME, "lookup_", ""), TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME LIKE "lookup\_%" order by TABLE_NAME asc';
		$table_list = DAO::getResultSet($link, $table_sql);

		// remove any lookups not conforming to the standard layout
		foreach ( $table_list as $tbl_id => $tbl_data ) {
			if ( !$this->_check_table_setup($link, $tbl_data[1]) ) {
				unset($table_list[$tbl_id]);
			}
		}
		return HTML::select('table_name', $table_list, '', true, false);
	}

	public function display_table_comments(PDO $link, $table_name = '' ) {

		if ( $table_name == '' ) {
			return;
		}

		$table_sql = 'SELECT TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "lookup_'.$table_name.'"';
		return(DAO::getSingleValue($link, $table_sql));
	}

	private function _check_table_setup(PDO $link, $table_name = '') {

		if ( $table_name == '' ) {
			return 0;
		}
		// check if the table has an id or code as the incrementor
		$column_definition_sql = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "'.$table_name.'"';
		$column_definition = DAO::getResultset($link, $column_definition_sql);
		// if ( sizeof($column_definition) == 2 ) {
		 	return 1;
		// }
		// return sizeof($column_definition);
	}

	public $table_makeup = array();
	public $table_has_primary_key = 0;
}
?>