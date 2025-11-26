<?php


class Bksb
{
    private static function executeCurl($access_key, $secret, $request_method = 'GET', $request_url, $learner_to_add = null)
    {
        $request_url = "https://live2api.bksblive2.co.uk/api/{$request_url}";

        $curl=curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_URL,$request_url);
        if($request_method == 'POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $learner_to_add);
        }
        else
        {
            curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$request_method);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("accept: application/json"));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,1);
        curl_setopt($curl, CURLOPT_USERPWD, "$access_key:$secret");
        //curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        $out = curl_exec($curl);
        curl_close($curl);

        return $out;
    }

    public static function createUser($access_key, $secret, $learner_push)
    {
        $api = "users/createUser";

        return self::executeCurl($access_key, $secret, 'POST', $api, $learner_push);
    }

    public static function getUserId($access_key, $secret, $bksb_username)
    {
        $bksb_username = str_replace ( ' ', '%20', $bksb_username);

        $api = "users/user/getUserId?username={$bksb_username}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function isUsernameExists($access_key, $secret, $bksb_username)
    {
        $bksb_username = str_replace ( ' ', '%20', $bksb_username);

        $api = "users/isUsernameExists?username={$bksb_username}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function initialAssessment($access_key, $secret, $bksb_userid, $page = 1, $recordsPerPage = 20)
    {
        $api = "results/initialAssessment/{$bksb_userid}/all?page={$page}&recordsPerPage={$recordsPerPage}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function getAutoLoginLink($access_key, $secret, $bksb_userid)
    {
        $api = "users/user/{$bksb_userid}/getAutoLoginLink";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function diagnosticAssessment($access_key, $secret, $bksb_userid, $page = 1, $recordsPerPage = 20)
    {
        $api = "results/diagnosticAssessment/{$bksb_userid}/all?page={$page}&recordsPerPage={$recordsPerPage}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function getAssessmentSessionsForCourseV5($access_key, $secret, $bksb_user_id, $courseSubject)
    {
        $api = "assessmentSessions/getAssessmentSessionsForCourseV5?courseSubject={$courseSubject}&userId={$bksb_user_id}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }

    public static function GetEOAReportDataForAssessmentSessionV5($access_key, $secret, $bksb_user_id, $sessionId)
    {
        $api = "assessmentSessions/GetEOAReportDataForAssessmentSessionV5?sessionId={$sessionId}&userId={$bksb_user_id}";

        return self::executeCurl($access_key, $secret, 'GET', $api);
    }
}