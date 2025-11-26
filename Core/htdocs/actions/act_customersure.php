<?php
class customersure implements IAction
{
    public function execute(PDO $link)
    {
        $this->dropbox_upload();
        //$this->sendRequest();
        //$this->getRequest();
    }

    public function sendRequest()
    {
        $time = date('H:i:s');
        $data_string = new stdClass();
        $data_string->email = "khushnoodahmedkhan@hotmail.com";
        $data_string->send_at = "2020-11-19T".$time."Z";
        $data_string->survey_id = 5799;
        $data_string->first_name = "Khushnood";
        $data_string->surname = "Khan";
        $data_string = json_encode($data_string);

        $headers = [
            "Content-type:application/json",
            "Authorization: Token token=441cb7c409757d7bd4b05bcc9e514c6c",
            "Accept: application/vnd.customersure.v1+json;"
        ];

        $curl = curl_init('https://api.customersure.com/feedback_requests');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, "Perspective/Sunesis");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $result = curl_exec($curl);

        pr(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            pr('Error:');
            pre($error_msg);
        }
        else {
            pr('Success:');
            $result = json_decode($result);
            pre($result);
        }
        curl_close($curl);
    }

    public function getRequest()
    {
        $headers = [
            "Content-type:application/json",
            "Authorization: Token token=441cb7c409757d7bd4b05bcc9e514c6c",
            "Accept: application/vnd.customersure.v1+json;"
        ];

        $curl = curl_init('https://api.customersure.com/feedback_requests/10796663');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, "Perspective/Sunesis");

        $result = curl_exec($curl);

        pr(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            pr('Error:');
            pre($error_msg);
        }
        else {
            pr('Success:');
            $result = json_decode($result);
            pre($result);
        }
        curl_close($curl);
    }

    public function dropbox_upload()
    {
        pre(1);
        $content = "Test File";
        $result = null;

        // store content in memory
        /*if ($handler = fopen("php://memory", "rw"))
        {
            try
            {
                if (strlen($content) === fwrite($handler, $content))
                {
                    if (rewind($handler))
                    {
                        // get random filename
                        $filename = bin2hex(openssl_random_pseudo_bytes(32, $strong_crypto));
                        if ($curl = curl_init("https://content.dropboxapi.com/2/files/upload"))
                        {
                            try
                            {
                                $curl_headers = ["Authorization: Bearer "."sl.AmFVhjbRMUb5rB4rpedDbZ-fRl6b6fCE90j89FXemjSGD0FCACfDHsbpqdHMM4mCc3BM-t2DbV5p5eGiKhBkfKygkmS_P05wZVWadaN_tCmv-pfIWliG2yeI6Jrl-dxRfYOFbiM",
                                "Content-Type: application/octet-stream",
                                "Dropbox-API-Arg: {/"path/":/"/$filename/"}"];

                                curl_setopt($curl, CURLOPT_HTTPHEADER,     $curl_headers);
                                curl_setopt($curl, CURLOPT_PUT,            true);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  "POST");
                                curl_setopt($curl, CURLOPT_INFILE,         $handler);
                                curl_setopt($curl, CURLOPT_INFILESIZE,     strlen($content));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                                $response = curl_exec($curl);
                                if (200 === curl_getinfo($curl, CURLINFO_RESPONSE_CODE))
                                {
                                    $result = $filename;
                                }
                            }
                            catch(Exception $exception)
                            {
                                curl_close($curl);
                            }
                        }
                    }
                }
            }
            catch(Exception $exception)
            {
                fclose($handler);
            }
        }

        return $result;*/
    }


}
?>