<?php
class support_requests implements IAction {

    private $user_auth;
    private $options;
    private $client;
    private $session_id;


    public function execute(PDO $link) {


        $export = isset($_REQUEST['export'])?$_REQUEST['export']:'';
        $filter_username = isset($_REQUEST['filter_username'])?$_REQUEST['filter_username']:'';
        if($filter_username == '')
            $filter_username = isset($_REQUEST['filter_username_preferences'])?$_REQUEST['filter_username_preferences']:'';

        $this->user_auth = array(
            "user_name"	=> "Sunesis Support ",
            "password"	=> md5("perspective"),
            "version"	=> ".01"
        );

        $case_status_collection = array(
            "New" => "New Requests",
            "Assigned" => "Being Looked Into By Perspective",
            "Reopened" => "Being Looked Into By Perspective",
            //"Validation" => "Being Looked Into By Perspective",
            "Awaiting Client" => "Requiring Your Feedback",
            "Awaiting Confirmation" => "Requiring Your Feedback",
            //"Awaiting Development TRAC" => "Under Consideration",
            //"Deployment" => "Under Consideration",
            "Development" => "Being Worked On",
            "Closed" => "Finished With",
            "Duplicate" => "Finished With",
            "On Hold" => "Under Consideration",
            // new case types
            //"Not Viable" => "Refused Development",
            "Chargeable Development" => "Bespoke Development"
        );

        $case_type_groups = array();
        $options = array();

        if(isset($_REQUEST['options']))
        {
            $options = null;

            $checked = $_REQUEST['options'];

            for($i=0; $i < count($checked); $i++)
            {
                if($checked[$i] == 'All')
                {
                    $options[] = $checked[$i];
                    $case_type_groups = $case_status_collection;
                    break;
                }
                else
                {
                    $options[] = $checked[$i];
                    if($checked[$i] == 'Assigned')
                    {
                        $case_type_groups['Reopened'] = $case_status_collection[$checked[$i]];
                        $case_type_groups['Validation'] = $case_status_collection[$checked[$i]];
                    }
                    if($checked[$i] == 'Awaiting Client')
                    {
                        $case_type_groups['Awaiting Confirmation'] = $case_status_collection[$checked[$i]];
                    }
                    if($checked[$i] == 'Deployment')
                    {
                        $case_type_groups['Awaiting Development TRAC'] = $case_status_collection[$checked[$i]];
                    }
                    if($checked[$i] == 'Closed')
                    {
                        $case_type_groups['Duplicate'] = $case_status_collection[$checked[$i]];
                        $case_type_groups['On Hold'] = $case_status_collection[$checked[$i]];
                    }
                    $case_type_groups[$checked[$i]] = $case_status_collection[$checked[$i]];
                }
            }
        }
        else
        {
            $case_type_groups = $case_status_collection;
        }


        $this->options = array(
            "location" 	=> 'https://sugar.perspective-uk.com/soap.php',
            "uri" 		=> 'https://sugar.perspective-uk.com/',
            "trace" 	=> 1
        );

        // set time and take an hour off to match sugar server???
        $page_load_timestamp = time()-(60*60);

        // create the soap client
        $this->getSoapClient();

        // do any updates requested
        $this->update_request();

        // ?? get the cases to find the types available
        // this is the super-admin override

        if ( $_SESSION['user']->isAdmin() || (DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER))
        {
            if($filter_username != '')
            {
                $filter_user_complete_name = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.username = '" . $filter_username . "'");
                $case = $this->client->get_entry_list($this->session_id, 'Cases',' `cases`.`created_by` = "ad7f7f13-2dcf-21c8-b931-4c8e1f495088" and `cases`.`name` = "Support request: '.$filter_user_complete_name.' of '.$_SESSION['user']->org->legal_name.'" ', ' `cases`.`status` desc, `cases`.`date_entered` desc ',0,'',2500);
            }
            else
                $case = $this->client->get_entry_list($this->session_id, 'Cases',' `cases`.`created_by` = "ad7f7f13-2dcf-21c8-b931-4c8e1f495088" and `cases`.`name` like "Support request: % of '.$_SESSION['user']->org->legal_name.'" ', ' `cases`.`status` desc, `cases`.`date_entered` desc ',0,'',2500);
        }
        else
            $case = $this->client->get_entry_list($this->session_id, 'Cases',' `cases`.`created_by` = "ad7f7f13-2dcf-21c8-b931-4c8e1f495088" and `cases`.`name` = "Support request: '.$_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' of '.$_SESSION['user']->org->legal_name.'" ', ' `cases`.`status` desc, `cases`.`date_entered` desc ',0,'',2500);

        //pre('`cases`.`name` = "Support request: '.$_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' of '.$_SESSION['user']->org->legal_name.'" ');
        $client_cases = array();

        $case_type = '';

        /* $case_type_groups = array(
            "New" => "New Requests",
            "Assigned" => "Being Looked Into By Perspective",
            "Reopened" => "Being Looked Into By Perspective",
            "Validation" => "Being Looked Into By Perspective",
            "Awaiting Client" => "Requiring Your Feedback",
            "Awaiting Confirmation" => "Requiring Your Feedback",
            "Awaiting Development TRAC" => "Under Consideration",
            "Deployment" => "Under Consideration",
            "Development" => "Being Worked On",
            "Closed" => "Finished With",
            "Duplicate" => "Finished With",
            "On Hold" => "Finished With",
            // new case types
            "Not Viable" => "Refused Development",
            "Chargeable Development" => "Bespoke Development"
        );*/
        $dataToExport = array();
        $case_group_data = array(
            "New Requests" => "<tr class='header-row' ><td colspan='5' >New Requests</td></tr>",
            "Being Looked Into By Perspective" => "<tr class='header-row' ><td colspan='5' >Being Looked Into By Perspective</td></tr>",
            "Requiring Your Feedback" => "<tr class='header-row' ><td colspan='5' >Requiring Your Feedback</td></tr>",
            "Under Consideration" => "<tr class='header-row' ><td colspan='5' >Under Consideration For Development</td></tr>",
            "Being Worked On" => "<tr class='header-row' ><td colspan='5' >Being Worked On</td></tr>",
            // new headers
            "Chargeable Development" => "<tr class='header-row' ><td colspan='5' >Bespoke Development</td></tr>",
            //"Not Viable" => "<tr class='header-row' ><td colspan='5' >Refused Development</td></tr>",
            // ---
            "Finished With" => "<tr class='header-row' ><td colspan='5' >Finished With</td></tr>"
        );

        $case_type_count = array(
            "New Requests" => 0,
            "Being Looked Into By Perspective" => 0,
            "Requiring Your Feedback" => 0,
            "Under Consideration" => 0,
            "Being Worked On" => 0,
            // new counters
            "Chargeable Development" => 0,
            //"Not Viable" => 0,
            // ---
            "Finished With" => 0
        );

        foreach( $case->entry_list as $field ) {

            //pre($case);
            $this_client_case = array();
            $this_case = '';
            foreach ( $field->name_value_list as $item ) {
                $this_client_case{$item->name} = $item->value;
            }
            if ( array_key_exists($this_client_case{'status'}, $case_type_groups) ) {

                $caseToExport = array();
                // add a class indicating the type of request - useful for future toggling
                $togglerefer_ahref = strtolower($this_client_case{'status'});
                $togglerefer_ahref = preg_replace('/ /', '', $togglerefer_ahref);

                $this_case .= "<tr class='case-info' >";
                $this_case .= "<td><strong>ID</strong>: ".$this_client_case{'case_number'}."</td>";
                $this_case .= "<td><strong>Raised</strong>: ".date('D d M y', strtotime($this_client_case{'date_entered'}))."</td>";
                $this_case .= "<td><strong>Type</strong>: ".$this_client_case{'case_type_c'}."</td>";
                $this_case .= "<td><strong>Category</strong>: ".$this_client_case{'category_c'}."</td>";
                $raisedBy = explode("of", $this_client_case{'name'});
                $raisedBy = substr($raisedBy[0],17);
                $this_case .= "<td><strong>Raised By</strong>: ".$raisedBy."</td>";
                $this_case .= "</tr>";

                $caseToExport['id'] = $this_client_case{'case_number'};
                $caseToExport['status'] = $this_client_case{'status'};
                $caseToExport['date'] = date('D d M y', strtotime($this_client_case{'date_entered'}));
                $caseToExport['type'] = $this_client_case{'case_type_c'};
                $caseToExport['cat'] = $this_client_case{'category_c'};
                $caseToExport['by'] = $raisedBy;


                if ( $case_type_groups{$this_client_case{'status'}} == 'Finished With' ) {
                    $this_case .= "<tr class='case-solution'>";
                    $this_case .= "<td colspan='2'><strong>Completed</strong></td>";
                    $this_case .= "<td><strong>Date</strong>: ".date('H:i D d M y', strtotime($this_client_case{'date_modified'}))."</td>";
                    $this_case .= "<td colspan='2'><strong>Resolution</strong>: ".$this_client_case{'resolution_type_c'}."</td>";
                    $this_case .= "</tr>";
                    $caseToExport['completed_date'] = date('H:i D d M y', strtotime($this_client_case{'date_modified'}));
                }

                $this_case .= "<tr>";
                $this_client_case{'description'} = preg_replace("/^Details:/", '<strong>Details</strong>:<br/>', $this_client_case{'description'} );
                $this_case .= "<td colspan='5'>".utf8_decode($this_client_case{'description'})."</td>";
                $this_case .= "</tr>";

                $caseToExport['description'] = preg_replace('/\n/', ' ', utf8_decode($this_client_case{'description'}));
                $caseToExport['description'] = preg_replace('/\n/', ' ', utf8_decode($this_client_case{'description'}));

                if ( $this_client_case{'resolution'} != '' ) {
                    $this_case .= "<tr class='case-solution'>";
                    $this_case .= "<td colspan='2'><strong>Feedback</strong></td>";
                    $this_case .= "<td><strong>Date</strong>: ".$this_client_case{'dateresolved_c'}."</td>";
                    $this_case .= "<td colspan='2'><strong>Type</strong>: ".$this_client_case{'resolution_type_c'}."</td>";
                    $this_case .= "</tr>";

                    $this_case .= "<tr class='case-info'>";
                    $this_case .= "<td colspan='5'><strong>Details</strong>:<br/>".preg_replace('/\n/', '<br/>', utf8_decode($this_client_case{'resolution'}))."</td>";
                    $this_case .= "</tr>";

                    $caseToExport['feedback_date'] =  $this_client_case{'dateresolved_c'};
                    $caseToExport['feedback'] = preg_replace('/\n/', ' ', utf8_decode($this_client_case{'resolution'}));
                }


                if ( $case_type_groups{$this_client_case{'status'}} == 'Requiring Your Feedback' || $case_type_groups{$this_client_case{'status'}} == 'Requiring Confirmation' ) {
                    $this_case .= "<tr class='case-feedback ".$togglerefer_ahref." c".$this_client_case{'case_number'}."' style='background-color: #fff;' >";
                    $this_case .= "<td colspan='5' style='background-color: #fff;'><input type='text' name='case-comment' id='case-comment-".$this_client_case{'case_number'}."' value='your comments...' style='width: 99%; color: #999;' onfocus='if (this.value == \"your comments...\") {this.value = \"\" ;}' onblur='if (this.value == \"\") {this.value = \"your comments...\";}'/></td>\n";
                    $this_case .= "</tr>";
                    $this_case .= "<tr>";
                    $this_case .= "<td colspan='5' style='background-color: #fff;'><input type='checkbox' id='close".$this_client_case{'case_number'}."' name='close".$this_client_case{'case_number'}."' value=1 style='width: auto!important;'>&nbsp;Please tick this box if you are happy with our solution and for the support request to be closed</td>\n";
                    $this_case .= "</td>";
                    $this_case .= "</tr>";
                    $this_case .= "<tr class='case-solution ".$togglerefer_ahref." c".$this_client_case{'case_number'}." ' style='background-color: #fff;' >";
                    $this_case .= "<td colspan='5'>";
                    $this_case .= "<button id='do.php?_action=support_requests&amp;header=1&amp;close-case=".$this_client_case{'id'}."&amp;case_number=".$this_client_case{'case_number'}."&amp;ts=".$page_load_timestamp."&amp;case-comment=' class='change-status r".$this_client_case{'case_number'}."' style='float:right;' >Send us your comments</button>\n";
                    $this_case .= "</td>\n";
                    $this_case .= "</tr>";
                }

                $this_case .= "<tr>";
                $this_case .= "<td colspan='5' style='border: none;' >&nbsp;</td>";
                $this_case .= "</tr>";

                $case_group_data{$case_type_groups{$this_client_case{'status'}}} .= $this_case;
                $case_type_count{$case_type_groups{$this_client_case{'status'}}}++;

                $dataToExport[] = $caseToExport;
            }
        }

        $cases_table = '<table id="cases" ><tbody>';
        foreach ( $case_group_data as $this_case_type => $this_case_data ) {
            if( $case_type_count{$this_case_type} > 0 ) {
                $cases_table .= $this_case_data;
                $cases_table .= "<tr><td colspan='5' style='border-bottom: none;' >&nbsp;</td></tr>";
            }
        }
        $cases_table .= "</tbody></table>";

        /**
         *
         * Build the summary of Support Request
         */

        $case_type_count = array(
            "New Requests" => 0,
            "Being Looked Into By Perspective" => 0,
            "Requiring Your Feedback" => 0,
            "Under Consideration" => 0,
            "Being Worked On" => 0,
            // new counters
            "Chargeable Development" => 0,
            //"Not Viable" => 0,
            // ---
            "Finished With" => 0
        );

        foreach( $case->entry_list as $field ) {

            $this_client_case = array();
            $this_case = '';
            foreach ( $field->name_value_list as $item ) {
                $this_client_case{$item->name} = $item->value;
            }
            if ( array_key_exists($this_client_case{'status'}, $case_status_collection) ) {

                $case_type_count{$case_status_collection{$this_client_case{'status'}}}++;
            }
        }


        $summary_html = '<table>';

        foreach ( $case_type_count as $this_case_type => $this_case_count ) {

            $summary_html .= '<tr><td style="font-size:1.4em; font-weight: bold; ">'.$this_case_count.'</td><td> requests are <strong>'.$this_case_type.'</strong></td></tr>';
        }

        $summary_html .= '</table>';

        if($export=='csv')
        {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="support_requests.csv"');

            // Internet Explorer requires two extra headers when downloading files over HTTPS
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "ID, Date, Status, Type, Category, Raised By, Description, Completion Date, Feedback Date, Feedback";
            echo "\r\n";
            foreach($dataToExport AS $supp)
            {
                $supp['description'] = preg_replace('/<[^>]*>/', '', $supp['description']);
                $supp['description'] = str_replace(',','',$supp['description']);
                if(isset($supp['feedback']))
                {
                    $supp['feedback'] = preg_replace('/<[^>]*>/', '', $supp['feedback']);
                    $supp['feedback'] = str_replace(',','',$supp['feedback']);
                    $supp['feedback'] = str_replace('&#039;','"',$supp['feedback']);
                }
                $supp['id'] = isset($supp['id'])?$supp['id']:' ';
                $supp['date'] = isset($supp['date'])?$supp['date']:' ';
                $supp['status'] = isset($supp['status'])?$case_status_collection[$supp['status']]:' ';
                $supp['type'] = isset($supp['type'])?$supp['type']:' ';
                $supp['cat'] = isset($supp['cat'])?$supp['cat']:' ';
                $supp['by'] = isset($supp['by'])?$supp['by']:' ';
                $supp['description'] = isset($supp['description'])?$supp['description']:' ';
                $supp['completed_date'] = isset($supp['completed_date'])?$supp['completed_date']:' ';
                $supp['feedback_date'] = isset($supp['feedback_date'])?$supp['feedback_date']:' ';
                $supp['feedback'] = isset($supp['feedback'])?$supp['feedback']:' ';
                echo $supp['id'] . "," . $supp['date'] . "," . $supp['status'] . "," . $supp['type'] . "," . $supp['cat'] . "," . $supp['by'] . "," . $supp['description'] . "," . $supp['completed_date'] . "," . $supp['feedback_date'] . "," . $supp['feedback'] . "\r\n";
            }
            echo "\r\n";
        }
        else
            include "tpl_support_requests.php";
        $response = $this->client->logout($this->session_id);
    }

    /**
     *
     * Error output
     * @param unknown_type $value
     */
    private function debug($value) {
        echo "<pre>";
        print_r($value);
        die;
    }

    /**
     *
     * Enter description here ...
     */
    private function getSoapClient() {
        $this->client = new SoapClient(null, $this->options);
        try {
            $response = $this->client->login($this->user_auth,"test");
        }
        catch (SoapFault $e) {
            return false;
        }
        $this->session_id = $response->id;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $fieldName
     */
    private function getField($fieldName) {

        if( isset($_POST[$fieldName]) ) {
            return($_POST[$fieldName]);
        }
        else if( isset($_SESSION['user']->$fieldName) ) {
            return $_SESSION['user']->$fieldName;
        }
        else if( isset($_SESSION['org']->$fieldName) ) {
            return $_SESSION['org']->$fieldName;
        }
    }

    private function update_request() {

        if ( isset($_REQUEST['close-case']) && $_REQUEST['close-case'] != '' ) {

            if ( strval(intval($_REQUEST['case_number'])) === $_REQUEST['case_number'] && $_REQUEST['case_number'] >= 10000 && $_REQUEST['case_number'] <= 99999 ) {
                $result = $this->client->get_entry($this->session_id, 'Cases', $_REQUEST['close-case']);
                /*
                        0 => assigned_user_name
                        1 => modified_by_name
                        2 => created_by_name
                        3 => id
                        4 => name
                        5 => date_entered
                        6 => date_modified
                        7 => modified_user_id
                        8 => created_by
                        9 => description
                        10 => deleted
                        11 => assigned_user_id
                        12 => case_number
                        13 => type
                        14 => status
                        15 => priority
                        16 => resolution
                        17 => work_log
                        18 => account_name
                        19 => account_id
                        20 => contacts_cases_1_name
                        21 => case_type_c
                        22 => category_c
                        23 => contact_c
                        24 => dateresolved_c
                        25 => due_date_c
                        26 => goldmine_c
                        27 => newsletter_c
                        28 => origin_c
                        29 => priority_c
                        30 => product_c
                        31 => resolution_type_c
                        32 => resolved_c
                        33 => time_taken_c
                        34 => trac_ticket_link_c
                        35 => trac_ticket_no_c
                        36 => internal_notes_c
                     */
                // check case hasn't been modified more recently than the page loaded
                /*				if ( !isset($_REQUEST['ts']) ) {
                                    return 'No update has occurred - we cannot verify the date of this request. We have reloaded the page for you';
                                    exit;
                                }
                                // modified date is more recent than page load timestamp
                                elseif (strtotime($result->entry_list[0]->name_value_list[6]->value) >= $_REQUEST['ts'] ) {
                                    return 'No update has occurred - this case has been modified since you loaded the page. We have reloaded the page for you';
                                }
                */
                // resolution text
                $update_text = '';
                $result->entry_list[0]->name_value_list[14]->value = 'Reopened';
                $result->entry_list[0]->name_value_list[32]->value = 1;


                if ( isset($_REQUEST['case-comment']) && $_REQUEST['case-comment'] != '' ) {
                    $update_text = utf8_encode(htmlspecialchars($_REQUEST['case-comment']));
                    $result->entry_list[0]->name_value_list[16]->value .= "\n".date("d/m/Y").": ".$update_text." by ".$_SESSION['user']->firstnames." ".$_SESSION['user']->surname;
                }

                if ( isset($_REQUEST['case-finished']) && $_REQUEST['case-finished'] == 1 ) {
                    $result->entry_list[0]->name_value_list[14]->value = 'Closed';
                    $result->entry_list[0]->name_value_list[16]->value .= "\n".date("d/m/Y").": Case has been closed by ".$_SESSION['user']->firstnames." ".$_SESSION['user']->surname;
                }

                // date of change
                $result->entry_list[0]->name_value_list[24]->value = date("Y-m-d");

                $update_case_content = array();
                foreach ($result->entry_list[0]->name_value_list as $id => $data ) {
                    $update_case_content[] = array("name"=>$data->name, "value"=>$data->value);
                }

                $result = $this->client->set_entry($this->session_id, 'Cases', $update_case_content);

                // send mail to the client.....
                // find the email address....
                return;
            }
        }
    }
}
