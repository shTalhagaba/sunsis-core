<?php

class read_pfr implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link) {

		$_SESSION['bc']->add($link, "do.php?_action=read_pfr", "PFR Reconciler");

		// populate the contracts in Sunesis
		// for use in the selection of contracts to reconcile
		// ---
		$this->build_contracts($link);

		// check and create the header table if required.
		$reconciler = new Reconciler();
		$reconciler->createHeaderTable($link);
		$reconciler->createReportTable($link);

		$view = ViewReconcilerSnapshot::getInstance();
		$view->refresh($link, $_REQUEST);

		include('tpl_read_pfr.php');
	}

	/**
	 * Get all the contracts available on the system 
	 * to compare pfr data against
	 * @param PDO $link
	 */
	public function build_contracts(PDO $link) {
		$contract_predictor_object = GetContractsPredictor::getInstance();
		$st = $link->query($contract_predictor_object->getSQL());
		if( $st ) {
			while( $row = $st->fetch() ) {
				$this->contract_list[$row['id']] = $row['title'];
			}
		}
	}

	private function _display_contracts(PDO $link) {
	}

	/**
	 * Get all the files uploaded to the reconciler for this client
	 * TODO: limit this file list by user and / or date uploaded
	 * @param PDO $link
	 */
	private function _display_previous_files(PDO $link) {
		$files_sql = 'select import_id, filename, DATE_FORMAT(import_date,"%W, %e %M, %Y"), period from rpt_reconciler_header group by filename order by import_date desc';
		$previous_files = DAO::getResultset($link, $files_sql);
		if ( sizeof($previous_files) > 0 ) {
			echo '<p>Select a previously uploaded file:</p>';
			echo '<table class="resultset" cellpadding="6">';
			echo '<thead><tr><th>Filename</th><th>Uploaded</th></tr></thead>';
			echo '<tbody>';
			foreach ( $previous_files as $file_internal_id => $file_data ) {
				echo '<tr>';
				echo '<td><input id="filebutton'.$file_data[0].'" type="radio" title="'.$file_data[1].'" name="importfileid" value="'.$file_data[0].'" />'.$file_data[1].'(Period '.$file_data[3].')</td><td>'.$file_data[2].'</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '<p><strong>Or:</strong></p>';
		}
		else {
			echo '';
		}
	}
}