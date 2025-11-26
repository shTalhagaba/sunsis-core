<?php
set_time_limit(0);
ini_set('memory_limit','2048M');
class bulk_update implements IAction
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

            if( substr(strrchr($_FILES['file']['name'],'.'),1) != 'csv' )
            {
                fclose($file_handle);
                pre("Only CSV file is allowed!");
            }
        }

        $line_of_text = fgetcsv($file_handle);
        if(!isset($line_of_text[0]) or (isset($line_of_text[0]) and strtoupper($line_of_text[0])!="ULN"))
        {
            fclose($file_handle);
            pre("ULN column is missing");
        }
        if(!isset($line_of_text[1]) or (isset($line_of_text[1]) and strtoupper($line_of_text[1])!="LEARNAIMREF"))    
        {
            fclose($file_handle);
            pre("LearnAimRef column is missing");
        }
        if(!isset($line_of_text[2]) or (isset($line_of_text[2]) and strtoupper($line_of_text[2])!="AWARDING_BODY_REGISTRATION_NUMBER"))    
        {
            fclose($file_handle);
            pre("Awarding_Body_Registration_Number column is missing");
        }
        if(!isset($line_of_text[3]) or (isset($line_of_text[3]) and strtoupper($line_of_text[3])!="AWARDING_BODY_REGISTRATION_DATE"))    
        {
            fclose($file_handle);
            pre("Awarding_Body_Registration_Date column is missing");
        }
        if(!isset($line_of_text[4]) or (isset($line_of_text[4]) and strtoupper($line_of_text[4])!="CERTIFICATE_APPLIED_DATE"))    
        {
            fclose($file_handle);
            pre("Certificate_Applied_Date column is missing");
        }
        if(!isset($line_of_text[5]) or (isset($line_of_text[5]) and strtoupper($line_of_text[5])!="CERTIFICATE_RECEIVED_DATE"))    
        {
            fclose($file_handle);
            pre("Certificate_Applied_Date column is missing");
        }
        if(!isset($line_of_text[6]) or (isset($line_of_text[6]) and strtoupper($line_of_text[6])!="CERTIFICATE_NUMBER"))    
        {
            fclose($file_handle);
            pre("Certificate_Number column is missing");
        }
        if(!isset($line_of_text[7]) or (isset($line_of_text[7]) and strtoupper($line_of_text[7])!="CERTIFICATE_POST_DATE"))    
        {
            fclose($file_handle);
            pre("Certificate_Post_Date column is missing");
        }
        if(!isset($line_of_text[8]) or (isset($line_of_text[8]) and strtoupper($line_of_text[8])!="EXPIRY_DATE"))    
        {
            fclose($file_handle);
            pre("Expiry_Date column is missing");
        }
        if(!isset($line_of_text[9]) or (isset($line_of_text[9]) and strtoupper($line_of_text[9])!="BATCH_NUMBER"))    
        {
            fclose($file_handle);
            pre("Batch Number column is missing");
        }
        if(!isset($line_of_text[10]) or (isset($line_of_text[10]) and strtoupper($line_of_text[10])!="CANDIDATE_NUMBER"))    
        {
            fclose($file_handle);
            pre("Candidate Number column is missing" . $line_of_text[10]);
        }

        $data = Array();
        $data[] = $this->getRecord($line_of_text);

        $header_row = true;            
        while (!feof($file_handle) )
        {
            $grandTotal = "";
            $line_of_text = fgetcsv($file_handle);
            if($line_of_text[0]!="" and $line_of_text[1]!="")
                $data[] = $this->getRecord($line_of_text);
        }

        DAO::execute($link, "truncate bulk_update");
        DAO::multipleRowInsert($link, "bulk_update", $data);
        DAO::execute($link, "truncate bulk_update2");
        DAO::execute($link, "INSERT INTO bulk_update2 SELECT 
        value_1
        ,value_2
        ,value_3
        ,value_4
        ,value_5
        ,value_6
        ,value_7
        ,value_8
        ,value_9
        ,value_10
        ,value_11
        FROM bulk_update");

        /*
        if(isset($line_of_text[0]) && trim($line_of_text[0])!="")
        {
            $tr_id = DAO::getSingleValue($link, "select id from tr where concat(firstnames, ' ',surname) = '$line_of_text[0]'");
            if($tr_id)
            {
                $assessor_id = DAO::getSingleValue($link, "select id from users where concat(firstnames, ' ',surname) = '$line_of_text[1]' and type = 3");

                if(!$assessor_id)    
                {
                    $this->WriteLog($link, "UpdateAssessor", $line_of_text[0], $line_of_text[1], "Assessor not found!");
                    continue;
                }
                else
                {
                    DAO::execute($link, "update tr set assessor = '$assessor_id' where id = '$tr_id'");                        
                    $this->WriteLog($link, "UpdateAssessor", $line_of_text[0], $line_of_text[1], "Assessor Update");
                }
            }
            else
            {
                $this->WriteLog($link, "UpdateAssessor", $line_of_text[0], $line_of_text[1], "Learner not found!");
            }
        }
        else
        {
            $this->WriteLog($link, "UpdateAssessor", "", "", "No Learner Name found!");
        }*/

        http_redirect("do.php?_action=get_contracts_predictor&destination=bulk_update&stage=2");

    }

    public static function getRecord($line_of_text)
    {
        $line = Array();
        $line['value_1'] = isset($line_of_text[0])?$line_of_text[0]:"";
        $line['value_2'] = isset($line_of_text[1])?$line_of_text[1]:"";
        $line['value_3'] = isset($line_of_text[2])?$line_of_text[2]:"";
        $line['value_4'] = isset($line_of_text[2])?$line_of_text[3]:"";
        $line['value_5'] = isset($line_of_text[2])?$line_of_text[4]:"";
        $line['value_6'] = isset($line_of_text[2])?$line_of_text[5]:"";
        $line['value_7'] = isset($line_of_text[2])?$line_of_text[6]:"";
        $line['value_8'] = isset($line_of_text[2])?$line_of_text[7]:"";
        $line['value_9'] = isset($line_of_text[2])?$line_of_text[8]:"";
        $line['value_10'] = isset($line_of_text[2])?$line_of_text[9]:"";
        $line['value_11'] = isset($line_of_text[2])?$line_of_text[10]:"";
        return $line;
    }

    public static function WriteLog($link, $entity, $learner, $new, $message)
    {
        DAO::execute($link, "insert into bulk_update values('$entity', '$learner', '$new', '$message', NOW());");
        return true;
    }

    private function _formatCash($value)
    {
        return '&pound;'.number_format(sprintf("%.2f", $value),2,".",",");
    }
}