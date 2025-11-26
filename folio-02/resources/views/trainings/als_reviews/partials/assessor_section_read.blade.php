<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">
                            What reasonable adjustments have taken place this month?
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reasonableAdjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->description }}</td>
                            <td>{!! in_array($adjustment->id, $selectedReasonableAdjustmentsAssessor)
                                ? '<i class="fa fa-check-circle green fa-2x"></i>'
                                : '' !!}</td>
                        </tr>
                    @endforeach
                    @if (!empty($alsReview->reasonable_adjustments_other_assessor))
                        <tr>
                            <td colspan="2">
                                {!! nl2br(e($alsReview->reasonable_adjustments_other_assessor)) !!}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <col width="30%" />
                <col width="70%" />
                <tr>
                    <th>
                        Dates of sessions/preparation etc 
                    </th>
                    <td>
                        {!! isset($formData->date_of_sessions) ? nl2br(e($formData->date_of_sessions)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Intent of reasonable adjustments 
                    </th>
                    <td>
                        {!! isset($formData->intent_of_reasonable_adjustments) ? nl2br(e($formData->intent_of_reasonable_adjustments)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Implementation of reasonable adjustments 
                    </th>
                    <td>
                        {!! isset($formData->implementation_of_reasonable_adjustments) ? nl2br(e($formData->implementation_of_reasonable_adjustments)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Impact of reasonable adjustments 
                    </th>
                    <td>
                        {!! isset($formData->impact_of_reasonable_adjustments) ? nl2br(e($formData->impact_of_reasonable_adjustments)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Completed with learner</th>
                </tr>
                <tr>
                    <th>
                        What topics/KSBs have you had support with this month? (both for your diploma and/or functional skills)
                    </th>
                    <td>
                        {!! isset($formData->what_topics_you_had_support) ? nl2br(e($formData->what_topics_you_had_support)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        What support have had to help you with the above?
                    </th>
                    <td>
                        {!! isset($formData->what_support_you_had) ? nl2br(e($formData->what_support_you_had)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        What do you feel more positive and/or confident about this month?
                    </th>
                    <td>
                        {!! isset($formData->what_do_you_feel_positive) ? nl2br(e($formData->what_do_you_feel_positive)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        How can you use this at work?
                    </th>
                    <td>
                        {!! isset($formData->how_can_use_this_at_work) ? nl2br(e($formData->how_can_use_this_at_work)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Do you feel confident in the topics to support EPA/exams/assessments?  Why?
                    </th>
                    <td>
                        {!! isset($formData->do_you_feel_confident) ? nl2br(e($formData->do_you_feel_confident)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Is there anything you don't feel you are progressing with? 
                    </th>
                    <td>
                        {!! isset($formData->anything_not_progressing) ? nl2br(e($formData->anything_not_progressing)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Do you feel like you are making good progress?
                    </th>
                    <td>
                        {!! isset($formData->making_good_progress) ? nl2br(e($formData->making_good_progress)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Do you think you will achieve your qualification(s) by your end date?
                    </th>
                    <td>
                        {!! isset($formData->confident_to_achieve) ? nl2br(e($formData->confident_to_achieve)) : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Is there any more we can do to support your progress?
                    </th>
                    <td>
                        {!! isset($formData->anything_else) ? nl2br(e($formData->anything_else)) : '' !!}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
