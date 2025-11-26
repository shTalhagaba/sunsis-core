<?php
class view_standard_main_aim implements IAction
{
    function sortArrayByArray(Array $array, Array $orderArray) {
        $ordered = array();
        foreach($orderArray as $key) {
            if(array_key_exists($key,$array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $ordered + $array;
    }


    public function execute(PDO $link)
    {
        $framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

        $sql = <<<SQL
SELECT DISTINCT
  frameworks.id,
  frameworks.`title`,
  frameworks.`framework_code`
FROM
  frameworks
  INNER JOIN framework_qualifications
    ON frameworks.id = framework_qualifications.`framework_id`
WHERE framework_qualifications.`main_aim` = 1
ORDER BY frameworks.`framework_code`,
  frameworks.`title`
;
SQL;
        $frameworksDDL = DAO::getResultset($link, $sql);

        $text_html = 'Select standard from the dropdown list and press View Detail';
        if($framework_id != '')
        {
            $text_html = $this->generate($link, $framework_id);
        }

        if(isset($_REQUEST['export']) && $_REQUEST['export'] == 'excel')
        {
            $this->exportToExcel($link, $framework_id);
            exit;
        }


        include('tpl_view_standard_main_aim.php');

    }

    private function generate(PDO $link, $framework_id)
    {
        $framework = Framework::loadFromDatabase($link, $framework_id);

        $text_html = "";
        $text_html .= "<table class='table table-bordered table-condensed small'>";
        $text_html .= "<thead class='bg-gray'>";
        $text_html .= "<th></th><th>Unit</th><th>Element</th><th>Evidence</th><th>Delivery Hours</th>";

        $text_html .= "</thead>";
        $text_html .= "<tbody>";

        $main_aim = $framework->getMainAim($link);
        $evidences = XML::loadSimpleXML($main_aim->evidences);

	$rowNo = 0;
        $total_delivery_hours = 0;
        foreach($evidences->units AS $unit_group)
        {
            $unit_group_title = $unit_group->attributes()->title->__toString();
            foreach($unit_group->unit AS $unit)
            {
                $unit_title = $unit->attributes()->title->__toString();
                foreach($unit->element AS $element)
                {
                    $element_title = $element->attributes()->title->__toString();
                    foreach($element->evidence AS $evidence)
                    {
                        $attributes = $evidence->attributes();
                        $delhours = '';
                        if(isset($attributes['delhours']))
                        {
                            $delhours = $evidence->attributes()->delhours->__toString();
                            $total_delivery_hours += floatval($delhours);
                        }
                        if($delhours == 'null')
                        {
                            $delhours = '';
                        }
                        $evidence_title = $evidence->attributes()->title->__toString();
                        $text_html .= '<tr>';
                        $text_html .= '<td>' . ++$rowNo . '</td>';
                        $text_html .= '<td>' . $unit_group_title . '</td>';
                        $text_html .= '<td>' . $unit_title . '</td>';
                        $text_html .= '<td>' . $evidence_title . '</td>';
                        $text_html .= ($delhours == '' || $delhours == '0') ?
                            '<td class="text-bold bg-red" style="font-size: medium" align="right">' . $delhours . '</td>' :
                            '<td class="text-bold" style="font-size: medium" align="right">' . $delhours . '</td>';
                        $text_html .= '</tr>';

                    }
                }
            }
        }

        $text_html .= '<tr><td colspan="5" align="right" class="text-bold" style="font-size: large">' . $total_delivery_hours . '</td></tr>';

        $text_html .= "</tbody>";
        $text_html .= "</table>";

        return $text_html;

    }

    private function exportToExcel(PDO $link, $framework_id)
    {
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="DeliveryHoursReport.csv"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }

        $framework = Framework::loadFromDatabase($link, $framework_id);

        $text_csv = "Unit,Element,Evidence,Delivery Hours";
        $text_csv .= "\r\n";

        $main_aim = $framework->getMainAim($link);
        $evidences = XML::loadSimpleXML($main_aim->evidences);

        $total_delivery_hours = 0;
        foreach($evidences->units AS $unit_group)
        {
            $unit_group_title = $unit_group->attributes()->title->__toString();
            foreach($unit_group->unit AS $unit)
            {
                $unit_title = $unit->attributes()->title->__toString();
                foreach($unit->element AS $element)
                {
                    $element_title = $element->attributes()->title->__toString();
                    foreach($element->evidence AS $evidence)
                    {
                        $attributes = $evidence->attributes();
                        $delhours = '';
                        if(isset($attributes['delhours']))
                        {
                            $delhours = $evidence->attributes()->delhours->__toString();
                            $total_delivery_hours += floatval($delhours);
                        }
                        if($delhours == 'null')
                        {
                            $delhours = '';
                        }
                        $evidence_title = $evidence->attributes()->title->__toString();
                        $text_csv .= HTML::csvSafe($unit_group_title) . ',';
                        $text_csv .= HTML::csvSafe($unit_title) . ',';
                        $text_csv .= HTML::csvSafe($evidence_title) . ',';
                        $text_csv .= $delhours;
                        $text_csv .= "\r\n";

                    }
                }
            }
        }

        $text_csv .= ',,,' . $total_delivery_hours;
        $text_csv .= "\r\n";

        echo $text_csv;
    }

}