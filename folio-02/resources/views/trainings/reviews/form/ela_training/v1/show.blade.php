<div class="row">
    <div class="col-sm-12">
        <div class="widget-box transparent">
            <div class="widget-header"><h5 class="widget-title">Additional Details</h5></div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Actual Date </div>
                            <div class="info-div-value">{{ !is_null($review->meeting_date) ? \Carbon\Carbon::parse($review->meeting_date)->format('d/m/Y') : '' }}</div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Behaviours and attitude to learning </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Have your Functional Skills targets been met since last review? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Are you booked in for tests? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Has current delivery pathway been reviewed, have you achieved the SMART targets set previously and discussed targets moving forward for next review? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Action/Update on Target </div>
                            <div class="info-div-value">
                                {!! isset($formData['target_details']) ? nl2br(e($formData['target_details'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Are your OTJ hours on target? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> (including standardised safeguarding and Prevent training) Have you added to your timesheet this week? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Action/Update against Plan </div>
                            <div class="info-div-value">
                                {!! isset($formData['otj_plan_details']) ? nl2br(e($formData['otj_plan_details'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Are you on target towards your main aim standard? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Have you met your targets set at your previous review? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> What are your SMART targets moving forward? </div>
                            <div class="info-div-value">
                                {!! isset($formData['smart_targets_details']) ? nl2br(e($formData['smart_targets_details'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Have you developed Knowledge, skills and behaviours since last review? </div>
                            <div class="info-div-value">
                                <span>
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
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> How will you develop these before the next review? </div>
                            <div class="info-div-value">
                                {!! isset($formData['future_ksb_plan']) ? nl2br(e($formData['future_ksb_plan'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> What progress has been made towards EPA preparation since last review? What SMART targets towards EPA are set? </div>
                            <div class="info-div-value">
                                {!! isset($formData['epa_details']) ? nl2br(e($formData['epa_details'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Have you attended any vocational/enhancement workshops since last review? </div>
                            <div class="info-div-value">
                                {!! isset($formData['workshops']) ? nl2br(e($formData['workshops'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> How well are you developing and progressing through your programme? (general wellbeing to be discussed).? </div>
                            <div class="info-div-value">
                                {!! isset($formData['wellbeing']) ? nl2br(e($formData['wellbeing'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> On a scale of one to ten how motivated and confident are you to achieve your programme within the timeframe set? </div>
                            <div class="info-div-value">
                                {!! isset($formData['rate_confidence']) ? $formData['rate_confidence'] : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Comments on how we can help/support you to complete on time. </div>
                            <div class="info-div-value">
                                {!! isset($formData['help_to_achieve_on_time']) ? nl2br(e($formData['help_to_achieve_on_time'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Short term goals. </div>
                            <div class="info-div-value">
                                {!! isset($formData['short_goals']) ? nl2br(e($formData['short_goals'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Medium term goals. </div>
                            <div class="info-div-value">
                                {!! isset($formData['medium_goals']) ? nl2br(e($formData['medium_goals'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Long term goals. </div>
                            <div class="info-div-value">
                                {!! isset($formData['long_goals']) ? nl2br(e($formData['long_goals'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Signposting for career goals/ambitions (links). </div>
                            <div class="info-div-value">
                                {!! isset($formData['signposting']) ? nl2br(e($formData['signposting'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Are you concerned or have experienced any safeguarding/Prevent, health and safety, equality & diversity issues since your last review?. </div>
                            <div class="info-div-value">
                                {!! isset($formData['safeguarding_issues']) ? nl2br(e($formData['safeguarding_issues'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessor Comments </div>
                            <div class="info-div-value">
                                {!! isset($formData['assessor_comments']) ? nl2br(e($formData['assessor_comments'])) : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> File/Resource </div>
                            <div class="info-div-value">
                                @if($reviewForm->media->count() > 0)
                                <div class="col-xs-12">
                                    @include('partials.model_media_items', ['mediaFiles' => $reviewForm->media, 'model' => $reviewForm])
                                </div>
                                @endif
                            </div>
                        </div>
                        @if (isset($formData['learner_comments']))
                        <div class="info-div-row">
                            <div class="info-div-name"> Learner Comments </div>
                            <div class="info-div-value">
                                {!! isset($formData['learner_comments']) ? nl2br(e($formData['learner_comments'])) : '' !!}
                            </div>
                        </div>    
                        @endif                        
                        @if (isset($formData['employer_comments']))
                        <div class="info-div-row">
                            <div class="info-div-name"> Line Manger/Employer Comments </div>
                            <div class="info-div-value">
                                {!! isset($formData['employer_comments']) ? nl2br(e($formData['employer_comments'])) : '' !!}
                            </div>
                        </div>    
                        @endif                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>