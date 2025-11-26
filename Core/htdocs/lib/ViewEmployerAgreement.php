<?php
class ViewEmployerAgreement extends View
{

    public static function getInstance(PDO $link, $org_id)
    {
        $key = 'view_'.__CLASS__;

        //if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
	SELECT
		*
	FROM
		employer_agreement
	WHERE employer_id = '$org_id';
HEREDOC;

            $view = $_SESSION[$key] = new ViewEmployerAgreement();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        {
            if($st)
            {
                echo $this->getViewNavigator('left');
                echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
                echo '<thead><tr><th>&nbsp;</th><th>Agreement Date</th><th>Comments</th><th>Form</th><th>Status</th><th>Next Action</th></tr></thead>';

                echo '<tbody>';
                while($row = $st->fetch())
                {
                    $id = $row['id'];
                    $employer_id = $row['employer_id'];
                    //echo HTML::viewrow_opening_tag('/do.php?_action=edit_employer_agreement&id=' . $row['id']);
                    if(DB_NAME != "am_baltic" && DB_NAME != "am_baltic_demo")
                        echo '<td><img src="/images/file.png" /></td>';
                    echo '<td align="left">' . HTML::cell(Date::toMedium($row['meeting_date'])) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['comments']) . '</td>';
                    echo "<td style='text-align: center'><a href='do.php?_action=edit_employer_agreement_form&source=1&employer_id={$row['employer_id']}&id={$row['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                    if($row['signature_assessor_font']=='')
                    {
                        echo '<td align="left">' . HTML::cell("In progress") . '</td>';
                        echo '<td align="left">' . HTML::cell("") . '</td>';
                    }
                    elseif($row['signature_assessor_font']!='' && $row['signature_employer_font']=='')
                    {
                        $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM ea_forms_audit WHERE form_id = '$id' AND description = 'Employer Agreement Emailed to Employer'");
                        if($emailed)
                        {
                            echo '<td>Awaiting employer</td>';
                            echo '<td><span class="button" onclick="sendEmailEmployerAgreement(3,' . $id . ',' . $employer_id . ')">Email Again</a></td>';
                        }
                        else
                        {
                            echo '<td>Provider Signed</td>';
                            echo '<td><span class="button" onclick="sendEmailEmployerAgreement(3,' . $id . ',' . $employer_id . ')">Email Employer</a></td>';
                        }
                    }
                    else
                    {
                        echo '<td align="left">' . HTML::cell("Complete") . '</td>';
                        echo "<td style='text-align: center'><a href='do.php?_action=edit_employer_agreement_form&output=PDF&source=1&employer_id=$employer_id&id={$id}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                    }
                    echo '</tr>';
                }

                echo '</tbody></table></div>';
                echo $this->getViewNavigator('left');

            }
            else
            {
                throw new DatabaseException($link, $this->getSQL());
            }
        }
    }
}
?>