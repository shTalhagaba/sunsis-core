<?php
class download_bootcamp_employer_file implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=EmployerEngagement.csv');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }

        echo 'Employer name:,';
        echo 'Does the employer operate from a single site or multiple sites?,';
        echo 'If multiple sites: Are Skills Bootcamps participants mainly recruited to the employer\'s main site or a branch site?,';
        echo 'Postcode of the site to which participants are mainly recruited:,';
        echo 'Size of employer (number of employees currently working for this employer),';
        echo 'Most relevant industry / business type of employer,';
        echo 'Is the employer offering vacancies?,';
        echo 'Number of vacancies offered for Skills Bootcamp participants:,';
        echo 'Is the employer sponsoring their own employees to participate on the Skills Bootcamp?,';
        echo 'Is the employer offering any other co-investment? E.g. venue equipment time given.,';
        echo 'Date of employer co-investment (engagement) buy-in agreed between employer &amp; provider (DD/MM/YYYY):,';
        echo 'Employer contact name:,';
        echo 'Employer email:,';
        echo 'Employer telephone:,';
        echo 'Please confirm the employer has received the Employer Privacy Notice so employers know how their data will be used. This is necessary to provide the training.,';
        echo 'Has the employer opted-out of contact from a third-party research contractor commissioned by DfE?  The research contractor will invite employers to participate in interviews and surveys covering their experience of courses and any potential improvements.,';
        echo 'Details of employer engagement/contribution';

        echo "\r\n";

        $records = DAO::getResultset($link, "SELECT * FROM organisations WHERE organisations.organisation_type = 2", DAO::FETCH_ASSOC);

        $rows = '';
        foreach($records AS $row)
        {
            $locationsCount = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$row['id']}'");
            $mainLocation = DAO::getObject($link, "SELECT * FROM locations WHERE locations.is_legal_address = '1' AND locations.organisations_id = '{$row['id']}'");
            $mainLocation = is_null($mainLocation) ? new Location() : $mainLocation;

            echo HTML::csvSafe($row['legal_name']) . ',';
            echo HTML::csvsafe($locationsCount > 1 ? 'Two or more sites' : 'One site') . ',';
            echo ',';
            echo HTML::csvsafe($mainLocation->postcode) . ',';
            echo HTML::csvsafe( DAO::getSingleValue($link, "SELECT description FROM lookup_employer_size WHERE code = '{$row['code']}'") ) . ',';
            echo HTML::csvsafe( DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$row['sector']}'") ) . ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo ',';
            echo HTML::csvsafe($mainLocation->contact_name) . ',';
            echo HTML::csvsafe($mainLocation->contact_email) . ',';
            echo HTML::csvsafe($mainLocation->contact_telephone) . ',';
            echo ',';
            echo ',';
            echo ',';
            
            echo "\r\n";
        }

        /*
        echo <<<HEADER_ROW
<table>
<tr>
    <th>Employer name:</th>
    <th>Does the employer operate from a single site or multiple sites?</th>
    <th>If multiple sites: Are Skills Bootcamps participants mainly recruited to the employer's main site or a branch site?</th>
    <th>Postcode of the site to which participants are mainly recruited:</th>
    <th>Size of employer (number of employees currently working for this employer)</th>
    <th>Most relevant industry / business type of employer</th>
    <th>Is the employer offering vacancies?</th>
    <th>Number of vacancies offered for Skills Bootcamp participants:</th>
    <th>Is the employer sponsoring their own employees to participate on the Skills Bootcamp?</th>
    <th>Is the employer offering any other co-investment? E.g. venue, equipment, time given.</th>
    <th>Date of employer co-investment (engagement) buy-in agreed between employer &amp; provider (DD/MM/YYYY):</th>
    <th>Employer contact name:</th>
    <th>Employer email:</th>
    <th>Employer telephone:</th>
    <th>Please confirm the employer has received the Employer Privacy Notice, so employers know how their data will be used. This is necessary to provide the training.</th>
    <th>Has the employer opted-out of contact from a third-party research contractor commissioned by DfE?  The research contractor will invite employers to participate in interviews and surveys covering their experience of courses and any potential improvements.</th>
    <th>Details of employer engagement/contribution</th>
</tr>
$rows
</table>
        
HEADER_ROW;
        */

    }
}
