<?php
/**
 * Created By: Perspective Ltd
 * User: Richard Elmes
 * Date: 26/06/12
 * Time: 09:20
 */
class ViewReconcilerMissing extends View {

	public static function getInstance($import_id, $table_name = 'sunesis')	{
		$key = 'view_'.__CLASS__.'_'.$table_name;

		$sql = "SELECT * from rpt_reconciler_".$table_name." where record_status = 2 and import_id = ".$import_id;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$view = $_SESSION[$key] = new ViewReconcilerMissing();
			$view->table_name = $table_name;

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}
		else {
			$view = $_SESSION[$key];
		}

		// changed this to always lookup rpt_reconciler_sunesis - as this table will always contain all Sunesis based contracts.
		// PFR was causing an issue as not all contracts exist in the PFR file.
		$options = "SELECT contracts.id, contracts.title, null, CONCAT('WHERE contract_id=', contracts.id) FROM contracts, rpt_reconciler_sunesis where contracts.id = rpt_reconciler_sunesis.contract_id AND rpt_reconciler_sunesis.import_id = {$import_id} group by contracts.id";
		$f = new DropDownViewFilter('filter_contracts', $options, null, true);
		$f->setDescriptionFormat("Contract: %s");
		$view->addFilter($f);

		$view->setSQL($sql);

		return $_SESSION[$key];
	}

	/**
	 * @param PDO $link
	 */
	public function render(PDO $link) {

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if( $st )	{
			$missing_totaliser = 0;

			echo $this->getViewNavigator();

			echo "<table style='resultset' cellpadding='6'><thead><tr>";
			echo "<th>Learner</th>";
			echo "<th>Learning Aim</th>";
			echo "<th>Contract</th>";
			echo "<th style='text-align:right;'>Sunesis Value</th>";
			echo "<th style='text-align:right;'>PFR Value</th>";
			echo "<th style='text-align:right;'>Discrepancy</th>";
			echo "</tr></thead>";
			echo "<tbody>";

			// remove any zero lined records
			while( $row = $st->fetch() ) {

				// number of lines found in PFR but not in Sunesis with no cash
				$number_missing_lines = 0;

				$contract_name = 'unmatched contract';
				if ( $row['contract_id'] != '' ) {
					$get_missing_contract_query = 'SELECT title FROM contracts where id = '.$row['contract_id'];
					$contract_name = DAO::getSingleValue($link, $get_missing_contract_query);
				}

				echo '<tr class="data-display" id="mid'.$row['id'].'_type';
				if ( $this->table_name == 'sunesis' ) {
					echo 's';
				}
				else {
					echo 'p';
				}
				echo '" onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};" title="" ';
				echo ' onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};"</tr>';

				echo "<td>";
				if ( $row['name'] != NULL ) {
					echo $row['name'].'<br/>';
				}
				else {
					// pull out the learner name if possible L03 ....
					echo 'unmatched learner reference:<br/>';
				}
				echo $row['L03'].'<br/>';

				// we could do a look up on the learner here?
				// ---
				echo '</td>';
				echo '<td>'.$row['A09'].'</td>';

				echo '<td>'.$contract_name.'</td>';
				if ( $this->table_name == 'sunesis' ) {
					echo '<td style="text-align:right;">'.$this->_formatCash($row['grand_total'])."</td>";
					echo '<td style="text-align:right;">&pound;0.00</td>';
				}
				else {
					echo '<td style="text-align:right;">&pound;0.00</td>';
					echo '<td style="text-align:right;">'.$this->_formatCash($row['grand_total'])."</td>";
				}
				echo "<td style='text-align:right;'>".$this->_formatCash($row['grand_total'])."</td></tr>";
			}
			echo "</tbody>";
			// echo "<tfoot><th colspan='5'>&nbsp;</th><th style='text-align:right;'>Total</th><th style='text-align:right;'>".$this->_formatCash($this->pfr_missing_totaliser)."</th></tfoot>";
			echo "</table>";
		}
	}

	private function _formatCash($value) {
		return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
	}

	public $table_name = NULL;
}

?>