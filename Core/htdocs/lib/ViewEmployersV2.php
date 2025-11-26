<?php
class ViewEmployersV2 extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $learner_type = User::TYPE_LEARNER;
            $sql = new SQLStatement("
SELECT DISTINCT 
  organisations.id AS org_id,     
  organisations.`legal_name`,
  organisations.`trading_name`,
  organisations.`company_number`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`,
  locations.`postcode`,
  locations.`contact_name`,
  locations.`contact_email`,
  locations.`contact_mobile`,
  locations.`contact_telephone`,
  locations.`telephone`,
  (SELECT
    COUNT(*)
  FROM
    users
  WHERE users.`type` = '{$learner_type}'
    AND users.`employer_id` = organisations.`id`) AS no_of_learners,
  IF(organisations.active = 1, 'Yes', 'No') AS active,
  (SELECT description FROM lookup_org_status WHERE id = organisations.org_status) AS status 
FROM
  organisations
  LEFT JOIN locations
    ON (
      locations.`organisations_id` = organisations.`id`
      AND locations.`is_legal_address` = 1
    )
			");

            $sql->setClause("WHERE organisations.`organisation_type` = '" . Organisation::TYPE_EMPLOYER . "'");

	    // PSA users can only see their created employers or where they have their learners.
            if($_SESSION['user']->employer_id == 3278)
            {
                $sql->setClause("WHERE organisations.`creator` IN (SELECT users.`username` FROM users WHERE users.`employer_id` = 3278) OR organisations.id IN (SELECT users.`employer_id` FROM users INNER JOIN training ON users.`id` = training.`learner_id` INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE users.`type` = 5 AND crm_training_schedule.`venue` = 'Peterborough Skills Academy')");
            }

            $view = $_SESSION[$key] = new ViewEmployersV2();
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Legal Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_employer_telephone', "WHERE REPLACE(locations.`telephone`, ' ', '') LIKE '%%%s%%' ", null);
            $f->setDescriptionFormat("Employer Telephone: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_employer_contact_telephone', "WHERE REPLACE(locations.`contact_telephone`, ' ', '') LIKE '%%%s%%' ", null);
            $f->setDescriptionFormat("Employer Contact Telephone: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All employers', null, null),
                1=>array(2, 'Active employers only', null, 'WHERE organisations.active = 1'),
                2=>array(3, 'Inactive employers only', null, 'WHERE organisations.active <> 1'));
            $f = new DropDownViewFilter('filter_active', $options, 2, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE organisations.org_status=",CHAR(39),id,CHAR(39)) FROM lookup_org_status ';
            $f = new DropDownViewFilter('filter_org_status', $options, null, true);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT address_line_3, address_line_3, null, CONCAT("having address_line_3=",CHAR(39),address_line_3,CHAR(39)) FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 2 AND locations.is_legal_address = 1 order by locations.address_line_3';
            $f = new DropDownViewFilter('filter_address_line_3', $options, null, true);
            $f->setDescriptionFormat("Address line 3: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT address_line_4, address_line_4, null, CONCAT("having address_line_4=",CHAR(39),address_line_4,CHAR(39)) FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 2 AND locations.is_legal_address = 1 order by locations.address_line_4';
            $f = new DropDownViewFilter('filter_address_line_4', $options, null, true);
            $f->setDescriptionFormat("Address line 4: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Postcode: %s");
            $view->addFilter($f);

            $regions = "SELECT description, description, NULL, CONCAT('WHERE organisations.region = ',CHAR(39),description,CHAR(39)) FROM lookup_vacancy_regions ORDER BY description";
            $f = new DropDownViewFilter('filter_region', $regions, null, true);
            $f->setDescriptionFormat("Region is: %s");
            $view->addFilter($f);

            $format = "WHERE organisations.created_at >= '%s'";
            $f = new DateViewFilter('filter_from_creation_date', $format, '');
            $f->setDescriptionFormat("From Creation Date: %s");
            $view->addFilter($f);

            $format = "WHERE organisations.created_at <= '%s'";
            $f = new DateViewFilter('filter_to_creation_date', $format, '');
            $f->setDescriptionFormat("To Creation Date: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Legal name', null, 'ORDER BY organisations.legal_name'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(300,300,null,null),
                5=>array(400,400,null,null),
                6=>array(500,500,null,null),
                7=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link, $columns)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center" ><table class="table table-bordered table-condensed" id="tblEmployers">';
            echo '<thead class="bg-gray-active"><tr>';
            echo '<th>&nbsp;</th>';
            foreach($columns as $column)
            {
                echo '<th>' . ucwords(str_replace("_"," ",$column)) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('/do.php?_action=read_employer&id=' . $row['org_id']);

                echo '<td>';
                echo $row['active'] == 'Yes' ? '<label class="label label-success">Active</label>' : '<label class="label label-danger">Not Active</label>';
                echo ' &nbsp; <label class="label label-info">' . $row['status'] . '</label>';
                echo '</td>';
                foreach($columns as $column)
                {
                    echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></div><p><br></p>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>