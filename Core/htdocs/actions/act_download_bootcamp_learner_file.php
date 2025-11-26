<?php
class download_bootcamp_learner_file implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=ApplicantInformation.csv');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }

		echo "First Name,";
		echo "Surname,";
		echo "National Insurance number,";
		echo "National Insurance number format validation. This column tells you if the format of the National Insurance Number is correct i.e. it has the correct amount of characters,";
		echo "Home Postcode,";
		echo "Email address,";
		echo "Tel no,";
		echo "Please complete the applicant's highest level of education completed - see detail in Annex 1 which gives a comparison.,";
		echo "If applicant has completed level 6 qualification (or above) please select which subject,";
		echo "What month/year did the applicant apply to join the Skills Bootcamp? MMM/YY,";
		echo "What best describes the applicant's employment status before they applied to the Skills Bootcamp?,";
		echo "If the applicant is employed what is the name of their current employer? (Employer name),";
		echo "What is the postcode of the applicant's main workplace? (Employer postcode),";
		echo "Has the applicant applied to participate in the Skills Bootcamp through their current employer?,";
		echo "Prior to applying for the Skills Bootcamp how many hours per week does the applicant usually work in their job(s)?,";
		echo "What is the applicant's estimated current salary (GBP)? Please provide figure as either: hourly rate (if a zero hours contract) weekly monthly yearly gross pay in their current job(s)? If unemployed please enter,";
		echo "Please indicate whether income figure estimated in column Q is hourly weekly monthly or yearly.,";
		echo "Is the applicant planning to continue working while on the Skills Bootcamp?,";
		echo "What is the applicant's main job prior to applying for the Skills Bootcamp? (If not currently employed what is the learners most recent job).  Please enter the JOB TITLE below,";
		echo "What industry did the applicant mainly work for prior to applying for the Skills Bootcamp?,";
		echo "Is the applicant currently claiming Universal Credit?,";
		echo "Does the applicant have caring responsibilities for children or other adults?,";
		echo "Applicant Date of Birth (DD/MM/YYYY):,";
		echo "What is the applicant's gender?,";
		echo "Does the Applicant have a disability / long term health condition?,";
		echo "What is the applicant's ethnicity:,";
		echo "Please confirm the applicant has received all the necessary supporting materials about how their data will be used. This is necessary to provide the training. Necessary supporting documents include:(1) Privacy Notice and Q (2) Complaints procedure,";
		echo "All Applicants/Learners may be asked to take part in qualitative interviews and or surveys to understand their experience of participating in Skills Bootcamps. This is optional. Has the Applicant/Learner opted out of being contacted for this purpose?,";
		echo "Where did the applicant hear about the course?,";
		echo "Has the applicant become a participant?,";
		echo "Planned Start Date,";
		echo "If the applicant did not become a participant please select reason why:,";

        echo "\r\n";

        $sql = new SQLStatement("
        SELECT
          learners.*,
          employers.legal_name,
          locations.postcode        
        FROM
          users AS learners
          LEFT JOIN organisations AS employers
            ON learners.`employer_id` = employers.`id`
          LEFT JOIN locations
            ON (
              locations.`organisations_id` = employers.`id`
              AND locations.`is_legal_address` = 1
            )
        ");
        
        $sql->setClause("WHERE learners.`type` = '" . User::TYPE_LEARNER . "'");
        
        $records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

        foreach($records AS $row)
        {
            echo HTML::csvSafe($row['firstnames']) . ',';
            echo HTML::csvSafe($row['surname']) . ',';
            echo HTML::csvSafe($row['ni']) . ',';
            echo ',';
            echo HTML::csvSafe($row['home_postcode']) . ',';
            echo HTML::csvSafe($row['home_email']) . ',';
            echo HTML::csvSafe($row['home_mobile']) . ',';
            echo ',';
            echo ',';
            echo Date::to($row['created'], 'My') . ',';
            echo ',';
            echo HTML::csvSafe($row['legal_name']) . ',';
            echo HTML::csvSafe($row['postcode']) . ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo Date::to($row['dob'], 'd/m/Y') . ',';
            echo $row['gender'] . ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
           
            echo "\r\n";
        }
    }
}
