<?php
class ViewEmployers extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

	    $where = "";
        if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                $where = "";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_FRONTLINE)
            {
                $where = " AND ( ob_learners.id IS NULL OR ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_FRONTLINE . "' ) ";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_LINKS_TRAINING)
            {
                //$where = " AND ( ob_learners.id IS NULL OR ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_LINKS_TRAINING . "' ) ";
		        $where = " AND organisations.`creator` IN (SELECT username FROM users WHERE users.`learners_caseload` = '" . OnboardingLearner::CASELOAD_LINKS_TRAINING . "' ) ";
            }
	        elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_NEW_ACCESS)
            {
                $where = " AND ( ob_learners.id IS NULL OR ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_NEW_ACCESS . "' ) AND organisations.creator IN ('sgeeves1', 'fzaidi')";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_INTERNAL_ELA)
            {
                $where = " AND ( ob_learners.id IS NULL OR ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_INTERNAL_ELA . "' ) ";
            }
        }

        if(!isset($_SESSION[$key]))
        {
            $organisation_type = Organisation::TYPE_EMPLOYER;
            $sql = <<<HEREDOC
SELECT
            DISTINCT
    organisations.id,
    organisations.legal_name,
    organisations.edrs,
    IF(organisations.levy_employer = 1, 'Yes', 'No') AS levy_employer,
    locations.address_line_1, 
    locations.postcode,
    locations.telephone,
    locations.contact_name,
    locations.contact_telephone,
    locations.contact_email,
    COALESCE(tr.learners_count, 0) AS learners_count,
    CASE ea.status
        WHEN '0' THEN 'NOT STARTED'
        WHEN '1' THEN 'CREATED'
        WHEN '2' THEN 'EMAILED TO EMPLOYER'
        WHEN '3' THEN 'SIGNED BY EMPLOYER'
        WHEN '4' THEN 'COMPLETED'
        ELSE 'NOT CREATED'
    END AS employer_agreement_status,
    DATE_FORMAT(hs.el_date, '%d/%m/%Y') AS liability_insurance_expiry,
    hs.el_insurance,
    hs.el_insurer
FROM organisations
LEFT JOIN locations  
    ON locations.organisations_id = organisations.id 
    AND locations.is_legal_address = 1
LEFT JOIN ob_learners ON organisations.`id` = ob_learners.`employer_id`
LEFT JOIN (
    SELECT employer_id, COUNT(*) AS learners_count
    FROM ob_tr
    GROUP BY employer_id
) AS tr ON tr.employer_id = organisations.id
LEFT JOIN (
    SELECT employer_id, status
    FROM employer_agreements ea1
    WHERE ea1.id = (
        SELECT ea2.id 
        FROM employer_agreements ea2
        WHERE ea2.employer_id = ea1.employer_id
        ORDER BY ea2.created DESC 
        LIMIT 1
    )
) AS ea ON ea.employer_id = organisations.id
LEFT JOIN (
    SELECT hs1.*
    FROM health_safety hs1
    WHERE hs1.id = (
        SELECT hs2.id 
        FROM health_safety hs2
        WHERE hs2.employer_id = hs1.employer_id
        ORDER BY hs2.id DESC
        LIMIT 1
    )
) AS hs ON hs.employer_id = organisations.id
WHERE organisations.organisation_type = '2'
 $where    
;
HEREDOC;


/*
            $sql = <<<HEREDOC
SELECT	DISTINCT 
    organisations.id,
    organisations.legal_name,
    organisations.edrs,
    IF(organisations.levy_employer = 1, 'Yes', 'No') AS levy_employer,
    locations.address_line_1, 
    locations.postcode,
    locations.telephone,
    locations.contact_name,
    locations.contact_telephone,
    locations.contact_email,
    (SELECT COUNT(*) FROM ob_tr WHERE ob_tr.employer_id = organisations.id) AS learners_count,
    CASE (SELECT employer_agreements.status FROM employer_agreements WHERE employer_agreements.employer_id = organisations.id ORDER BY employer_agreements.created DESC LIMIT 1) 
        WHEN '0' THEN 'NOT STARTED'
        WHEN '1' THEN 'CREATED'
        WHEN '2' THEN 'EMAILED TO EMPLOYER'
        WHEN '3' THEN 'SIGNED BY EMPLOYER'
        WHEN '4' THEN 'COMPLETED'
        ELSE 'NOT CREATED'
    END AS employer_agreement_status,
    DATE_FORMAT(health_safety.el_date, '%d/%m/%Y') AS liability_insurance_expiry,
    health_safety.el_insurance,
    health_safety.el_insurer
FROM
    organisations 
	LEFT JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	LEFT JOIN ob_learners ON organisations.`id` = ob_learners.`employer_id`
	LEFT JOIN (SELECT m1.* FROM health_safety m1 LEFT JOIN health_safety m2 ON (m1.employer_id = m2.employer_id AND m1.id < m2.id) WHERE m2.id IS NULL) AS health_safety 
     ON organisations.`id` = health_safety.`employer_id`
WHERE organisations.organisation_type = '{$organisation_type}'
 $where    
;
HEREDOC;
*/
            $view = $_SESSION[$key] = new ViewEmployers();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array(1, 'NOT CREATED ', null, ' HAVING employer_agreement_status = "NOT CREATED" '),
                1=>array(2, 'NOT STARTED', null, ' HAVING employer_agreement_status = "NOT STARTED" '),
                2=>array(3, 'CREATED', null, ' HAVING employer_agreement_status = "CREATED" '),
                3=>array(4, 'EMAILED TO EMPLOYER', null, ' HAVING employer_agreement_status = "EMAILED TO EMPLOYER" '),
                4=>array(5, 'SIGNED BY EMPLOYER', null, ' HAVING employer_agreement_status = "SIGNED BY EMPLOYER" '),
                5=>array(6, 'COMPLETED', null, ' HAVING employer_agreement_status = "COMPLETED" '),
            );
            $f = new DropDownViewFilter('filter_agreement_status', $options, '', true);
            $f->setDescriptionFormat("Agreement Status: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Legal Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Postcode: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_edrs', "WHERE organisations.edrs LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("EDRS: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Pipeline Employers ', null, ' WHERE employer_agreements.id IS NULL OR employer_agreements.status < 3 '),
                1=>array(2, 'Onboarding Employers', null, ' WHERE employer_agreements.file_upload = "Y" OR employer_agreements.status >= 3 '));
            $f = new DropDownViewFilter('filter_pipe_onboard', $options, '', true);
            $f->setDescriptionFormat("Pipeline Or Onboard: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array(1, 'Expired ', null, ' WHERE health_safety.el_date < CURDATE() '),
                1=>array(2, 'Not Expired', null, ' WHERE health_safety.el_date >= CURDATE() '),
                2=>array(3, 'No Date', null, ' WHERE health_safety.el_date IS NULL '),
            );
            $f = new DropDownViewFilter('filter_el_expiry', $options, '', true);
            $f->setDescriptionFormat("EL Expiry Date: %s");
            $view->addFilter($f);
/*
            $options = DAO::getResultset($link, "SELECT users.username, CONCAT(firstnames, ' ', surname), NULL, CONCAT('WHERE organisations.creator=', CHAR(39), users.username, CHAR(39)) FROM users WHERE users.username IN (SELECT creator FROM organisations) ORDER BY users.`firstnames`;");
            $f = new DropDownViewFilter('filter_bdo', $options, '', true);
            $f->setDescriptionFormat("Business Development Officer: %s");
            $view->addFilter($f);
*/
            $options = DAO::getResultset($link, "SELECT id, description, NULL, CONCAT('WHERE organisations.sector=', CHAR(39), id, CHAR(39)) FROM lookup_sector_types ORDER BY description;");
            $f = new DropDownViewFilter('filter_sector', $options, '', true);
            $f->setDescriptionFormat("Sector: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Yes ', null, ' WHERE organisations.`active` = "1" '),
                1=>array(2, 'No ', null, ' WHERE organisations.`active` != "1" '));
            $f = new DropDownViewFilter('filter_active', $options, 1, true);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Yes ', null, ' WHERE organisations.`levy_employer` = "1" '),
                1=>array(2, 'No ', null, ' WHERE organisations.`levy_employer` != "1" '));
            $f = new DropDownViewFilter('filter_levy_employer', $options, '', true);
            $f->setDescriptionFormat("Levy Employer: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Company name (asc)', null, 'ORDER BY legal_name'),
                1=>array(2, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
                2=>array(3, 'Employer Agreements Created (Desc), Company name ', null, 'ORDER BY employer_agreements.created DESC, legal_name'),
                3=>array(4, 'Employer Agreements Created (ASC), Company name ', null, ' ORDER BY employer_agreements.created ASC, legal_name'),
                4=>array(5, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
                5=>array(6, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblEmployers" class="table table-bordered">';
            echo <<<HTML
<thead>
    <tr>
        <th>&nbsp;</th>
        <th>Legal Name</th>
        <th>Edrs</th>
        <th>Levy Employer</th>
        <th>Address Line 1</th>
        <th>Postcode</th>
        <th>Telephone</th>
        <th>Contact Name</th>
        <th>Contact Telephone</th>
        <th>Contact Email</th>
        <th>Employer Agreement</th>
        <th>Liability Insurance Expiry</th>
        <th>Learners Count</th>
    </tr>
</thead>
HTML;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('/do.php?_action=read_employer&id=' . $row['id']);
                echo '<td><span class="fa fa-bank"></span> </td>';
                echo '<td>' . HTML::cell($row['legal_name']) . '</td>';
                echo '<td>' . HTML::cell($row['edrs']) . '</td>';
                echo '<td>' . HTML::cell($row['levy_employer']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_1']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['telephone']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_name']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_telephone']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_email']) . '</td>';
                if($row['employer_agreement_status'] == 'NOT CREATED')
                    echo '<td><label class="label label-danger">' . HTML::cell($row['employer_agreement_status']) . '</label></td>';
                elseif($row['employer_agreement_status'] == 'SIGNED BY EMPLOYER')
                    echo '<td><label class="label label-success">' . HTML::cell($row['employer_agreement_status']) . '</label></td>';
                else
                    echo '<td><label class="label label-info">' . HTML::cell($row['employer_agreement_status']) . '</label></td>';
		echo isset($row['liability_insurance_expiry']) ? '<td align="center">' . HTML::cell($row['liability_insurance_expiry']) . '</td>' : '<td align="center"></td>';
                echo '<td align="center">' . HTML::cell($row['learners_count']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>