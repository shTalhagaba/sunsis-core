<?php
class GetQualifications extends View
{
    public static function getInstance(PDO $link, $fid)
    {
        $cn = DB_NAME;
        if(SystemConfig::getEntityValue($link, "manager") && $_SESSION['user']->type == User::TYPE_MANAGER)
        {
            $db = DB_NAME;
            $sql = <<<HEREDOC
SELECT DISTINCT 
  qualifications.auto_id,     
  REPLACE(qualifications.id, '/', '') AS id,
  qualifications.`internaltitle`,
  qualifications.`total_proportion`,
  EXTRACTVALUE(qualifications.evidences, 'count(//unit[@mandatory="true"])') AS mandatory_units,
  EXTRACTVALUE(qualifications.evidences, 'count(//unit)') AS total_units,
  qualifications.`active`,
  (SELECT CONCAT(id, ' - ', description) FROM lookup_qual_type WHERE id = qualifications.qualification_type) AS qual_type, 
  (SELECT CONCAT(id, ' - ', description) FROM lookup_qual_level WHERE id = qualifications.level) AS qual_level,
  IF(framework_qualifications.`id` IS NOT NULL, '1', '0') AS in_framework,
  framework_qualifications.`proportion`,
  framework_qualifications.`main_aim`,
  framework_qualifications.`duration_in_months`
FROM
  qualifications
  INNER JOIN $db.provider_qualifications
    ON (REPLACE($db.provider_qualifications.qualification_id, '/', '' ) = REPLACE(qualifications.id, '/', '')
    AND $db.provider_qualifications.internaltitle = qualifications.internaltitle)
  LEFT JOIN framework_qualifications 
    ON (REPLACE(framework_qualifications.`id`, '/', '') = REPLACE(qualifications.`id`, '/', '') 
    AND framework_qualifications.`internaltitle` = qualifications.`internaltitle` AND framework_qualifications.`framework_id` = '$fid')
ORDER BY in_framework DESC, qualifications.title ASC
;
HEREDOC;
        }
        else
        {
            $sql = <<<HEREDOC
SELECT DISTINCT 
  qualifications.auto_id,
  REPLACE(qualifications.id, '/', '') AS id,
  qualifications.`internaltitle`,
  qualifications.`total_proportion`,
  EXTRACTVALUE(qualifications.evidences, 'count(//unit[@mandatory="true"])') AS mandatory_units,
  EXTRACTVALUE(qualifications.evidences, 'count(//unit)') AS total_units,
  qualifications.`active`,
  (SELECT CONCAT(id, ' - ', description) FROM lookup_qual_type WHERE id = qualifications.qualification_type) AS qual_type, 
  (SELECT CONCAT(id, ' - ', description) FROM lookup_qual_level WHERE id = qualifications.level) AS qual_level,
  IF(framework_qualifications.`id` IS NOT NULL, '1', '0') AS in_framework,
  framework_qualifications.`proportion`,
  framework_qualifications.`main_aim`,
  framework_qualifications.`duration_in_months`
FROM
	qualifications LEFT JOIN framework_qualifications 
    ON (REPLACE(framework_qualifications.`id`, '/', '') = REPLACE(qualifications.`id`, '/', '') 
    AND framework_qualifications.`internaltitle` = qualifications.`internaltitle` AND framework_qualifications.`framework_id` = '$fid') 
WHERE clients LIKE '%$cn%'
ORDER BY in_framework DESC, qualifications.title ASC
;
HEREDOC;
        }

        $view = new GetQualifications();
        $view->setSQL($sql);

        return $view;
    }


    public function render(PDO $link, $fid)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());

        if($st)
        {
            echo '<div align="left"><table class="table table-bordered">';
            echo '<thead><tr class="bg-light-blue-gradient"><th>&nbsp;</th><th style="width: 45%;">Internal Title</th><th>Type</th><th>Level</th>';
            echo '<th>QAN</th><th>Proportion</th><th>Duration</th><th>Main Aim</th></tr></thead>';
            $counter=1;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                $qid = $row['auto_id'];
                $active_qual = $row['active'] == 0 ? 'text-red' : '';
                $active_qual_row_title = $row['active'] == 0 ? ' title="This qualification is not active." ' : '';
                echo $row['in_framework'] > 0 ? '<tr id="row'.$qid.'" class="bg-warning '.$active_qual.'"'.$active_qual_row_title.'>' : '<tr class="'.$active_qual.'"'.$active_qual_row_title.'>';

                echo $row['in_framework'] > 0 ?
                    '<td align="center"><input type="checkbox" name="selectedQuals[]" checked value="' . $qid . '" />' :
                    '<td align="center"><input type="checkbox" name="selectedQuals[]" value="' . $qid . '" />';

                echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['qual_type']) . "</td>";
                echo '<td align="left">' . htmlspecialchars((string)$row['qual_level']) . "</td>";

                echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
                echo '<td align="center"><input type="text" name="proportion'.$qid.'" onkeypress= "return numbersonly(this, event)" size="2" value="' . $row['proportion'] . '" >'  . "</td>";
                echo '<td align="center"><input type="text" name="duration'.$qid.'" onkeypress= "return numbersonly(this, event)" size="2" value="' . $row['duration_in_months'] . '" >'  . "</td>";

                echo $row['main_aim'] == 1 ?
                    '<td align="center"><input type="radio" title="' . "Main Aim" . '" name="main_aim_radio" checked value="' . $qid . '" />' :
                    '<td align="center"><input type="radio" title="' . "Main Aim" . '" name="main_aim_radio" value="' . $qid . '" />';

                echo '</tr>';
            }
            echo '</tbody></table></div>';
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>