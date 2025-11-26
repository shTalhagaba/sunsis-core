<?php
class ViewILRAimExcludeReport extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT 
tr.id as tr_id
,tr.firstnames
,tr.surname
,REPLACE(student_qualifications.id,"/","") AS aim_reference
,title AS aim_title
,'' AS exclude 
FROM student_qualifications
LEFT JOIN tr ON tr.id = student_qualifications.tr_id 
WHERE tr.status_code = 1;
HEREDOC;
            $view = $_SESSION[$key] = new ViewILRAimExcludeReport();
            $view->setSQL($sql);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        //if(SOURCE_BLYTHE_VALLEY)
        $rp = new ReflectionProperty('View', 'sql');
        $rp->setAccessible(true);
        $st = $link->query($rp->getValue($this));
        //$st = $link->query($this->getSQL());

        if($st)
        {
            //echo $this->getViewNavigator();
            echo '<table id="tblLogs" class="table table-bordered">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Firstnames</th>
		<th>Surname</th>
		<th>Aim Reference</th>
		<th>Aim Title</th>
		<th>Exclude</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                $submission = 1;
                $aim = $row['aim_reference'];
                $tr_id = $row['tr_id'];
                $exclude = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, \"/Learner/LearningDelivery[LearnAimRef='$aim']/Exclude\") FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 1");
                if($exclude==1)
                {
                    echo '<tr ' . $style . '>';
                    echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                    echo '<td>' . HTML::cell($row['surname']) . '</td>';
                    echo '<td>' . HTML::cell($row['aim_reference']) . '</td>';
                    echo '<td>' . HTML::cell($row['aim_title']) . '</td>';
                    echo '<td>' . HTML::cell($exclude) . '</td>';
                    echo '</tr>';
    
                }
            }
            echo '</tbody></table>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>