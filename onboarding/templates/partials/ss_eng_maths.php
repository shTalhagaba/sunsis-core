<div class="row" style="font-size: medium">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <caption class="text-bold bg-gray">Initial Assessment</caption>
                <tr><th>Session ID</th><th>Course Component Name</th><th>Ability Measurement</th><th>Measuring Assessment Name</th><th>Measured At</th></tr>
                <?php
                $result = DAO::getResultset($link, "SELECT SessionId, AssessmentEOAData FROM bksb_assessment_sessions WHERE ob_learner_id = '{$ob_learner->id}' AND AssessmentType = '1'", DAO::FETCH_ASSOC);
                $html1 = '';
                foreach($result AS $row)
                {
                    if($row['AssessmentEOAData'] != '')
                    {
                        $AssessmentEOAData = json_decode($row['AssessmentEOAData']);
                        if(isset($AssessmentEOAData->AssessmentEOAData))
                        {
                            $AssessmentEOAData = $AssessmentEOAData->AssessmentEOAData;
                            foreach($AssessmentEOAData AS $entry)
                            {
                                $html1 .= '<tr>';
                                $html1 .= '<td class="small">' . $row['SessionId'] . '</td>';
                                $html1 .= isset($entry->CourseComponentName) ? '<td>' . $entry->CourseComponentName . '</td>' : '<td></td>';
                                $html1 .= isset($entry->AbilityMeasurement) ? '<td align="center" class="text-bold">' . $entry->AbilityMeasurement . '</td>' : '<td></td>';
                                $html1 .= isset($entry->MeasuringAssessmentName) ? '<td>' . $entry->MeasuringAssessmentName . '</td>' : '<td></td>';
                                if(isset($entry->MeasuredAt))
                                {
                                    $d = new Date($entry->MeasuredAt);
                                    $html1 .= '<td>' . $d->format(Date::DATETIME) . '</td>';
                                }
                                else
                                {
                                    $html1 .= '<td></td>';
                                }
                                $html1 .= '</tr>';
                            }
                        }
                    }
                }

                echo $html1 == '' ? '<tr><td colspan="5"><i>No records to show</i></td></tr>' : $html1;
                ?>
            </table>
        </div>
    </div>
</div>