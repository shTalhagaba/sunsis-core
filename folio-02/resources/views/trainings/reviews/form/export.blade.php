<!DOCTYPE html>
<html lang="en">

<head>
    <title>Folio | Review Details</title>
    <style>
        th {
            color: #000;
            background-color: #d2d6de !important
        }

        .page-break {
            page-break-after: always;
        }

        /** 
        Set the margins of the page to 0, so the footer and the header
        can be of the full height and width !
        **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
            font-size: small;
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            text-align: center;
            line-height: 1.5cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 2cm;
            font-size: x-small;

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            text-align: center;
            line-height: .5cm;
        }
    </style>
</head>

<body>
    <header>
        <img height="40px;" src="{{ asset('images/logos/'.App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="Logo"> &nbsp; 
        {{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}
    </header>
    <footer>
        Review form for {{ $training->student->full_name }} printed on {{ now()->format('d/m/Y H:i:s') }}
    </footer>
    <h4>Learner Details</h4>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th>Learner</th>
            <td>{{ $training->student->full_name }}</td>
        </tr>
        <tr>
            <th>Employer</th>
            <td>{{ $training->employer->legal_name }}</td>
        </tr>
        <tr>
            <th>Programme</th>
            <td>{{ $training->programme->title }}</td>
        </tr>
    </table>
    <h4>Review Details</h4>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th>Review Title</th>
            <td>{{ $review->title }}</td>
        </tr>
        <tr>
            <th>Review Type</th>
            <td>{{ !is_null($review->type_of_review) ? App\Models\LookupManager::getTrainingReviewTypes($review->type_of_review) : '' }}
            </td>
        </tr>
        <tr>
            <th>Scheduled Date and Time</th>
            <td>{{ $review->due_date->format('d/m/Y') }} @ {{ $review->start_time . ' - ' }}{{ $review->end_time }}</td>
        </tr>
        <tr>
            <th>Assessor</th>
            <td>{{ optional(App\Models\User::find($review->assessor))->full_name }}</td>
        </tr>
        <tr>
            <th>General Comments</th>
            <td>{!! nl2br(e($review->comments)) !!}</td>
        </tr>
        <tr>
            <th>Last Review Actual Date</th>
            <td>{{ $formData['svLastReviewActualDate'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Total Contracted Hours on Programme</th>
            <td>{{ $formData['svTotalContrcatedHours'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Total OTJ Hours</th>
            <td>{{ $formData['svTotalOtj'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Target Percentage of OTJ Hours</th>
            <td>{{ $formData['svTargetPercentageOfOtj'] ?? 0 }}%</td>
        </tr>
        <tr>
            <th>Actual OTJ Hours to Date</th>
            <td>{{ $formData['svActualOtjToDate'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Expected OTJ Hours to Date</th>
            <td>{{ $formData['svExpectedOtjToDate'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Expected OTJ Hours Deviation</th>
            <td>{{ $formData['svExpectedOtjDeviation'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Last OTJ Activity</th>
            <td>
                @if (isset($formData['svLastOtjActivity']['id']))
                    {{ 'Type: ' . \App\Models\LookupManager::getOtjDdl($formData['svLastOtjActivity']['type']) }}<br>
                    {{ 'Date: ' . Carbon\Carbon::parse($formData['svLastOtjActivity']['date'])->format('d/m/Y') }}<br>
                    {{ 'Duration: ' . $formData['svLastOtjActivityDurationFormatted'] }}
                @endif
            </td>
        </tr>
    </table>
    <hr>
    @foreach ($training->portfolios as $portfolio)
        <table border="1" style="width: 100%;" cellpadding="6">
            <tr>
                <th colspan="3">
                    <h4>{{ $portfolio->title }}</h4>
                    Expected Completion Date: {{ $portfolio->planned_end_date }}
                </th>
            </tr>
            <tr>
                <th>Unit</th>
                <th>Signoff Progress</th>
                <th>Changes since last review (%)</th>
            </tr>
            @foreach ($portfolio->units as $unit)
                <tr>
                    <td>
                        {{ $unit->title }}
                    </td>
                    <td align="center">
                        @if (isset($formData['svPortfolioUnits']) && array_key_exists($unit->id, $formData['svPortfolioUnits']))
                            {{ $formData['svPortfolioUnits'][$unit->id]->progress }}%
                        @endif
                    </td>
                    <td align="center">
                        @if (isset($formData['svPortfolioUnits']) && array_key_exists($unit->id, $formData['svPortfolioUnits']))
                            {{ $formData['svPortfolioUnits'][$unit->id]->progress - $formData['svPortfolioUnits'][$unit->id]->progress_on_last_review }}%
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endforeach

    <h4>Additional Details</h4>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th style="width: 30%">Actual Date</th>
            <td>{{ !is_null($review->meeting_date) ? \Carbon\Carbon::parse($review->meeting_date)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <th>Behaviours and attitude to learning</th>
            <td>
                @php
                    if(isset($formData['performance']) && $formData['performance'] == 1)
                    {
                        echo 'Outstanding';
                    }
                    elseif(isset($formData['performance']) && $formData['performance'] == 2)
                    {
                        echo 'Good';
                    }
                    elseif(isset($formData['performance']) && $formData['performance'] == 3)
                    {
                        echo 'Requires Improvement';
                    }
                @endphp
            </td>
        </tr>
    </table>
    
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Functional Skills</th>
        </tr>
        <tr>
            <th style="width: 30%">Have your Functional Skills targets been met since last review?</th>
            <td>
                @php
                    if(isset($formData['fs_targets_met']) && $formData['fs_targets_met'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['fs_targets_met']) && $formData['fs_targets_met'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Are you booked in for tests?</th>
            <td>
                @php
                    if(isset($formData['booked_in_for_tests']) && $formData['booked_in_for_tests'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['booked_in_for_tests']) && $formData['booked_in_for_tests'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Has current delivery pathway been reviewed, have you achieved the SMART targets set previously and discussed targets moving forward for next review?</th>
            <td>
                @php
                    if(isset($formData['fs_smart_targets_achieved']) && $formData['fs_smart_targets_achieved'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['fs_smart_targets_achieved']) && $formData['fs_smart_targets_achieved'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Action/Update on Target</th>
            <td>
                {!! isset($formData['target_details']) ? nl2br(e($formData['target_details'])) : '' !!}
            </td>
        </tr>
    </table>

    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Off the Job (OTJ) Plan</th>
        </tr>
        <tr>
            <th style="width: 30%">Are your OTJ hours on target?</th>
            <td>
                @php
                    if(isset($formData['otj_on_target']) && $formData['otj_on_target'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['otj_on_target']) && $formData['otj_on_target'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">(including standardised safeguarding and Prevent training) Have you added to your timesheet this week?</th>
            <td>
                @php
                    if(isset($formData['added_timesheet']) && $formData['added_timesheet'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['added_timesheet']) && $formData['added_timesheet'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Action/Update against Plan</th>
            <td>
                {!! isset($formData['otj_plan_details']) ? nl2br(e($formData['otj_plan_details'])) : '' !!}
            </td>
        </tr>
    </table>
    
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Main Aim</th>
        </tr>
        <tr>
            <th style="width: 30%">Are you on target towards your main aim standard?</th>
            <td>
                @php
                    if(isset($formData['on_target_towards_main_aim']) && $formData['on_target_towards_main_aim'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['on_target_towards_main_aim']) && $formData['on_target_towards_main_aim'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Have you met your targets set at your previous review?</th>
            <td>
                @php
                    if(isset($formData['main_aim_previous_targets_achieved']) && $formData['main_aim_previous_targets_achieved'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['main_aim_previous_targets_achieved']) && $formData['main_aim_previous_targets_achieved'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">What are your SMART targets moving forward?</th>
            <td>
                {!! isset($formData['smart_targets_details']) ? nl2br(e($formData['smart_targets_details'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Have you developed Knowledge, skills and behaviours since last review?</th>
            <td>
                @php
                    if(isset($formData['ksb_developed']) && $formData['ksb_developed'] == 'Yes')
                    {
                        echo 'Yes';
                    }
                    elseif(isset($formData['ksb_developed']) && $formData['ksb_developed'] == 'No')
                    {
                        echo 'No';
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th style="width: 30%">How will you develop these before the next review?</th>
            <td>
                {!! isset($formData['future_ksb_plan']) ? nl2br(e($formData['future_ksb_plan'])) : '' !!}
            </td>
        </tr>
    </table>

    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">End Point Assessment</th>
        </tr>
        <tr>
            <th style="width: 30%">What progress has been made towards EPA preparation since last review? What SMART targets towards EPA are set?</th>
            <td>
                {!! isset($formData['epa_details']) ? nl2br(e($formData['epa_details'])) : '' !!}
            </td>
        </tr>
    </table>
    
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Vocational/Enhancement Workshops</th>
        </tr>
        <tr>
            <th style="width: 30%">Have you attended any vocational/enhancement workshops since your last review?</th>
            <td>
                {!! isset($formData['workshops']) ? nl2br(e($formData['workshops'])) : '' !!}
            </td>
        </tr>
    </table>
    
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Wellbeing</th>
        </tr>
        <tr>
            <th style="width: 30%">How well are you developing and progressing through your programme? (general wellbeing to be discussed).</th>
            <td>
                {!! isset($formData['wellbeing']) ? nl2br(e($formData['wellbeing'])) : '' !!}
            </td>
        </tr>
    </table>
    
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th style="width: 30%" colspan="10">On a scale of one to ten how motivated and confident are you to achieve your programme within the timeframe set?</th>
        </tr>
        <tr>
            @foreach (range(1, 10) as $i)
            {!! (isset($formData['rate_confidence']) && $formData['rate_confidence'] == $i) ? '<th>' . $formData['rate_confidence'] . '</th>' : '<td align="center">' . $i . '</td>' !!}
            @endforeach
        </tr>
    </table>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th style="width: 30%">Comments on how we can help/support you to complete on time.</th>
            <td>
                {!! isset($formData['help_to_achieve_on_time']) ? nl2br(e($formData['help_to_achieve_on_time'])) : '' !!}
            </td>
        </tr>
    </table>

    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2">Progression Pathway</th>
        </tr>
        <tr>
            <th style="width: 30%">Short Term Goals</th>
            <td>
                {!! isset($formData['short_goals']) ? nl2br(e($formData['short_goals'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Medium Term Goals</th>
            <td>
                {!! isset($formData['medium_goals']) ? nl2br(e($formData['medium_goals'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Long Term Goals</th>
            <td>
                {!! isset($formData['long_goals']) ? nl2br(e($formData['long_goals'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Signposting for career goals/ambitions (add links)</th>
            <td>
                {!! isset($formData['signposting']) ? nl2br(e($formData['signposting'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Are you concerned or have experienced any safeguarding/Prevent, health and safety, equality & diversity issues since your last review?</th>
            <td>
                {!! isset($formData['safeguarding_issues']) ? nl2br(e($formData['safeguarding_issues'])) : '' !!}
            </td>
        </tr>
    </table>
    <hr>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th style="width: 30%">Assessor/Tutor comments</th>
            <td>
                {!! isset($formData['assessor_comments']) ? nl2br(e($formData['assessor_comments'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Learner Comments</th>
            <td>
                {!! isset($formData['learner_comments']) ? nl2br(e($formData['learner_comments'])) : '' !!}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Line Manger/Employer Comments</th>
            <td>
                {!! isset($formData['employer_comments']) ? nl2br(e($formData['employer_comments'])) : '' !!}
            </td>
        </tr>
    </table>


</body>

</html>
