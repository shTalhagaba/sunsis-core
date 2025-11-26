<?php
class active_campaign implements IAction
{
    public function execute(PDO $link)
    {
        $emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';
        if($emp_id>0)
        {
            $this->CreateInSunesis($link, $emp_id);
        }

        $this->updateEmployers($link, $this->getEmployers("https://city-skills.api-us1.com/api/3/accounts?limit=100&count_deals=true&offset=700"));
        include('tpl_active_campaign.php');
    }

    public function getEmployers($url)
    {
        $headers = $this->getHeaders();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        return($result);
    }

    public function updateEmployers($link, $response)
    {
        $result = json_decode($response, true);
        //pre($result);
        $html="";
        $employers = array();
        foreach($result as $accounts)
        {
            for($i = 0; $i<sizeof($accounts); $i++)
            {
                if(isset($accounts[$i]) and isset($accounts[$i]['id']))
                {
                    $add1 = "";
                    $add2 = "";
                    $add3 = "";
                    $add4 = "";
                    $postcode = "";
                    $mobile = "";
                    $pc_name = "";
                    $pc_mobile = "";
                    $pc_email = "";
                    $edrs = "";
                    $ID = "";
                    $Name = "";
                    $ID=$accounts[$i]['id'];
                    $Name = $accounts[$i]['name'];
                    $addresses = json_decode($this->getCustomFields($accounts[$i]['links']['accountCustomFieldData']), true);
                    foreach($addresses as $address)
                    {
                        for($j=0; $j<sizeof($address); $j++)
                        {
                            if(isset($address[$j]['custom_field_id']))
                            {
                                if($address[$j]['custom_field_id']==2)
                                    $add1 = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==4)
                                    $add2 = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==5)
                                    $add3 = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==7)
                                    $add4 = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==11)
                                    $mobile = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==13)
                                    $pc_name = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==14)
                                    $pc_email = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==6)
                                    $postcode = $address[$j]['custom_field_text_value'];
                                if($address[$j]['custom_field_id']==33)
                                    $edrs = $address[$j]['custom_field_text_value'];
                            }
                        }
                    }
                    $emp = new StdClass();
                    $emp->Id = $ID;
                    $emp->Name = $Name;
                    $emp->Add1 = $add1;
                    $emp->Add2 = $add2;
                    $emp->Add3 = $add3;
                    $emp->Add4 = $add4;
                    $emp->Postcode = $postcode;
                    $emp->EDRS = $edrs;
                    $emp->PC_Name = $pc_name;
                    $emp->PC_Mobile = $mobile;
                    $emp->PC_Email = $pc_email;
                    DAO::saveObjectToTable($link, 'ac_employers', $emp);
                }
            }
        }
    }

    public function CreateInSunesis($link, $id)
    {
        $ac_employer = DAO::getObject($link, "select * from ac_employers where Id = '$id'");
        $emp = new StdClass();
        $loc = new StdClass();
        $emp->id = NULL;
        $emp->organisation_type = 2;
        $emp->legal_name = $ac_employer->Name;
        $emp->trading_name = $ac_employer->Name;
        $emp->short_name = substr($ac_employer->Name, 0 , 20);
        $emp->active = 1;
        $res = DAO::saveObjectToTable($link, 'organisations', $emp);
        $loc->id = NULL;
        $loc->organisations_id = DAO::getSingleValue($link, "select max(id) from organisations");
        $loc->is_legal_address = 1;
        $loc->full_name = "Main Site";
        $loc->short_name = "main site";
        $loc->address_line_1 = $ac_employer->Add1;
        $loc->address_line_2 = $ac_employer->Add2;
        $loc->address_line_3 = $ac_employer->Add3;
        $loc->address_line_4 = $ac_employer->Add4;
        $loc->full_name = "Main Site";
        $loc->postcode = $ac_employer->Postcode;
        $loc->line1 = $ac_employer->Add1;
        $loc->line2 = $ac_employer->Add2;
        $loc->line3 = $ac_employer->Add3;
        $loc->line4 = $ac_employer->Add4;
        $loc->contact_name = $ac_employer->PC_Name;
        $loc->contact_mobile = $ac_employer->PC_Mobile;
        $loc->contact_email = $ac_employer->PC_Email;
        $res = DAO::saveObjectToTable($link, 'locations', $loc);

        DAO::execute($link, "update ac_employers inner join organisations on organisations.legal_name = ac_employers.Name set Sunesis = organisations.id");
    }

    public function getHeaders()
    {
        return $headers = [
            "Content-type:application/json",
            "Api-Token: efba70d3e0ea42f2721f7ac4497d34fce51946a2c0da44f410457db478e616fff764915f",
            "Accept: application/json;"
        ];
    }

    public function getCustomFields($url)
    {
        $headers = $this->getHeaders();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        return($result);

    }
}