<?php
class view_users_cpd implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:null;

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_users_cpd", "View Users CPD");

        $view = VoltView::getViewFromSession('UsersCpd', 'UsersCpd'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['UsersCpd'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        include_once('tpl_view_users_cpd.php');
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
SELECT
	users_cpd.id,
    users.firstnames,    
    users.surname,
    CASE users_cpd.routeway
        WHEN 'DM' THEN 'DM'
        WHEN 'SWD' THEN 'SWD'
        WHEN 'IT' THEN 'IT'
        WHEN 'Data' THEN 'Data'
    END AS routeway,
    DATE_FORMAT(users_cpd.start_date, '%d/%m/%Y') AS start_date,
    users_cpd.start_time,   
    DATE_FORMAT(users_cpd.end_date, '%d/%m/%Y') AS end_date,   
    users_cpd.end_time,
    CASE users_cpd.type
        WHEN 'P' THEN 'Professional'
        WHEN 'T' THEN 'Technical'
        WHEN 'BU' THEN 'Business Understanding'
        WHEN 'Of' THEN 'Ofsted'
        WHEN 'Ot' THEN 'Other'
    END AS type,
    users_cpd.comments   
FROM
	users_cpd INNER JOIN users ON users_cpd.user_id = users.id
;
		");
        $view = new VoltView('UsersCpd', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE users.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name(s): %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE users.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
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
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Firstnames, Surname', null, 'ORDER BY users.firstnames, users.surname'),
            1=>array(2, 'Creation Date (desc), Firstnames', null, 'ORDER BY users.created DESC, users.firstnames'));
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

            echo '<th>Id</th><th>Firstnames</th><th>Surname</th><th>Routeway</th><th>Start Date</th><th>Start Time</th><th>End Date</th><th>End Time</th><th>Type</th><th>Comments</th>';

            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=edit_user_cpd&id='.$row['id']);

                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                echo '<td>' . $row['routeway'] . '</td>';
		echo '<td>' . $row['start_date'] . '</td>';
                echo '<td>' . $row['start_time'] . '</td>';
		echo '<td>' . $row['end_date'] . '</td>';
                echo '<td>' . $row['end_time'] . '</td>';
                echo '<td>' . $row['type'] . '</td>';
                echo '<td>' . nl2br((string) $row['comments']) . '</td>';

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

}