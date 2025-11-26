<?php
class view_sent_emails implements IAction
{
    public function execute(PDO $link)
    {
        $view = VoltView::getViewFromSession('view_ViewSentEmails', 'view_ViewSentEmails'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['view_ViewSentEmails'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_sent_emails", "View Sent Emails");


        include_once('tpl_view_sent_emails.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT 
	users.`id` AS learner_id,
	users.`firstnames`, 
	users.`surname`,
	email_to,
	email_subject, 
	email_body, 
	IF(
		emails.`by_whom` = 9999, 'AUTOMATED EMAIL',  
		(SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.`id` = emails.`by_whom`)  
	)AS by_whom,
	emails.`created` AS sent_date,
	(SELECT email_templates.`template_type` FROM email_templates WHERE email_templates.`id` = emails.email_type) AS email_type
FROM 
	emails INNER JOIN users ON (emails.`entity_id` = users.`id` AND emails.`entity_type` = 'sunesis_learner')
;
		");

        $view = new VoltView('view_ViewSentEmails', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE users.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name(s): %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE users.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $options = "SELECT template_type, template_type, NULL, CONCAT('HAVING email_type=',CHAR(39),template_type,CHAR(39)) FROM email_templates ORDER BY sorting;";
        $f = new VoltDropDownViewFilter('filter_email_type', $options, null, true);
        $f->setDescriptionFormat("Email Type: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Automated Emails Only', null, 'HAVING by_whom = "AUTOMATED EMAIL"'));
        $f = new VoltDropDownViewFilter('filter_auto_emails', $options, 0, false);
        $f->setDescriptionFormat("Auto Emails only: %s");
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
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 100, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Firstnames, Surname', null, 'ORDER BY users.firstnames, users.surname'),
            1=>array(2, 'Sent Date (desc), Firstnames', null, 'ORDER BY emails.created DESC, users.firstnames'));
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><p><br></p><table class="table table-bordered">';
            echo '<thead class="bg-gray"><tr>';

            echo '<th>Firstnames</th><th>Surname</th><th>Email To</th><th>Email Subject</th><th>By Whom</th><th>Sent Date</th><th>Email Type</th>';

            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_learner&id='.$row['learner_id']);

                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                echo '<td>' . $row['email_to'] . '</td>';
                echo '<td>' . $row['email_subject'] . '</td>';
                echo '<td>' . $row['by_whom'] . '</td>';
                echo '<td>' . Date::to($row['sent_date'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['email_type'] . '</td>';

                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function csvSafe($value)
    {
        $value = str_replace(',', '; ', $value);
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace("\t", '', $value);
        $value = str_replace("&nbsp;", '', $value);
        $value = '"' . str_replace('"', '""', $value) . '"';
        return $value;
    }

}