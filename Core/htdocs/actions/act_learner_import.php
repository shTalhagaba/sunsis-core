<?php
set_time_limit(0);
ini_set('memory_limit','2048M');
class learner_import implements IAction
{
    public function execute(PDO $link)
    {
        if ($_FILES['file']['error'] == UPLOAD_ERR_OK               //checks for errors
            && is_uploaded_file($_FILES['file']['tmp_name']))
        { //checks that file is uploaded
            $file_handle = fopen($_FILES['file']['tmp_name'], "r");

            if( substr(strrchr($_FILES['file']['name'],'.'),1) == 'xls' )
            {
                $csvFile = DATA_ROOT . '/uploads/' . DB_NAME . '/Learners.csv';
                file_put_contents($csvFile, $this->convertToCSV($_FILES['file']['tmp_name']));
                gc_collect_cycles();
                $file_handle = fopen($csvFile, "r");
            }
        }

        $line_of_text = fgetcsv($file_handle);
        if($line_of_text[0]!="ApprenticeshipEnrolmentForm_Id")
            pre("Invalid File");
        while (!feof($file_handle) )
        {
            $grandTotal = "";
            $line_of_text = fgetcsv($file_handle);

            if(isset($line_of_text[6]) && trim($line_of_text[6])!="")
            {
                $exists = DAO::getSingleValue($link, "select id from users where ni = '$line_of_text[6]'");
                if(!$exists)
                {
                    $learner = new User();
                    $learner->username = strtolower(mb_convert_encoding($line_of_text[2],'UTF-8')) . "." . strtolower(mb_convert_encoding($line_of_text[3],'UTF-8'));
                    $learner->firstnames = mb_convert_encoding($line_of_text[2],'UTF-8');
                    $learner->surname= mb_convert_encoding($line_of_text[3],'UTF-8');

                    $employers = explode("-",$line_of_text[39]);
                    if(!isset($employers[2]))
                        pre($line_of_text);
                    $edrs = trim($employers[2]);

                    $emp_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$edrs'");
                    if($emp_id>0)
                    {
                        $learner->employer_id = $emp_id;
                        $loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id' limit 0,1");
                        $location = Location::loadFromDatabase($link, $loc_id);
                        if(!isset($location->id))
                        {
                            $this->WriteLog($link, $line_of_text[6], "Please create location!");
                            continue;
                        }
                        $learner->employer_location_id = $location->id;
                        $learner->record_status = 1;
                        $learner->web_access = 0;
                        $learner->dob = Date::toMySQL($line_of_text[4]);
                        $learner->ni = $line_of_text[6];
                        $learner->gender = substr($line_of_text[5],0,1);
                        // Ethnicity to be numbers
                        $learner->work_address_line1 = $location->address_line_1;
                        $learner->work_address_line2 = $location->address_line_2;
                        $learner->work_address_line3 = $location->address_line_3;
                        $learner->work_address_line4 = $location->address_line_4;
                        $learner->work_postcode = $location->postcode;
                        $learner->work_telephone = $location->telephone;
                        $learner->work_mobile = $location->contact_mobile;
                        $learner->work_fax = $location->fax;
                        $learner->work_email = $location->contact_email;
                        $learner->home_address_line_1 = $line_of_text[10];
                        $learner->home_address_line_1 = $line_of_text[11];
                        $learner->home_address_line_1 = $line_of_text[12];
                        $learner->home_address_line_1 = $line_of_text[13];
                        $learner->home_postcode = $line_of_text[14];
                        $learner->home_telephone = $line_of_text[15];
                        $learner->home_mobile = $line_of_text[16];
                        $learner->home_email = $line_of_text[17];
                        $learner->type = 5;
                        $learner->save($link);
                        $this->WriteLog($link, $line_of_text[6], "Learner created!");

                    }
                    else
                    {
                        $this->WriteLog($link, $line_of_text[6], "Please create employer record!");
                    }
                }
                else
                {
                    $this->WriteLog($link, $line_of_text[6], "Learner already exists!");
                }
            }
            else
            {
                $this->WriteLog($link, "", "No National Insurance Number found!");
            }
        }
        pre("Process complete");
    }


    public static function WriteLog($link, $ni, $message)
    {
        DAO::execute($link, "insert into import_learners values(NULL, '$ni','$message', NOW());");
        return true;
    }

    private function _formatCash($value)
    {
        return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
    }
}