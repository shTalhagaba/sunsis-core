<?php
class ViewDepartments extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $organisation_type = Organisation::TYPE_DEPARTMENT;
            $sql = <<<HEREDOC
SELECT
    organisations.id,
    organisations.legal_name AS department_name,
    organisations.company_number AS department_code,
    locations.address_line_1, 
    locations.address_line_2, 
    locations.address_line_3, 
    locations.address_line_4, 
    locations.postcode,
    locations.telephone,
    locations.contact_name AS head_of_department_name,
    locations.contact_email AS head_of_department_email,
    locations.contact_telephone AS head_of_department_phone,
      sub_departments.`dept_code` AS sub_dept_code,
  sub_departments.`dept_name` AS sub_dept_name,
  sub_departments.`pm_name`,
  sub_departments.`pm_telephone`,
  sub_departments.`pm_email`
FROM
    organisations LEFT JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
    LEFT JOIN sub_departments ON organisations.id = sub_departments.`linked_dept_id`
WHERE organisations.organisation_type = '{$organisation_type}';
HEREDOC;
            $view = $_SESSION[$key] = new ViewDepartments();
            $view->setSQL($sql);

            // Add view filters
            $f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Legal Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Postcode: %s");
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
                0=>array(1, 'Department name (asc), Sub Department name', null, 'ORDER BY legal_name, dept_name'));
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
            echo '<div align="center"><table id="tblDepartments" class="table table-bordered">';
            echo <<<HTML
<thead>
    <tr>
        <th>Department Name</th>
        <th>Department Code</th>
        <th>Address Line 1</th>
        <th>Address Line 2</th>
        <th>Address Line 3</th>
        <th>Address Line 4</th>
        <th>Postcode</th>
        <th>Telephone</th>
        <th>Head of Department Name</th>
        <th>Head of Department Email</th>
        <th>Head of Department Phone</th>
        <th>Sub Dept Code</th>
        <th>Sub Dept Name</th>
        <th>Pm Name</th>
        <th>Pm Telephone</th>
        <th>Pm Email</th>
    </tr>
</thead>
HTML;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('/do.php?_action=read_department&id=' . $row['id']);
                echo '<td>' . HTML::cell($row['department_name']) . '</td>';
                echo '<td>' . strtoupper($row['department_code']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_1']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_2']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_3']) . '</td>';
                echo '<td>' . HTML::cell($row['address_line_4']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['telephone']) . '</td>';
                echo '<td>' . HTML::cell($row['head_of_department_name']) . '</td>';
                echo '<td>' . HTML::cell($row['head_of_department_email']) . '</td>';
                echo '<td>' . HTML::cell($row['head_of_department_phone']) . '</td>';
                echo '<td>' . HTML::cell($row['sub_dept_code']) . '</td>';
                echo '<td>' . HTML::cell($row['sub_dept_name']) . '</td>';
                echo '<td>' . HTML::cell($row['pm_name']) . '</td>';
                echo '<td>' . HTML::cell($row['pm_telephone']) . '</td>';
                echo '<td>' . HTML::cell($row['pm_email']) . '</td>';
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