<?php
class ViewCrmActivities extends View
{
    public static function getInstance(PDO $link, $view_name)
    {
        if (!isset($_SESSION[$view_name])) 
        {

            $sql = new SQLStatement("
SELECT
    crm_enquiries.`enquiry_title`,
    crm_leads.`lead_title`,
    crm_opportunities.`opportunity_title`,
    CASE crm_enquiries.`company_type`
        WHEN 'pool' THEN (SELECT legal_name FROM pool WHERE pool.id = crm_enquiries.`company_id`)
        WHEN 'employer' THEN (SELECT legal_name FROM organisations WHERE organisations.id = crm_enquiries.`company_id`)
        ELSE ''
    END AS enquiry_company,
    CASE crm_leads.`company_type`
        WHEN 'pool' THEN (SELECT legal_name FROM pool WHERE pool.id = crm_leads.`company_id`)
        WHEN 'employer' THEN (SELECT legal_name FROM organisations WHERE organisations.id = crm_leads.`company_id`)
        ELSE ''
    END AS lead_company,
    CASE crm_opportunities.`company_type`
        WHEN 'pool' THEN (SELECT legal_name FROM pool WHERE pool.id = crm_opportunities.`company_id`)
        WHEN 'employer' THEN (SELECT legal_name FROM organisations WHERE organisations.id = crm_opportunities.`company_id`)
        ELSE ''
    END AS opportunity_company,
    crm_activities.*,
    (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_activities.created_by) AS creator
FROM
    crm_activities
    LEFT JOIN crm_enquiries ON (crm_enquiries.`id` = crm_activities.`entity_id` AND crm_activities.`entity_type` = 'enquiry')
    LEFT JOIN crm_leads ON (crm_leads.`id` = crm_activities.`entity_id` AND crm_activities.`entity_type` = 'lead')
    LEFT JOIN crm_opportunities ON (crm_opportunities.`id` = crm_activities.`entity_id` AND crm_activities.`entity_type` = 'opportunity')
;
                    ");

            if ($view_name == 'ViewActivitiesDue') {
                $sql->setClause("WHERE crm_activities.due_date = CURDATE() AND crm_activities.complete = 0 ");
            }
            if ($view_name == 'ViewActivitiesOverdue') {
                $sql->setClause("WHERE crm_activities.due_date < CURDATE() AND crm_activities.complete = 0 ");
            }
            if ($view_name == 'ViewActivitiesUpcoming') {
                $sql->setClause("WHERE crm_activities.due_date > CURDATE() AND crm_activities.complete = 0 ");
            }
            if ($view_name == 'ViewActivitiesCompleted') {
                $sql->setClause("WHERE crm_activities.complete = 1 ");
            }

            $view = new ViewCrmActivities($view_name, $sql->__toString());
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_subject', "WHERE crm_activities.subject LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Title/Subject: %s");
            $view->addFilter($f);

            $user_id = $_SESSION['user']->id;
            $options = <<<SQL
    SELECT DISTINCT
    users.id,   CONCAT(users.firstnames, ' ', users.surname), NULL,
    CONCAT('WHERE crm_activities.created_by=', users.id)
FROM
    users
WHERE users.id IN (SELECT crm_activities.created_by FROM crm_activities) OR users.id = '{$user_id}'
ORDER BY users.firstnames;
SQL;
            $f = new DropDownViewFilter('filter_created_by', $options, null, true);
            $f->setDescriptionFormat("Created By: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('1', 'Email', null, 'WHERE crm_activities.activity_type = "email"'),
                1 => array('2', 'Meeting', null, 'WHERE crm_activities.activity_type = "meeting"'),
                2 => array('3', 'Phone Calls', null, 'WHERE crm_activities.activity_type = "phone"'),
                3 => array('4', 'Task', null, 'WHERE crm_activities.activity_type = "task"')
            );
            $f = new DropDownViewFilter('filter_activity_type', $options, null, true);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                0 => array('0', 'Open', null, 'WHERE crm_activities.complete = "0"'),
                1 => array('1', 'Completed', null, 'WHERE crm_activities.complete = "1"'),
            );
            $f = new DropDownViewFilter('filter_completed', $options, null, true);
            $f->setDescriptionFormat("Completed: %s");
            $view->addFilter($f);

            $format = "WHERE crm_activities.due_date >= '%s'";
            $f = new DateViewFilter('from_due_date', $format, '');
            $f->setDescriptionFormat("From due date: %s");
            $view->addFilter($f);
            $format = "WHERE crm_activities.due_date <= '%s'";
            $f = new DateViewFilter('to_due_date', $format, '');
            $f->setDescriptionFormat("To due date: %s");
            $view->addFilter($f);

            $format = "WHERE crm_activities.created_at >= '%s'";
            $f = new DateViewFilter('from_created_at', $format, '');
            $f->setDescriptionFormat("From created: %s");
            $view->addFilter($f);
            $format = "WHERE crm_activities.created_at <= '%s'";
            $f = new DateViewFilter('to_created_at', $format, '');
            $f->setDescriptionFormat("To created: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(20, 20, null, null),
                1 => array(50, 50, null, null),
                2 => array(100, 100, null, null),
                3 => array(200, 200, null, null),
                4 => array(300, 300, null, null),
                5 => array(400, 400, null, null),
                6 => array(500, 500, null, null),
                7 => array(0, 'No limit', null, null)
            );
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(1, 'Last Updated At (Descending)', null, 'ORDER BY crm_activities.updated_at DESC'),
                1 => array(2, 'Last Updated At (Ascending)', null, 'ORDER BY crm_activities.updated_at ASC'),
                2 => array(3, 'Due Date (Descending)', null, 'ORDER BY crm_activities.due_date DESC'),
                3 => array(4, 'Due Date (Ascending)', null, 'ORDER BY crm_activities.due_date ASC'),
                4 => array(5, 'Creation Date (Descending)', null, 'ORDER BY crm_activities.date DESC'),
                5 => array(6, 'Creation Date (Ascending)', null, 'ORDER BY crm_activities.date AC')
            );
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            return $view;
        }

        return $_SESSION[$view_name];
    }
}
